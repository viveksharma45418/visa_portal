<?php
/**
 * AuthController — Admin login / logout.
 */
class AuthController
{
    /**
     * Show the login page (GET) or handle login (POST).
     */
    public function login(): void
    {
        // Already logged in → go to dashboard
        if ($this->isLoggedIn()) {
            $this->redirectToDashboard();
            return;
        }

        $error   = '';
        $blocked = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // CSRF token check
            $token = $_POST['csrf_token'] ?? '';
            if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
                $error = 'Invalid request. Please try again.';
            } else {
                // Rate-limit: check lockout
                $attempts  = $_SESSION['login_attempts'] ?? 0;
                $lastAttempt = $_SESSION['last_attempt_time'] ?? 0;

                if ($attempts >= ADMIN_MAX_ATTEMPTS) {
                    $wait = ADMIN_LOCKOUT_SECS - (time() - $lastAttempt);
                    if ($wait > 0) {
                        $blocked = true;
                        $mins    = ceil($wait / 60);
                        $error   = "Too many failed attempts. Please wait {$mins} minute(s) before trying again.";
                    } else {
                        // Lockout expired — reset
                        $_SESSION['login_attempts']   = 0;
                        $_SESSION['last_attempt_time'] = 0;
                        $attempts = 0;
                    }
                }

                if (!$blocked) {
                    $username = trim($_POST['username'] ?? '');
                    $password = $_POST['password'] ?? '';

                    if (
                        $username === ADMIN_USERNAME &&
                        password_verify($password, ADMIN_PASSWORD_HASH)
                    ) {
                        // Success — regenerate session to prevent fixation
                        session_regenerate_id(true);
                        $_SESSION['admin_logged_in']  = true;
                        $_SESSION['admin_username']   = $username;
                        $_SESSION['admin_login_time'] = time();
                        $_SESSION['login_attempts']   = 0;
                        $this->redirectToDashboard();
                        return;
                    } else {
                        // Failed attempt
                        $_SESSION['login_attempts']    = $attempts + 1;
                        $_SESSION['last_attempt_time'] = time();
                        $remaining = ADMIN_MAX_ATTEMPTS - $_SESSION['login_attempts'];
                        $error = $remaining > 0
                            ? "Invalid username or password. {$remaining} attempt(s) remaining."
                            : 'Too many failed attempts. Account locked for ' . (ADMIN_LOCKOUT_SECS / 60) . ' minutes.';
                    }
                }
            }
        }

        // Regenerate CSRF token for form
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $csrfToken = $_SESSION['csrf_token'];
        include BASE_PATH . '/views/admin/login.php';
    }

    /**
     * Logout — destroy session and redirect to login.
     */
    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: index.php?page=admin&action=login');
        exit;
    }

    /**
     * Check if admin is authenticated.
     * Call this at the top of every admin action.
     */
    public static function requireAuth(): void
    {
        if (empty($_SESSION['admin_logged_in'])) {
            header('Location: index.php?page=admin&action=login');
            exit;
        }

        // Session lifetime check
        $loginTime = $_SESSION['admin_login_time'] ?? 0;
        if ((time() - $loginTime) > ADMIN_SESSION_LIFETIME) {
            session_unset();
            session_destroy();
            header('Location: index.php?page=admin&action=login&expired=1');
            exit;
        }
    }

    /**
     * Check if current visitor is logged in (non-terminating).
     */
    public static function isLoggedIn(): bool
    {
        return !empty($_SESSION['admin_logged_in']);
    }

    private function redirectToDashboard(): void
    {
        header('Location: index.php?page=admin');
        exit;
    }
}
