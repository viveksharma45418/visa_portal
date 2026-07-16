<?php
/**
 * Visa Vista Global — Application Entry Point & Router
 *
 * Routes:
 *   /                               → Application form
 *   /?page=admin                    → Admin dashboard   (auth required)
 *   /?page=admin&action=login       → Admin login form
 *   /?page=admin&action=logout      → Admin logout
 *   /?page=admin&action=view&id=X   → View application  (auth required)
 *   /?page=admin&action=update_status (POST)             (auth required)
 *   /?page=admin&action=add_note      (POST)             (auth required)
 *   /?page=admin&action=download&id=X&field=Y            (auth required)
 */

// ── Session — must start before any output ─────────────────────
session_name('vv_admin_session');
session_set_cookie_params([
    'lifetime' => 0,        // until browser close
    'path'     => '/',
    'secure'   => false,    // set true in production over HTTPS
    'httponly' => true,     // JS cannot read the cookie
    'samesite' => 'Strict',
]);
session_start();

// ── Bootstrap ──────────────────────────────────────────────────
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/mail.php';
require_once __DIR__ . '/models/ApplicationModel.php';
require_once __DIR__ . '/models/SmtpMailer.php';
require_once __DIR__ . '/models/NotificationHelper.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ApplicationController.php';
require_once __DIR__ . '/controllers/AdminController.php';

// ── Route ──────────────────────────────────────────────────────
$page   = $_GET['page']   ?? 'apply';
$action = $_GET['action'] ?? 'dashboard';

switch ($page) {

    case 'admin':
        $auth = new AuthController();

        // Login & logout handled before AdminController (no auth required)
        if ($action === 'login') {
            $auth->login();
            break;
        }

        if ($action === 'logout') {
            $auth->logout();
            break;
        }

        // All other admin actions require authentication
        $controller = new AdminController(new ApplicationModel());

        switch ($action) {
            case 'view':
                $controller->viewApplication($_GET['id'] ?? null);
                break;

            case 'update_status':
                $controller->updateStatus();
                break;

            case 'add_note':
                $controller->addNote();
                break;

            case 'download':
                $controller->downloadDocument();
                break;

            default:
                $controller->dashboard();
                break;
        }
        break;

    default:
        $controller = new ApplicationController(new ApplicationModel());
        $controller->showApplicationForm();
        break;
}
