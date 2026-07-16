# Visa Vista Global — Study Visa Application Portal

A professional, responsive, and secure Study Visa Application Portal built with **Core PHP**, **MySQL**, **Bootstrap 5**, and **Vanilla JavaScript**. Designed to look and feel like a modern Visa Consultancy CRM/client portal.

---

## 🚀 Live Demo Views
* **Client Application Form:** `http://localhost/visa_portal/`
* **Admin Dashboard:** `http://localhost/visa_portal/?page=admin`

---

## ✨ Features

### 📋 Client Portal
- **4-Step Wizard Stepper**:
  - **Step 1 (Personal Info)**: Full name, DOB, email, mobile, city, passport number, passport expiry (minimum 18-month validity check), and marital status.
  - **Step 2 (Education & Destination)**: Choice of 10 countries, intake seasons, course details, budget ranges, and conditional visa refusal explanation block.
  - **Step 3 (Secure Document Upload)**: Clean drag-and-drop style upload cards with size validation (max 10MB), file type restrictions (PDF, JPG, PNG), real-time name/size preview, replace/remove options, and mandatory checks.
  - **Step 4 (Review & Submit)**: Summarizes all choices and documents before checking confirmation and sending.
- **Save Draft Functionality**: Applicants can save progress at any step and receive a unique reference ID (e.g., `VV-2026-0001`) saved inside session storage.
- **Responsive Layout**: Designed first for mobile and tablets.
- **SweetAlert2 Feedback**: High-quality success and warning dialogs.

### 🛡️ Admin Dashboard (Authenticated)
- **Secure Sessions**: Authentication guard preventing unauthorized access.
- **Bcrypt Passwords & Lockout Protection**: Locked login screen after 5 failed login attempts with CSRF protection.
- **Status Pipeline Manager**: Seamless status transitions (Draft → Submitted → Under Review → Documents Requested → Approved → Rejected).
- **Internal Logs & Notes**: Add timestamps and consultant remarks directly into applicant files.
- **Secure File Retrieval**: Files are stored outside public access and served via an auth-guarded script to block direct URL indexing.
- **SMTP Notification Center**: Sends instant premium HTML status updates directly to applicants' email when changes occur.

---

## 🛠️ Tech Stack
- **Backend:** PHP 8.x
- **Database:** MySQL
- **CSS Framework:** Bootstrap 5.3 (via CDN)
- **Icons:** Font Awesome 6 (via CDN)
- **Alerts:** SweetAlert2 (via CDN)
- **Client Scripts:** Native AJAX (Fetch API) & vanilla JS (no jQuery needed)

---

## 📦 Project Directory Structure
```
/config
  ├── database.php             # PDO database connection credentials
  ├── auth.php                 # Admin login username/hash settings
  └── mail.php                 # SMTP host details for notifications
/models
  ├── ApplicationModel.php     # CRUD & file upload storage logic
  ├── SmtpMailer.php           # Lightweight socket-based SMTP client
  └── NotificationHelper.php   # HTML email template construction
/controllers
  ├── AuthController.php       # Admin login, sessions, rate limits
  ├── ApplicationController.php# Main public application views
  └── AdminController.php      # Admin views & document retrieval
/views
  ├── apply.php                # Stepper form & review panel
  └── admin/
      ├── login.php            # Admin auth page
      ├── dashboard.php        # Applications database search & filter
      └── view_application.php # Applicant details, notes & status actions
/ajax
  ├── save_draft.php           # Draft saving endpoint
  └── submit_application.php   # Submission verification endpoint
/assets
  ├── css/style.css            # Dark Navy & Gold layout styles
  └── js/app.js                # Stepper validation & file handling scripts
/database
  └── schema.sql               # MySQL database schema setup
/uploads
  ├── .htaccess                # Disable raw script execution for security
  └── applications/            # Store user documents securely
index.php                      # Main routing controller
.gitignore                     # Exclude document uploads and logs
```

---

## ⚙️ Local Installation & Setup

1. **Clone the repository** to your XAMPP `htdocs` directory:
   ```bash
   cd c:\xampp\htdocs
   git clone <your-repository-url> visa_portal
   ```

2. **Database Setup**:
   - Start Apache and MySQL from the XAMPP Control Panel.
   - Go to `http://localhost/phpmyadmin/` or access MySQL CLI.
   - Import the database schema from `/database/schema.sql`.
   ```bash
   mysql -u root -p -e "CREATE DATABASE visa_portal; USE visa_portal; SOURCE database/schema.sql;"
   ```

3. **Configure Database Settings**:
   - Open `/config/database.php` and verify the credentials.
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'visa_portal');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

4. **SMTP Mail Settings**:
   - Open `/config/mail.php` and update the SMTP credentials (e.g. your Gmail app passwords) to deliver real notifications to your customers.

---

## 🔑 Admin Credentials
- **URL:** `http://localhost/visa_portal/?page=admin`
- **Username:** `admin`
- **Password:** `Admin@1234`
