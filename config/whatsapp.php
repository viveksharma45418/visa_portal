<?php
/**
 * WhatsApp Gateway Configuration
 *
 * To automate sending WhatsApp messages to applicants from the backend,
 * you can use a service provider like Twilio, Ultramsg, or the official Meta Cloud API.
 *
 * By default, this system simulates WhatsApp sending and logs all messages to
 * uploads/whatsapp_log.txt.
 */

define('WHATSAPP_ENABLED', true); // Set to false to disable all WhatsApp notifications

// Choose gateway provider: 'log' (simulated), 'twilio', or 'ultramsg'
define('WHATSAPP_PROVIDER', 'log'); 

// --- Twilio Settings ---
define('TWILIO_ACCOUNT_SID', '');
define('TWILIO_AUTH_TOKEN',  '');
define('TWILIO_FROM_NUMBER', ''); // e.g. 'whatsapp:+14155238886' (Twilio Sandbox number)

// --- Ultramsg Settings ---
define('ULTRAMSG_INSTANCE_ID', '');
define('ULTRAMSG_TOKEN',       '');
