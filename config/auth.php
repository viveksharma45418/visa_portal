<?php
/**
 * Admin Authentication Configuration
 *
 * To change credentials, update ADMIN_USERNAME and ADMIN_PASSWORD below.
 * The password is stored as a bcrypt hash for security.
 *
 * Default credentials:
 *   Username : admin
 *   Password : Admin@1234
 *
 * To generate a new hash, run in PHP CLI:
 *   php -r "echo password_hash('YourNewPassword', PASSWORD_BCRYPT);"
 */

define('ADMIN_USERNAME',      'admin');
define('ADMIN_PASSWORD_HASH', '$2y$10$cV5CEC4mUS589PXEPbULpe.c5bFjEGsSUp5jAX4kKKUQ3tyTYLgXm');
// Hash above = password_hash('Admin@1234', PASSWORD_BCRYPT)

// Session name and lifetime
define('ADMIN_SESSION_NAME',     'vv_admin_session');
define('ADMIN_SESSION_LIFETIME', 7200); // 2 hours in seconds

// Max login attempts before lockout
define('ADMIN_MAX_ATTEMPTS',  5);
define('ADMIN_LOCKOUT_SECS',  300); // 5 minutes
