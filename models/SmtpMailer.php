<?php
/**
 * SmtpMailer — A lightweight, pure-PHP SMTP client with TLS/SSL support.
 * Has zero dependencies and works directly from sockets.
 */
class SmtpMailer
{
    private string $host;
    private int $port;
    private ?string $username;
    private ?string $password;
    private ?string $secure;
    private array $logs = [];

    public function __construct(string $host, int $port, ?string $username = null, ?string $password = null, ?string $secure = null)
    {
        $this->host     = $host;
        $this->port     = $port;
        $this->username = $username;
        $this->password = $password;
        $this->secure   = $secure;
    }

    /**
     * Sends an HTML email using SMTP.
     */
    public function send(string $to, string $subject, string $htmlContent, array $headers = []): bool
    {
        $fromEmail = SMTP_FROM_EMAIL;
        $fromName  = SMTP_FROM_NAME;

        // Build headers
        $emailHeaders = [
            "MIME-Version: 1.0",
            "Content-type: text/html; charset=utf-8",
            "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromEmail}>",
            "Reply-To: {$fromEmail}",
            "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=",
            "To: <{$to}>",
            "Date: " . date('r'),
            "X-Mailer: PHP-SmtpMailer",
        ];

        foreach ($headers as $key => $val) {
            $emailHeaders[] = "{$key}: {$val}";
        }

        $message = implode("\r\n", $emailHeaders) . "\r\n\r\n" . $htmlContent . "\r\n";

        $socketHost = $this->host;
        if (strtolower($this->secure) === 'ssl') {
            $socketHost = 'ssl://' . $this->host;
        }

        $socket = @fsockopen($socketHost, $this->port, $errno, $errstr, 15);
        if (!$socket) {
            $this->log("Connection failed: {$errstr} ({$errno})");
            return false;
        }

        // Read greeting
        if (!$this->expect($socket, '220')) {
            fclose($socket);
            return false;
        }

        // EHLO
        $heloHost = $_SERVER['SERVER_NAME'] ?? 'localhost';
        fwrite($socket, "EHLO {$heloHost}\r\n");
        $this->log("EHLO {$heloHost}");
        if (!$this->expect($socket, '250')) {
            fclose($socket);
            return false;
        }

        // STARTTLS if TLS chosen
        if (strtolower($this->secure) === 'tls') {
            fwrite($socket, "STARTTLS\r\n");
            $this->log("STARTTLS");
            if (!$this->expect($socket, '220')) {
                fclose($socket);
                return false;
            }

            // Enable crypto on socket
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                $this->log("TLS encryption handshake failed");
                fclose($socket);
                return false;
            }

            // Repeat EHLO after STARTTLS
            fwrite($socket, "EHLO {$heloHost}\r\n");
            $this->log("EHLO (encrypted)");
            if (!$this->expect($socket, '250')) {
                fclose($socket);
                return false;
            }
        }

        // Authentication if credentials present
        if ($this->username && $this->password) {
            fwrite($socket, "AUTH LOGIN\r\n");
            $this->log("AUTH LOGIN");
            if (!$this->expect($socket, '334')) {
                fclose($socket);
                return false;
            }

            fwrite($socket, base64_encode($this->username) . "\r\n");
            $this->log("SEND USER");
            if (!$this->expect($socket, '334')) {
                fclose($socket);
                return false;
            }

            fwrite($socket, base64_encode($this->password) . "\r\n");
            $this->log("SEND PASS");
            if (!$this->expect($socket, '235')) {
                fclose($socket);
                return false;
            }
        }

        // MAIL FROM
        fwrite($socket, "MAIL FROM:<{$fromEmail}>\r\n");
        $this->log("MAIL FROM:<{$fromEmail}>");
        if (!$this->expect($socket, '250')) {
            fclose($socket);
            return false;
        }

        // RCPT TO
        fwrite($socket, "RCPT TO:<{$to}>\r\n");
        $this->log("RCPT TO:<{$to}>");
        if (!$this->expect($socket, '250')) {
            fclose($socket);
            return false;
        }

        // DATA
        fwrite($socket, "DATA\r\n");
        $this->log("DATA");
        if (!$this->expect($socket, '354')) {
            fclose($socket);
            return false;
        }

        // Send email message body
        // Normalize single dot lines in email body to prevent ending transmission early
        $message = str_replace("\n.", "\n..", $message);
        fwrite($socket, $message . "\r\n.\r\n");
        $this->log("SEND BODY");
        if (!$this->expect($socket, '250')) {
            fclose($socket);
            return false;
        }

        // QUIT
        fwrite($socket, "QUIT\r\n");
        $this->log("QUIT");
        $this->expect($socket, '221');

        fclose($socket);
        return true;
    }

    /**
     * Get transaction logs for debugging.
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    private function log(string $msg): void
    {
        $this->logs[] = $msg;
    }

    private function expect($socket, string $code): bool
    {
        $response = '';
        while ($str = fgets($socket, 515)) {
            $response .= $str;
            if (substr($str, 3, 1) === ' ') {
                break;
            }
        }
        $this->log("Server response: " . trim($response));
        return strpos($response, $code) === 0;
    }
}
