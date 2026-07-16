<?php
/**
 * NotificationHelper — Handles sending email and message notifications to applicants.
 */
class NotificationHelper
{
    /**
     * Sends an email notification to the applicant and writes a local log in uploads/mail_log.txt.
     */
    public static function sendStatusEmail(array $app, string $newStatus, string $notes = ''): bool
    {
        $email = $app['email'] ?? '';
        $name  = $app['full_name'] ?? 'Applicant';
        $appId = $app['application_id'] ?? '';

        if (empty($email)) {
            return false;
        }

        $subject = "Visa Vista Global — Application Status Update: {$newStatus} ({$appId})";

        // HTML Email Template matching premium Dark Blue + Gold scheme
        $htmlContent = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #EEF2FF; margin: 0; padding: 20px; color: #0F172A; }
                .email-container { max-width: 600px; background-color: #FFFFFF; margin: 0 auto; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(13,27,62,0.08); border: 1px solid #E2E8F0; }
                .header { background: linear-gradient(135deg, #070E20, #0D1B3E); padding: 30px; text-align: center; border-bottom: 3px solid #D4AF37; }
                .logo-circle { width: 50px; height: 50px; background: linear-gradient(135deg, #D4AF37, #A8891A); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; color: #070E20; font-weight: bold; margin-bottom: 10px; line-height: 50px; text-align: center; }
                .brand-name { font-size: 20px; font-weight: bold; color: #FFFFFF; display: block; margin: 0; }
                .brand-tagline { font-size: 10px; color: #E8CC6A; letter-spacing: 1.5px; text-transform: uppercase; display: block; margin-top: 4px; }
                .content { padding: 40px 30px; line-height: 1.6; }
                .greeting { font-size: 18px; font-weight: bold; color: #0D1B3E; margin-bottom: 20px; }
                .status-card { background: #EEF2FF; border-left: 4px solid #D4AF37; padding: 20px; border-radius: 8px; margin: 25px 0; }
                .status-label { font-size: 12px; font-weight: bold; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; }
                .status-value { font-size: 22px; font-weight: 800; color: #0D1B3E; margin-top: 4px; }
                .notes-title { font-size: 13px; font-weight: bold; color: #0D1B3E; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase; }
                .notes-value { font-size: 14px; color: #475569; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 12px; border-radius: 6px; white-space: pre-wrap; }
                .btn-container { text-align: center; margin: 30px 0 10px; }
                .btn-action { background: linear-gradient(135deg, #D4AF37, #A8891A); color: #070E20 !important; font-weight: bold; text-decoration: none; padding: 12px 30px; border-radius: 6px; display: inline-block; font-size: 14px; box-shadow: 0 4px 12px rgba(212,175,55,0.3); }
                .footer { background-color: #070E20; color: rgba(255,255,255,0.4); text-align: center; padding: 20px; font-size: 12px; border-top: 1px solid rgba(255,255,255,0.1); }
                .footer a { color: #E8CC6A; text-decoration: none; }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <div class='logo-circle'>🌍</div>
                    <div class='brand-name'>Visa Vista Global</div>
                    <div class='brand-tagline'>Authorized Study Visa Consultancy</div>
                </div>
                <div class='content'>
                    <div class='greeting'>Dear {$name},</div>
                    <p>We are writing to update you on the progress of your Study Visa Application.</p>
                    <p>Our consultants have reviewed your application and the current status has been updated:</p>
                    
                    <div class='status-card'>
                        <div class='status-label'>Application Reference</div>
                        <div style='font-size:16px; font-weight:bold; color:#0D1B3E;'>{$appId}</div>
                        <div class='status-label' style='margin-top:15px;'>Current Status</div>
                        <div class='status-value'>{$newStatus}</div>
                        
                        " . (!empty($notes) ? "
                        <div class='notes-title'>Consultant's Remarks / Action Required</div>
                        <div class='notes-value'>{$notes}</div>
                        " : "") . "
                    </div>

                    <p>If action or further documentation is requested, please prepare your documents and reply to this email or contact your dedicated visa officer as soon as possible.</p>
                    
                    <div class='btn-container'>
                        <a href='http://localhost/visa_portal/' class='btn-action'>Visit Application Portal</a>
                    </div>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " Visa Vista Global. All rights reserved.</p>
                    <p>Authorized Visa Consultancy &bull; AIRC Registered &bull; ISO 9001:2015 Certified</p>
                </div>
            </div>
        </body>
        </html>
        ";

        // Headers for HTML Mail fallback
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM_EMAIL . ">\r\n";
        $headers .= "Reply-To: " . SMTP_FROM_EMAIL . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        $smtpUsed = false;
        $smtpSuccess = false;
        $smtpDebug = [];

        // Check if SMTP is enabled and configured
        if (defined('SMTP_ENABLED') && SMTP_ENABLED && !empty(SMTP_USER) && !empty(SMTP_PASS)) {
            $smtpUsed = true;
            $mailer = new SmtpMailer(SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS, SMTP_SECURE);
            $smtpSuccess = $mailer->send($email, $subject, $htmlContent);
            $smtpDebug = $mailer->getLogs();
        }

        // Fallback to PHP's standard mail() if SMTP was not used or failed
        $fallbackUsed = false;
        if (!$smtpSuccess) {
            $fallbackUsed = true;
            @mail($email, $subject, $htmlContent, $headers);
        }

        // Local logs for testing/inspection on XAMPP
        $logDir = BASE_PATH . DIRECTORY_SEPARATOR . 'uploads';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . DIRECTORY_SEPARATOR . 'mail_log.txt';

        $timestamp = date('Y-m-d H:i:s');
        $divider = str_repeat('=', 80);
        
        $logPayload = "{$divider}\n";
        $logPayload .= "Timestamp: {$timestamp}\n";
        $logPayload .= "Recipient: {$email} ({$name})\n";
        $logPayload .= "Subject: {$subject}\n";
        
        if ($smtpUsed) {
            $logPayload .= "Delivery Method: SMTP (" . SMTP_HOST . ":" . SMTP_PORT . ")\n";
            $logPayload .= "SMTP Status: " . ($smtpSuccess ? "SUCCESS" : "FAILED") . "\n";
            $logPayload .= "SMTP Transaction Log:\n" . implode("\n", $smtpDebug) . "\n";
        } else {
            $logPayload .= "Delivery Method: PHP mail() (SMTP details not configured)\n";
        }
        
        if ($fallbackUsed && $smtpUsed) {
            $logPayload .= "Fallback: Triggered standard PHP mail() because SMTP failed.\n";
        }
        
        $logPayload .= "HTML Content Body:\n{$htmlContent}\n";
        $logPayload .= "{$divider}\n\n";

        file_put_contents($logFile, $logPayload, FILE_APPEND | LOCK_EX);

        return $smtpSuccess || !$fallbackUsed;
    }
}
