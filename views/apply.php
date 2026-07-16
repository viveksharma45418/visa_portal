<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Study Visa Application — Visa Vista Global</title>
  <meta name="description" content="Apply for your study visa with Visa Vista Global. Fill in your personal details, education information, and upload your documents securely.">

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- Custom -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ============================================================
     NAVBAR
     ============================================================ -->
<nav class="vv-navbar">
  <div class="container">
    <div class="navbar-inner">
      <a href="index.php" class="brand-wrap">
        <div class="brand-logo-circle">
          <i class="fas fa-globe-americas"></i>
        </div>
        <div class="brand-text">
          <div class="name">Visa Vista Global</div>
          <div class="tagline">Authorized Study Visa Consultancy</div>
        </div>
      </a>
      <div class="nav-actions">
        <button class="btn-portal">
          <i class="fas fa-user-circle me-1"></i> Client Portal
        </button>
        <a href="index.php?page=admin" class="btn-dashboard">
          <i class="fas fa-tachometer-alt me-1"></i> Team Dashboard
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- ============================================================
     HERO SECTION
     ============================================================ -->
<section class="hero-section">
  <!-- Dynamic background glow orbs -->
  <div class="orb-glow" style="width: 350px; height: 350px; background: var(--gold); top: -100px; right: -50px;"></div>
  <div class="orb-glow" style="width: 250px; height: 250px; background: #3B82F6; bottom: -50px; left: -50px; animation-delay: 2.5s;"></div>
  
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-7">
        <div class="hero-badge">
          <i class="fas fa-shield-alt"></i>
          Secure & Encrypted Portal
        </div>
        <h1 class="hero-title">
          Study Visa<br><span>Application</span>
        </h1>
        <p class="hero-desc">
          Fill in your details and upload your documents securely. Our expert consultants will review your application and contact you within <strong style="color:rgba(255,255,255,0.9)">24 hours</strong>.
        </p>
        <div class="hero-trust">
          <span class="trust-item"><i class="fas fa-lock"></i> SSL Secured</span>
          <span class="trust-item"><i class="fas fa-user-shield"></i> Data Protected</span>
          <span class="trust-item"><i class="fas fa-clock"></i> 24hr Response</span>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="stats-card">
          <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-university"></i></div>
            <div>
              <div class="stat-number">230+</div>
              <div class="stat-label">Partner Universities</div>
            </div>
          </div>
          <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-globe"></i></div>
            <div>
              <div class="stat-number">10+</div>
              <div class="stat-label">Destination Countries</div>
            </div>
          </div>
          <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
            <div>
              <div class="stat-number">5,000+</div>
              <div class="stat-label">Successful Applicants</div>
            </div>
          </div>
          <div class="stat-row">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div>
              <div class="stat-number">98%</div>
              <div class="stat-label">Visa Success Rate</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     PROGRESS STEPPER
     ============================================================ -->
<section class="stepper-section">
  <div class="container">
    <div class="stepper">

      <div class="step-item active" data-step="1">
        <div class="step-body">
          <div class="step-circle">1</div>
          <div class="step-label">Personal Information</div>
        </div>
      </div>

      <div class="step-connector"></div>

      <div class="step-item" data-step="2">
        <div class="step-body">
          <div class="step-circle">2</div>
          <div class="step-label">Education &amp; Destination</div>
        </div>
      </div>

      <div class="step-connector"></div>

      <div class="step-item" data-step="3">
        <div class="step-body">
          <div class="step-circle">3</div>
          <div class="step-label">Upload Documents</div>
        </div>
      </div>

      <div class="step-connector"></div>

      <div class="step-item" data-step="4">
        <div class="step-body">
          <div class="step-circle">4</div>
          <div class="step-label">Review &amp; Submit</div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ============================================================
     APPLICATION FORM
     ============================================================ -->
<section class="form-section">
  <div class="container">

    <!-- Application ID Banner (appears after first save) -->
    <div class="mb-3" style="display:flex; justify-content:flex-end;">
      <div class="application-id-banner" id="app-id-banner" style="display:none;">
        <i class="fas fa-id-card text-gold"></i>
        Reference: <strong id="app-id-text">—</strong>
      </div>
    </div>

    <form id="visaApplicationForm" enctype="multipart/form-data" novalidate>

      <!-- ========================================================
           STEP 1: PERSONAL INFORMATION
           ======================================================== -->
      <div class="step-content active" id="step-1">

        <div class="form-card mb-4">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-user"></i></div>
            <div>
              <h3 class="form-card-title">Personal Information</h3>
              <p class="form-card-subtitle">Enter your personal details exactly as on your passport</p>
            </div>
          </div>
          <div class="form-card-body">
            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label" for="full_name">Full Name (As on Passport) <span class="req">*</span></label>
                <input type="text" class="form-control" id="full_name" name="full_name"
                       placeholder="As it appears on your passport" autocomplete="name">
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="date_of_birth">Date of Birth <span class="req">*</span></label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                       max="<?= date('Y-m-d', strtotime('-5 years')) ?>">
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="email">Email Address <span class="req">*</span></label>
                <div class="input-group">
                  <span class="input-group-text" style="border:1.5px solid var(--border);background:#F8FAFC;border-right:none;">
                    <i class="fas fa-envelope text-muted" style="font-size:13px;"></i>
                  </span>
                  <input type="email" class="form-control" id="email" name="email"
                         placeholder="your@email.com" autocomplete="email"
                         style="border-left:none;">
                </div>
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="mobile">Mobile / WhatsApp <span class="req">*</span></label>
                <div class="input-group">
                  <span class="input-group-text" style="border:1.5px solid var(--border);background:#F8FAFC;border-right:none;">
                    <i class="fab fa-whatsapp text-muted" style="font-size:14px;color:#25D366 !important;"></i>
                  </span>
                  <input type="tel" class="form-control" id="mobile" name="mobile"
                         placeholder="+91 98765 43210" autocomplete="tel"
                         style="border-left:none;">
                </div>
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="city">City / District <span class="req">*</span></label>
                <input type="text" class="form-control" id="city" name="city"
                       placeholder="e.g. Mumbai, Delhi, Ahmedabad">
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="marital_status">Marital Status <span class="req">*</span></label>
                <select class="form-select" id="marital_status" name="marital_status">
                  <option value="">— Select Status —</option>
                  <option value="Single">Single</option>
                  <option value="Married">Married</option>
                  <option value="Divorced">Divorced</option>
                  <option value="Widowed">Widowed</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="passport_number">Passport Number <span class="req">*</span></label>
                <input type="text" class="form-control" id="passport_number" name="passport_number"
                       placeholder="e.g. A1234567" style="text-transform:uppercase;"
                       oninput="this.value=this.value.toUpperCase()">
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="passport_expiry">Passport Expiry Date <span class="req">*</span></label>
                <input type="date" class="form-control" id="passport_expiry" name="passport_expiry"
                       min="<?= date('Y-m-d', strtotime('+18 months')) ?>">
                <div class="invalid-feedback"></div>
                <div class="form-text text-muted" style="font-size:11px;">
                  <i class="fas fa-info-circle me-1"></i> Passport must be valid for at least 18 months
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Step 1 Navigation -->
        <div class="step-nav-bar">
          <div></div>
          <button type="button" class="btn-next-step" onclick="nextStep()">
            Education &amp; Destination <i class="fas fa-arrow-right"></i>
          </button>
        </div>

      </div><!-- /step-1 -->


      <!-- ========================================================
           STEP 2: EDUCATION & DESTINATION
           ======================================================== -->
      <div class="step-content" id="step-2">

        <div class="form-card mb-4">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-graduation-cap"></i></div>
            <div>
              <h3 class="form-card-title">Education &amp; Destination</h3>
              <p class="form-card-subtitle">Tell us about your academic background and study plans</p>
            </div>
          </div>
          <div class="form-card-body">
            <div class="row g-3">

              <div class="col-md-6">
                <label class="form-label" for="destination_country">Destination Country <span class="req">*</span></label>
                <select class="form-select" id="destination_country" name="destination_country">
                  <option value="">— Select Country —</option>
                  <option value="Canada">🇨🇦 Canada</option>
                  <option value="Australia">🇦🇺 Australia</option>
                  <option value="United Kingdom">🇬🇧 United Kingdom</option>
                  <option value="United States">🇺🇸 United States</option>
                  <option value="New Zealand">🇳🇿 New Zealand</option>
                  <option value="Germany">🇩🇪 Germany</option>
                  <option value="Ireland">🇮🇪 Ireland</option>
                  <option value="France">🇫🇷 France</option>
                  <option value="Singapore">🇸🇬 Singapore</option>
                  <option value="Other">🌍 Other</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="preferred_intake">Preferred Intake <span class="req">*</span></label>
                <select class="form-select" id="preferred_intake" name="preferred_intake">
                  <option value="">— Select Intake —</option>
                  <option value="January">January Intake</option>
                  <option value="May">May Intake</option>
                  <option value="September">September Intake</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="course_program">Course / Program <span class="req">*</span></label>
                <input type="text" class="form-control" id="course_program" name="course_program"
                       placeholder="e.g. Masters in Computer Science">
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="preferred_university">Preferred University <span style="color:var(--text-muted);font-weight:400;">(Optional)</span></label>
                <input type="text" class="form-control" id="preferred_university" name="preferred_university"
                       placeholder="e.g. University of Toronto">
              </div>

              <div class="col-md-6">
                <label class="form-label" for="highest_qualification">Highest Qualification <span class="req">*</span></label>
                <select class="form-select" id="highest_qualification" name="highest_qualification">
                  <option value="">— Select Qualification —</option>
                  <option value="10th">10th Standard</option>
                  <option value="12th">12th Standard</option>
                  <option value="Diploma">Diploma</option>
                  <option value="Bachelor's Degree">Bachelor's Degree</option>
                  <option value="Master's Degree">Master's Degree</option>
                  <option value="Other">Other</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="ielts_score">IELTS / PTE Score <span style="color:var(--text-muted);font-weight:400;">(Optional)</span></label>
                <input type="text" class="form-control" id="ielts_score" name="ielts_score"
                       placeholder="e.g. IELTS 7.0 / PTE 65">
              </div>

              <div class="col-md-6">
                <label class="form-label" for="annual_budget">Annual Budget (Approx.) <span class="req">*</span></label>
                <select class="form-select" id="annual_budget" name="annual_budget">
                  <option value="">— Select Budget Range —</option>
                  <option value="Under ₹10 Lakh">Under ₹10 Lakh</option>
                  <option value="Under ₹20 Lakh">Under ₹20 Lakh</option>
                  <option value="₹20–40 Lakh">₹20–40 Lakh</option>
                  <option value="Above ₹40 Lakh">Above ₹40 Lakh</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="visa_refusals">Previous Visa Refusals <span class="req">*</span></label>
                <select class="form-select" id="visa_refusals" name="visa_refusals">
                  <option value="">— Select —</option>
                  <option value="No">No</option>
                  <option value="Yes">Yes</option>
                </select>
                <div class="invalid-feedback"></div>
              </div>

              <!-- Refusal details (conditional) -->
              <div class="col-12" id="refusal_block" style="display:none;">
                <label class="form-label" for="refusal_details">
                  <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                  Please explain previous refusal details <span class="req">*</span>
                </label>
                <textarea class="form-control" id="refusal_details" name="refusal_details"
                          rows="3" placeholder="Country, year, reason for refusal..."></textarea>
                <div class="invalid-feedback"></div>
              </div>

              <div class="col-12">
                <label class="form-label" for="additional_notes">Additional Notes / Special Requirements</label>
                <textarea class="form-control" id="additional_notes" name="additional_notes"
                          rows="3" placeholder="Any specific requirements, health conditions, or special circumstances..."></textarea>
              </div>

            </div>
          </div>
        </div>

        <!-- Step 2 Navigation -->
        <div class="step-nav-bar">
          <button type="button" class="btn-prev-step" onclick="prevStep()">
            <i class="fas fa-arrow-left"></i> Personal Info
          </button>
          <button type="button" class="btn-next-step" onclick="nextStep()">
            Upload Documents <i class="fas fa-arrow-right"></i>
          </button>
        </div>

      </div><!-- /step-2 -->


      <!-- ========================================================
           STEP 3: DOCUMENT UPLOAD
           ======================================================== -->
      <div class="step-content" id="step-3">

        <div class="form-card mb-4">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-file-upload"></i></div>
            <div>
              <h3 class="form-card-title">Document Upload</h3>
              <p class="form-card-subtitle">PDF, JPG, PNG — Max 10MB each. Required documents marked in red.</p>
            </div>
          </div>
          <div class="form-card-body">

            <div class="section-tip mb-4">
              <i class="fas fa-info-circle me-2"></i>
              All files are encrypted and stored securely. Ensure documents are clear, readable, and valid.
            </div>

            <div class="upload-grid">

              <!-- 1. Passport -->
              <div class="upload-card" data-upload-card="passport_file">
                <div class="upload-doc-icon"><i class="fas fa-passport"></i></div>
                <div class="upload-info">
                  <div class="upload-doc-title">
                    Passport (All Pages)
                    <span class="badge-required">Required</span>
                  </div>
                  <p class="upload-doc-desc">Clear scan of all pages including blank ones. Must be valid for at least 18 months.</p>
                  <div class="upload-status-badge not-uploaded" id="status-passport_file">
                    <i class="fas fa-exclamation-triangle"></i> Not uploaded
                  </div>
                  <div class="file-preview-info" id="preview-passport_file" style="display:none;">
                    <i class="fas fa-file-pdf"></i>
                    <span class="file-preview-name"></span>
                    <span class="file-preview-size"></span>
                  </div>
                  <div class="upload-btn-wrap">
                    <label class="btn-choose-file" for="input-passport_file">
                      <i class="fas fa-cloud-upload-alt"></i> Choose File
                    </label>
                    <input type="file" id="input-passport_file" name="passport_file"
                           accept=".pdf,.jpg,.jpeg,.png" class="file-input-hidden d-none"
                           data-field="passport_file">
                    <button type="button" class="btn-remove-file" id="remove-passport_file"
                            onclick="removeFile('passport_file')">
                      <i class="fas fa-times"></i> Remove
                    </button>
                  </div>
                </div>
              </div>

              <!-- 2. Passport Photo -->
              <div class="upload-card" data-upload-card="passport_photo">
                <div class="upload-doc-icon"><i class="fas fa-camera"></i></div>
                <div class="upload-info">
                  <div class="upload-doc-title">
                    Passport Size Photo
                    <span class="badge-required">Required</span>
                  </div>
                  <p class="upload-doc-desc">Recent white background photo. JPEG format preferred, 35×45mm dimensions.</p>
                  <div class="upload-status-badge not-uploaded" id="status-passport_photo">
                    <i class="fas fa-exclamation-triangle"></i> Not uploaded
                  </div>
                  <div class="file-preview-info" id="preview-passport_photo" style="display:none;">
                    <i class="fas fa-file-image"></i>
                    <span class="file-preview-name"></span>
                    <span class="file-preview-size"></span>
                  </div>
                  <div class="upload-btn-wrap">
                    <label class="btn-choose-file" for="input-passport_photo">
                      <i class="fas fa-cloud-upload-alt"></i> Choose File
                    </label>
                    <input type="file" id="input-passport_photo" name="passport_photo"
                           accept=".pdf,.jpg,.jpeg,.png" class="file-input-hidden d-none"
                           data-field="passport_photo">
                    <button type="button" class="btn-remove-file" id="remove-passport_photo"
                            onclick="removeFile('passport_photo')">
                      <i class="fas fa-times"></i> Remove
                    </button>
                  </div>
                </div>
              </div>

              <!-- 3. 10th Marksheet -->
              <div class="upload-card" data-upload-card="tenth_marksheet">
                <div class="upload-doc-icon"><i class="fas fa-file-alt"></i></div>
                <div class="upload-info">
                  <div class="upload-doc-title">
                    10th Marksheet &amp; Certificate
                    <span class="badge-required">Required</span>
                  </div>
                  <p class="upload-doc-desc">Both marksheet and passing certificate, clearly scanned.</p>
                  <div class="upload-status-badge not-uploaded" id="status-tenth_marksheet">
                    <i class="fas fa-exclamation-triangle"></i> Not uploaded
                  </div>
                  <div class="file-preview-info" id="preview-tenth_marksheet" style="display:none;">
                    <i class="fas fa-file-pdf"></i>
                    <span class="file-preview-name"></span>
                    <span class="file-preview-size"></span>
                  </div>
                  <div class="upload-btn-wrap">
                    <label class="btn-choose-file" for="input-tenth_marksheet">
                      <i class="fas fa-cloud-upload-alt"></i> Choose File
                    </label>
                    <input type="file" id="input-tenth_marksheet" name="tenth_marksheet"
                           accept=".pdf,.jpg,.jpeg,.png" class="file-input-hidden d-none"
                           data-field="tenth_marksheet">
                    <button type="button" class="btn-remove-file" id="remove-tenth_marksheet"
                            onclick="removeFile('tenth_marksheet')">
                      <i class="fas fa-times"></i> Remove
                    </button>
                  </div>
                </div>
              </div>

              <!-- 4. 12th Marksheet -->
              <div class="upload-card" data-upload-card="twelfth_marksheet">
                <div class="upload-doc-icon"><i class="fas fa-file-alt"></i></div>
                <div class="upload-info">
                  <div class="upload-doc-title">
                    12th Marksheet &amp; Certificate
                    <span class="badge-required">Required</span>
                  </div>
                  <p class="upload-doc-desc">Both marksheet and passing certificate, clearly scanned.</p>
                  <div class="upload-status-badge not-uploaded" id="status-twelfth_marksheet">
                    <i class="fas fa-exclamation-triangle"></i> Not uploaded
                  </div>
                  <div class="file-preview-info" id="preview-twelfth_marksheet" style="display:none;">
                    <i class="fas fa-file-pdf"></i>
                    <span class="file-preview-name"></span>
                    <span class="file-preview-size"></span>
                  </div>
                  <div class="upload-btn-wrap">
                    <label class="btn-choose-file" for="input-twelfth_marksheet">
                      <i class="fas fa-cloud-upload-alt"></i> Choose File
                    </label>
                    <input type="file" id="input-twelfth_marksheet" name="twelfth_marksheet"
                           accept=".pdf,.jpg,.jpeg,.png" class="file-input-hidden d-none"
                           data-field="twelfth_marksheet">
                    <button type="button" class="btn-remove-file" id="remove-twelfth_marksheet"
                            onclick="removeFile('twelfth_marksheet')">
                      <i class="fas fa-times"></i> Remove
                    </button>
                  </div>
                </div>
              </div>

              <!-- 5. Bachelor's Degree (Optional) -->
              <div class="upload-card" data-upload-card="bachelor_degree">
                <div class="upload-doc-icon"><i class="fas fa-scroll"></i></div>
                <div class="upload-info">
                  <div class="upload-doc-title">
                    Bachelor's Degree / Diploma
                    <span class="badge-optional">Optional</span>
                  </div>
                  <p class="upload-doc-desc">Degree certificate and all semester marksheets if applying for PG programs.</p>
                  <div class="upload-status-badge not-uploaded" id="status-bachelor_degree">
                    <i class="fas fa-exclamation-triangle"></i> Not uploaded
                  </div>
                  <div class="file-preview-info" id="preview-bachelor_degree" style="display:none;">
                    <i class="fas fa-file-pdf"></i>
                    <span class="file-preview-name"></span>
                    <span class="file-preview-size"></span>
                  </div>
                  <div class="upload-btn-wrap">
                    <label class="btn-choose-file" for="input-bachelor_degree">
                      <i class="fas fa-cloud-upload-alt"></i> Choose File
                    </label>
                    <input type="file" id="input-bachelor_degree" name="bachelor_degree"
                           accept=".pdf,.jpg,.jpeg,.png" class="file-input-hidden d-none"
                           data-field="bachelor_degree">
                    <button type="button" class="btn-remove-file" id="remove-bachelor_degree"
                            onclick="removeFile('bachelor_degree')">
                      <i class="fas fa-times"></i> Remove
                    </button>
                  </div>
                </div>
              </div>

              <!-- 6. IELTS Scorecard (Optional) -->
              <div class="upload-card" data-upload-card="ielts_scorecard">
                <div class="upload-doc-icon"><i class="fas fa-language"></i></div>
                <div class="upload-info">
                  <div class="upload-doc-title">
                    IELTS / PTE / TOEFL Scorecard
                    <span class="badge-optional">Optional</span>
                  </div>
                  <p class="upload-doc-desc">Official scorecard from the testing authority. Skip if not yet appeared.</p>
                  <div class="upload-status-badge not-uploaded" id="status-ielts_scorecard">
                    <i class="fas fa-exclamation-triangle"></i> Not uploaded
                  </div>
                  <div class="file-preview-info" id="preview-ielts_scorecard" style="display:none;">
                    <i class="fas fa-file-pdf"></i>
                    <span class="file-preview-name"></span>
                    <span class="file-preview-size"></span>
                  </div>
                  <div class="upload-btn-wrap">
                    <label class="btn-choose-file" for="input-ielts_scorecard">
                      <i class="fas fa-cloud-upload-alt"></i> Choose File
                    </label>
                    <input type="file" id="input-ielts_scorecard" name="ielts_scorecard"
                           accept=".pdf,.jpg,.jpeg,.png" class="file-input-hidden d-none"
                           data-field="ielts_scorecard">
                    <button type="button" class="btn-remove-file" id="remove-ielts_scorecard"
                            onclick="removeFile('ielts_scorecard')">
                      <i class="fas fa-times"></i> Remove
                    </button>
                  </div>
                </div>
              </div>

              <!-- 7. Bank Statement (Required) -->
              <div class="upload-card" data-upload-card="bank_statement">
                <div class="upload-doc-icon"><i class="fas fa-university"></i></div>
                <div class="upload-info">
                  <div class="upload-doc-title">
                    Bank Statements (6 Months)
                    <span class="badge-required">Required</span>
                  </div>
                  <p class="upload-doc-desc">Last 6 months bank statement showing sufficient funds. Bank-stamped preferred.</p>
                  <div class="upload-status-badge not-uploaded" id="status-bank_statement">
                    <i class="fas fa-exclamation-triangle"></i> Not uploaded
                  </div>
                  <div class="file-preview-info" id="preview-bank_statement" style="display:none;">
                    <i class="fas fa-file-pdf"></i>
                    <span class="file-preview-name"></span>
                    <span class="file-preview-size"></span>
                  </div>
                  <div class="upload-btn-wrap">
                    <label class="btn-choose-file" for="input-bank_statement">
                      <i class="fas fa-cloud-upload-alt"></i> Choose File
                    </label>
                    <input type="file" id="input-bank_statement" name="bank_statement"
                           accept=".pdf,.jpg,.jpeg,.png" class="file-input-hidden d-none"
                           data-field="bank_statement">
                    <button type="button" class="btn-remove-file" id="remove-bank_statement"
                            onclick="removeFile('bank_statement')">
                      <i class="fas fa-times"></i> Remove
                    </button>
                  </div>
                </div>
              </div>

              <!-- 8. SOP (Optional) -->
              <div class="upload-card" data-upload-card="sop_file">
                <div class="upload-doc-icon"><i class="fas fa-pen-fancy"></i></div>
                <div class="upload-info">
                  <div class="upload-doc-title">
                    Statement of Purpose (SOP)
                    <span class="badge-optional">Optional</span>
                  </div>
                  <p class="upload-doc-desc">Your written SOP for the chosen program. We can also help you draft this.</p>
                  <div class="upload-status-badge not-uploaded" id="status-sop_file">
                    <i class="fas fa-exclamation-triangle"></i> Not uploaded
                  </div>
                  <div class="file-preview-info" id="preview-sop_file" style="display:none;">
                    <i class="fas fa-file-pdf"></i>
                    <span class="file-preview-name"></span>
                    <span class="file-preview-size"></span>
                  </div>
                  <div class="upload-btn-wrap">
                    <label class="btn-choose-file" for="input-sop_file">
                      <i class="fas fa-cloud-upload-alt"></i> Choose File
                    </label>
                    <input type="file" id="input-sop_file" name="sop_file"
                           accept=".pdf,.jpg,.jpeg,.png" class="file-input-hidden d-none"
                           data-field="sop_file">
                    <button type="button" class="btn-remove-file" id="remove-sop_file"
                            onclick="removeFile('sop_file')">
                      <i class="fas fa-times"></i> Remove
                    </button>
                  </div>
                </div>
              </div>

            </div><!-- /upload-grid -->
          </div>
        </div>

        <!-- Step 3 Navigation -->
        <div class="step-nav-bar">
          <button type="button" class="btn-prev-step" onclick="prevStep()">
            <i class="fas fa-arrow-left"></i> Education
          </button>
          <button type="button" class="btn-next-step" onclick="nextStep()">
            Review &amp; Submit <i class="fas fa-arrow-right"></i>
          </button>
        </div>

      </div><!-- /step-3 -->


      <!-- ========================================================
           STEP 4: REVIEW & SUBMIT
           ======================================================== -->
      <div class="step-content" id="step-4">

        <div class="section-tip mb-4">
          <i class="fas fa-eye me-2"></i>
          Review your information carefully before submitting. You can go back to edit any section.
        </div>

        <!-- Personal Info Review -->
        <div class="review-block mb-3">
          <div class="review-block-header">
            <i class="fas fa-user"></i>
            <h6>Personal Information</h6>
          </div>
          <div class="review-grid">
            <div class="review-item">
              <div class="review-label">Full Name</div>
              <div class="review-value" id="rv-full_name">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Date of Birth</div>
              <div class="review-value" id="rv-date_of_birth">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Email Address</div>
              <div class="review-value" id="rv-email">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Mobile / WhatsApp</div>
              <div class="review-value" id="rv-mobile">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">City / District</div>
              <div class="review-value" id="rv-city">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Marital Status</div>
              <div class="review-value" id="rv-marital_status">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Passport Number</div>
              <div class="review-value" id="rv-passport_number">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Passport Expiry</div>
              <div class="review-value" id="rv-passport_expiry">—</div>
            </div>
          </div>
        </div>

        <!-- Education Review -->
        <div class="review-block mb-3">
          <div class="review-block-header">
            <i class="fas fa-graduation-cap"></i>
            <h6>Education &amp; Destination</h6>
          </div>
          <div class="review-grid">
            <div class="review-item">
              <div class="review-label">Destination Country</div>
              <div class="review-value" id="rv-destination_country">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Preferred Intake</div>
              <div class="review-value" id="rv-preferred_intake">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Course / Program</div>
              <div class="review-value" id="rv-course_program">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Preferred University</div>
              <div class="review-value" id="rv-preferred_university">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Highest Qualification</div>
              <div class="review-value" id="rv-highest_qualification">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">IELTS / PTE Score</div>
              <div class="review-value" id="rv-ielts_score">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Annual Budget</div>
              <div class="review-value" id="rv-annual_budget">—</div>
            </div>
            <div class="review-item">
              <div class="review-label">Previous Visa Refusals</div>
              <div class="review-value" id="rv-visa_refusals">—</div>
            </div>
          </div>
        </div>

        <!-- Document Status Review -->
        <div class="review-block mb-4">
          <div class="review-block-header">
            <i class="fas fa-file-check"></i>
            <h6>Document Status</h6>
          </div>
          <div class="doc-status-row">
            <div class="doc-status-name">Passport (All Pages) <span class="doc-req-badge">Required</span></div>
            <span id="ds-passport_file" class="badge-doc-missing"><i class="fas fa-times-circle me-1"></i> Missing</span>
          </div>
          <div class="doc-status-row">
            <div class="doc-status-name">Passport Size Photo <span class="doc-req-badge">Required</span></div>
            <span id="ds-passport_photo" class="badge-doc-missing"><i class="fas fa-times-circle me-1"></i> Missing</span>
          </div>
          <div class="doc-status-row">
            <div class="doc-status-name">10th Marksheet &amp; Certificate <span class="doc-req-badge">Required</span></div>
            <span id="ds-tenth_marksheet" class="badge-doc-missing"><i class="fas fa-times-circle me-1"></i> Missing</span>
          </div>
          <div class="doc-status-row">
            <div class="doc-status-name">12th Marksheet &amp; Certificate <span class="doc-req-badge">Required</span></div>
            <span id="ds-twelfth_marksheet" class="badge-doc-missing"><i class="fas fa-times-circle me-1"></i> Missing</span>
          </div>
          <div class="doc-status-row">
            <div class="doc-status-name">Bachelor's Degree / Diploma</div>
            <span id="ds-bachelor_degree" class="badge-doc-missing"><i class="fas fa-times-circle me-1"></i> Missing</span>
          </div>
          <div class="doc-status-row">
            <div class="doc-status-name">IELTS / PTE / TOEFL Scorecard</div>
            <span id="ds-ielts_scorecard" class="badge-doc-missing"><i class="fas fa-times-circle me-1"></i> Missing</span>
          </div>
          <div class="doc-status-row">
            <div class="doc-status-name">Bank Statements (6 Months) <span class="doc-req-badge">Required</span></div>
            <span id="ds-bank_statement" class="badge-doc-missing"><i class="fas fa-times-circle me-1"></i> Missing</span>
          </div>
          <div class="doc-status-row">
            <div class="doc-status-name">Statement of Purpose (SOP)</div>
            <span id="ds-sop_file" class="badge-doc-missing"><i class="fas fa-times-circle me-1"></i> Missing</span>
          </div>
        </div>

        <!-- Confirmation Checkbox -->
        <div class="confirm-wrap mb-4">
          <input type="checkbox" id="confirm_accuracy" name="confirm_accuracy">
          <label for="confirm_accuracy">
            <strong>I confirm</strong> that all information provided in this application is accurate, complete, and truthful. I understand that any false or misleading information may result in the rejection of my visa application.
          </label>
        </div>

        <!-- Step 4 Navigation -->
        <div class="step-nav-bar">
          <button type="button" class="btn-prev-step" onclick="prevStep()">
            <i class="fas fa-arrow-left"></i> Documents
          </button>
          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button type="button" class="btn-save-draft" id="btn-save-draft">
              <i class="fas fa-save"></i> Save Draft
            </button>
            <button type="button" class="btn-submit-app" id="btn-submit-app">
              <i class="fas fa-paper-plane"></i> Submit Application
            </button>
          </div>
        </div>

      </div><!-- /step-4 -->

    </form>
  </div>
</section>

<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer class="vv-footer">
  <div class="container">
    <p>
      &copy; <?= date('Y') ?> <strong style="color:rgba(255,255,255,0.7)">Visa Vista Global</strong>.
      All rights reserved. &nbsp;|&nbsp;
      <a href="#">Privacy Policy</a> &nbsp;|&nbsp;
      <a href="#">Terms of Service</a>
    </p>
    <p style="margin-top:4px;font-size:12px;">
      Authorized Visa Consultancy &bull; AIRC Registered &bull; ISO 9001:2015 Certified
    </p>
  </div>
</footer>

<!-- ── Scripts ─────────────────────────────────────────────────── -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="assets/js/app.js"></script>

</body>
</html>
