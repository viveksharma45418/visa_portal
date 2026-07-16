<?php
/**
 * SMTP Mailer Configuration
 *
 * To send real emails from your local environment (XAMPP) to external addresses like Gmail,
 * update the SMTP settings below with your mail server details.
 *
 * If using Gmail:
 * 1. Set SMTP_HOST to 'smtp.gmail.com'
 * 2. Set SMTP_PORT to 465 (for SSL) or 587 (for TLS)
 * 3. Set SMTP_USER to your Gmail address
 * 4. Set SMTP_PASS to a Gmail "App Password" (do NOT use your account password).
 *    To get an App Password:
 *    - Enable 2-Step Verification on your Google Account.
 *    - Go to Security > App Passwords, select 'Mail' and 'Other (custom name)', and generate.
 * 5. Set SMTP_SECURE to 'ssl' or 'tls'
 */

define('SMTP_ENABLED', true); // Set to false to disable real SMTP sending and only use local logs

define('SMTP_HOST',   'smtp.ethereal.email');
define('SMTP_PORT',   587);
define('SMTP_USER',   'kp2sy4zpzihn6xaw@ethereal.email'); // Ethereal test user
define('SMTP_PASS',   'uyuXKw4hnvM1EpU71Q'); // Ethereal test password
define('SMTP_SECURE', 'tls'); // 'ssl', 'tls', or null for plain text

define('SMTP_FROM_EMAIL', 'support@visavistaglobal.com');
define('SMTP_FROM_NAME',  'Visa Vista Global');
