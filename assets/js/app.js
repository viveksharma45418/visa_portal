/**
 * Visa Vista Global — Application Portal JavaScript
 * Handles: step wizard, validation, file uploads, AJAX save/submit
 */

'use strict';

// ── State ──────────────────────────────────────────────────────
const VisaApp = {
  currentStep: 1,
  totalSteps:  4,
  appId:       null,         // DB numeric id after first save
  applicationId: null,       // VV-2026-XXXX string
  uploadedFiles: {},         // { fieldName: {name, size, path} }

  // Required document fields
  requiredDocs: ['passport_file','passport_photo','tenth_marksheet','twelfth_marksheet','bank_statement'],
  // All document configs
  docConfigs: {
    passport_file:     { label: 'Passport (All Pages)',           required: true  },
    passport_photo:    { label: 'Passport Size Photo',            required: true  },
    tenth_marksheet:   { label: '10th Marksheet & Certificate',   required: true  },
    twelfth_marksheet: { label: '12th Marksheet & Certificate',   required: true  },
    bachelor_degree:   { label: "Bachelor's Degree / Diploma",    required: false },
    ielts_scorecard:   { label: 'IELTS / PTE / TOEFL Scorecard',  required: false },
    bank_statement:    { label: 'Bank Statements (6 Months)',      required: true  },
    sop_file:          { label: 'Statement of Purpose (SOP)',      required: false },
  },
};

// ── DOM Ready ──────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initStepper();
  initFileUploads();
  initVisaRefusalToggle();
  initFormSubmit();

  // Restore application_id from sessionStorage if available
  const savedAppId = sessionStorage.getItem('vv_app_id');
  const savedId    = sessionStorage.getItem('vv_id');
  if (savedAppId) {
    VisaApp.applicationId = savedAppId;
    VisaApp.appId         = savedId;
    showAppIdBanner(savedAppId);
  }
});

// ================================================================
// STEPPER NAVIGATION
// ================================================================
function initStepper() {
  document.querySelectorAll('.btn-next-step').forEach(btn => {
    btn.addEventListener('click', () => nextStep());
  });
  document.querySelectorAll('.btn-prev-step').forEach(btn => {
    btn.addEventListener('click', () => prevStep());
  });
}

function nextStep() {
  if (!validateCurrentStep()) return;
  if (VisaApp.currentStep < VisaApp.totalSteps) {
    VisaApp.currentStep++;
    updateStepperUI();
    if (VisaApp.currentStep === 4) populateReview();
    scrollToFormTop();
  }
}

function prevStep() {
  if (VisaApp.currentStep > 1) {
    VisaApp.currentStep--;
    updateStepperUI();
    scrollToFormTop();
  }
}

function updateStepperUI() {
  const step = VisaApp.currentStep;

  // Step items
  document.querySelectorAll('.step-item').forEach((el, idx) => {
    const n = idx + 1;
    el.classList.remove('active','completed');
    if (n === step)       el.classList.add('active');
    else if (n < step)    el.classList.add('completed');

    // Icon for completed
    const circle = el.querySelector('.step-circle');
    if (n < step) {
      circle.innerHTML = '<i class="fas fa-check"></i>';
    } else {
      circle.textContent = n;
    }
  });

  // Connectors
  document.querySelectorAll('.step-connector').forEach((el, idx) => {
    const n = idx + 1;
    el.classList.toggle('completed', n < step);
  });

  // Step content panels
  document.querySelectorAll('.step-content').forEach((el, idx) => {
    const n = idx + 1;
    el.classList.toggle('active', n === step);
  });
}

function scrollToFormTop() {
  const formSection = document.querySelector('.form-section');
  if (formSection) {
    formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}

// ================================================================
// VALIDATION
// ================================================================
function validateCurrentStep() {
  clearAllErrors();
  switch (VisaApp.currentStep) {
    case 1: return validateStep1();
    case 2: return validateStep2();
    case 3: return validateStep3();
    default: return true;
  }
}

function validateStep1() {
  let valid = true;

  valid = requireField('full_name',       'Full name is required.')       && valid;
  valid = requireField('date_of_birth',   'Date of birth is required.')   && valid;
  valid = requireEmail('email')                                             && valid;
  valid = requirePhone('mobile')                                            && valid;
  valid = requireField('city',            'City / District is required.')  && valid;
  valid = requireField('passport_number', 'Passport number is required.')  && valid;
  valid = requireField('passport_expiry', 'Passport expiry date is required.') && valid;
  valid = requireSelect('marital_status', 'Please select marital status.') && valid;

  // Date of birth: must not be in future
  const dob = document.getElementById('date_of_birth')?.value;
  if (dob && new Date(dob) >= new Date()) {
    showError('date_of_birth', 'Date of birth cannot be today or future.');
    valid = false;
  }

  // Passport expiry: must be in future
  const expiry = document.getElementById('passport_expiry')?.value;
  if (expiry) {
    const expiryDate = new Date(expiry);
    const minDate    = new Date();
    minDate.setMonth(minDate.getMonth() + 18);
    if (expiryDate < minDate) {
      showError('passport_expiry', 'Passport must be valid for at least 18 months.');
      valid = false;
    }
  }

  return valid;
}

function validateStep2() {
  let valid = true;
  valid = requireSelect('destination_country',   'Please select destination country.') && valid;
  valid = requireSelect('preferred_intake',       'Please select preferred intake.')    && valid;
  valid = requireField('course_program',          'Course / Program is required.')      && valid;
  valid = requireSelect('highest_qualification',  'Please select highest qualification.') && valid;
  valid = requireSelect('annual_budget',          'Please select annual budget.')        && valid;
  valid = requireSelect('visa_refusals',          'Please answer visa refusal question.') && valid;

  const refusals = document.getElementById('visa_refusals')?.value;
  if (refusals === 'Yes') {
    valid = requireField('refusal_details', 'Please provide refusal details.') && valid;
  }

  return valid;
}

function validateStep3() {
  // Check required documents
  let allRequired = true;
  VisaApp.requiredDocs.forEach(field => {
    if (!VisaApp.uploadedFiles[field]) {
      const card = document.querySelector(`[data-upload-card="${field}"]`);
      if (card) {
        card.classList.add('error-state');
        const statusEl = card.querySelector('.upload-status-badge');
        if (statusEl) {
          statusEl.classList.add('not-uploaded');
          statusEl.innerHTML = '<i class="fas fa-exclamation-circle"></i> Required — Please upload';
        }
      }
      allRequired = false;
    }
  });

  if (!allRequired) {
    Swal.fire({
      icon: 'warning',
      title: 'Documents Required',
      text: 'Please upload all required documents before proceeding.',
      confirmButtonColor: '#0D1B3E',
    });
  }
  return allRequired;
}

// Field validators
function requireField(id, msg) {
  const el = document.getElementById(id);
  if (!el) return true;
  if (!el.value.trim()) { showError(id, msg); return false; }
  el.classList.add('is-valid');
  return true;
}

function requireSelect(id, msg) {
  const el = document.getElementById(id);
  if (!el) return true;
  if (!el.value || el.value === '') { showError(id, msg); return false; }
  el.classList.add('is-valid');
  return true;
}

function requireEmail(id) {
  const el = document.getElementById(id);
  if (!el) return true;
  const val = el.value.trim();
  if (!val) { showError(id, 'Email address is required.'); return false; }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
    showError(id, 'Please enter a valid email address.'); return false;
  }
  el.classList.add('is-valid');
  return true;
}

function requirePhone(id) {
  const el = document.getElementById(id);
  if (!el) return true;
  const val = el.value.trim().replace(/[\s\-\(\)]/g, '');
  if (!val) { showError(id, 'Mobile / WhatsApp number is required.'); return false; }
  if (!/^\+?[\d]{7,15}$/.test(val)) {
    showError(id, 'Please enter a valid phone number.'); return false;
  }
  el.classList.add('is-valid');
  return true;
}

function showError(id, msg) {
  const el = document.getElementById(id);
  if (!el) return;
  el.classList.remove('is-valid');
  el.classList.add('is-invalid');
  let fb = el.parentElement.querySelector('.invalid-feedback');
  if (!fb) {
    fb = document.createElement('div');
    fb.className = 'invalid-feedback';
    el.parentElement.appendChild(fb);
  }
  fb.textContent = msg;
  fb.style.display = 'block';
}

function clearAllErrors() {
  document.querySelectorAll('.is-invalid').forEach(el => {
    el.classList.remove('is-invalid');
  });
  document.querySelectorAll('.invalid-feedback').forEach(el => {
    el.style.display = 'none';
  });
  document.querySelectorAll('.upload-card.error-state').forEach(el => {
    el.classList.remove('error-state');
  });
}

// ================================================================
// FILE UPLOADS
// ================================================================
function initFileUploads() {
  document.querySelectorAll('.file-input-hidden').forEach(input => {
    input.addEventListener('change', function() {
      handleFileSelect(this);
    });
  });
}

function handleFileSelect(input) {
  const field = input.dataset.field;
  const file  = input.files[0];
  if (!file) return;

  const card        = document.querySelector(`[data-upload-card="${field}"]`);
  const statusBadge = document.getElementById(`status-${field}`);
  const previewEl   = document.getElementById(`preview-${field}`);
  const removeBtn   = document.getElementById(`remove-${field}`);

  // Validate size (10 MB)
  if (file.size > 10 * 1024 * 1024) {
    Swal.fire({ icon: 'error', title: 'File Too Large', text: 'Maximum file size is 10MB.', confirmButtonColor: '#0D1B3E' });
    input.value = '';
    return;
  }

  // Validate extension
  const ext = file.name.split('.').pop().toLowerCase();
  if (!['pdf','jpg','jpeg','png'].includes(ext)) {
    Swal.fire({ icon: 'error', title: 'Invalid File Type', text: 'Only PDF, JPG, and PNG files are allowed.', confirmButtonColor: '#0D1B3E' });
    input.value = '';
    return;
  }

  // Store in state
  VisaApp.uploadedFiles[field] = { name: file.name, size: file.size, file: file };

  // Update UI
  if (card)        card.classList.add('uploaded');
  if (card)        card.classList.remove('error-state');

  if (statusBadge) {
    statusBadge.className = 'upload-status-badge is-uploaded';
    statusBadge.innerHTML = '<i class="fas fa-check-circle"></i> Uploaded';
  }

  if (previewEl) {
    previewEl.style.display = 'flex';
    previewEl.querySelector('.file-preview-name').textContent = file.name;
    previewEl.querySelector('.file-preview-size').textContent = formatBytes(file.size);

    // Set icon based on type
    const iconEl = previewEl.querySelector('i');
    if (iconEl) {
      if (ext === 'pdf')              iconEl.className = 'fas fa-file-pdf';
      else if (['jpg','jpeg','png'].includes(ext)) iconEl.className = 'fas fa-file-image';
    }
  }

  if (removeBtn) removeBtn.classList.add('visible');

  // Update review doc status if on step 4
  if (VisaApp.currentStep === 4) updateDocStatus(field, true);
}

function removeFile(field) {
  const input       = document.getElementById(`input-${field}`);
  const card        = document.querySelector(`[data-upload-card="${field}"]`);
  const statusBadge = document.getElementById(`status-${field}`);
  const previewEl   = document.getElementById(`preview-${field}`);
  const removeBtn   = document.getElementById(`remove-${field}`);

  if (input) { input.value = ''; }
  delete VisaApp.uploadedFiles[field];

  if (card)        { card.classList.remove('uploaded', 'error-state'); }
  if (statusBadge) {
    const isRequired = VisaApp.requiredDocs.includes(field);
    statusBadge.className = 'upload-status-badge not-uploaded';
    statusBadge.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Not uploaded`;
  }
  if (previewEl)   { previewEl.style.display = 'none'; }
  if (removeBtn)   { removeBtn.classList.remove('visible'); }

  if (VisaApp.currentStep === 4) updateDocStatus(field, false);
}

function formatBytes(bytes) {
  if (bytes < 1024)       return bytes + ' B';
  if (bytes < 1048576)    return (bytes / 1024).toFixed(1) + ' KB';
  return (bytes / 1048576).toFixed(1) + ' MB';
}

// ================================================================
// VISA REFUSAL TOGGLE
// ================================================================
function initVisaRefusalToggle() {
  const sel   = document.getElementById('visa_refusals');
  const block = document.getElementById('refusal_block');
  if (!sel || !block) return;

  sel.addEventListener('change', () => {
    const show = sel.value === 'Yes';
    block.style.display = show ? 'block' : 'none';
    if (!show) {
      const ta = document.getElementById('refusal_details');
      if (ta) ta.value = '';
    }
  });
}

// ================================================================
// REVIEW POPULATION (Step 4)
// ================================================================
function populateReview() {
  // Personal Info
  setReviewVal('rv-full_name',        'full_name');
  setReviewVal('rv-date_of_birth',    'date_of_birth');
  setReviewVal('rv-email',            'email');
  setReviewVal('rv-mobile',           'mobile');
  setReviewVal('rv-city',             'city');
  setReviewVal('rv-passport_number',  'passport_number');
  setReviewVal('rv-passport_expiry',  'passport_expiry');
  setReviewValSelect('rv-marital_status', 'marital_status');

  // Education
  setReviewValSelect('rv-destination_country',   'destination_country');
  setReviewValSelect('rv-preferred_intake',       'preferred_intake');
  setReviewVal('rv-course_program',               'course_program');
  setReviewVal('rv-preferred_university',         'preferred_university');
  setReviewValSelect('rv-highest_qualification',  'highest_qualification');
  setReviewVal('rv-ielts_score',                  'ielts_score');
  setReviewValSelect('rv-annual_budget',          'annual_budget');
  setReviewValSelect('rv-visa_refusals',          'visa_refusals');

  // Document status
  Object.keys(VisaApp.docConfigs).forEach(field => {
    updateDocStatus(field, !!VisaApp.uploadedFiles[field]);
  });
}

function setReviewVal(targetId, sourceId) {
  const target = document.getElementById(targetId);
  const source = document.getElementById(sourceId);
  if (!target || !source) return;
  target.textContent = source.value.trim() || '—';
}

function setReviewValSelect(targetId, sourceId) {
  const target = document.getElementById(targetId);
  const source = document.getElementById(sourceId);
  if (!target || !source) return;
  const opt = source.options[source.selectedIndex];
  target.textContent = (opt && opt.value) ? opt.text : '—';
}

function updateDocStatus(field, uploaded) {
  const el = document.getElementById(`ds-${field}`);
  if (!el) return;
  if (uploaded) {
    el.className   = 'badge-doc-uploaded';
    el.innerHTML   = '<i class="fas fa-check-circle me-1"></i> Uploaded';
  } else {
    el.className   = 'badge-doc-missing';
    el.innerHTML   = '<i class="fas fa-times-circle me-1"></i> Missing';
  }
}

// ================================================================
// FORM SUBMIT (AJAX)
// ================================================================
function initFormSubmit() {
  const saveBtn   = document.getElementById('btn-save-draft');
  const submitBtn = document.getElementById('btn-submit-app');

  if (saveBtn)   saveBtn.addEventListener('click',   saveDraft);
  if (submitBtn) submitBtn.addEventListener('click', submitApplication);
}

function buildFormData(isDraft) {
  const form = document.getElementById('visaApplicationForm');
  const fd   = new FormData(form);

  // Add app id if available
  if (VisaApp.appId)  fd.append('existing_id', VisaApp.appId);
  fd.append('is_draft', isDraft ? '1' : '0');

  // Attach file objects from uploadedFiles state
  Object.keys(VisaApp.uploadedFiles).forEach(field => {
    const fileObj = VisaApp.uploadedFiles[field];
    if (fileObj && fileObj.file) {
      fd.set(field, fileObj.file, fileObj.name);
    }
  });

  return fd;
}

function saveDraft() {
  const fd = buildFormData(true);

  Swal.fire({
    title: 'Saving Draft…',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });

  fetch('ajax/save_draft.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        VisaApp.appId         = data.id;
        VisaApp.applicationId = data.application_id;
        sessionStorage.setItem('vv_app_id', data.application_id);
        sessionStorage.setItem('vv_id',     data.id);
        showAppIdBanner(data.application_id);

        Swal.fire({
          icon: 'success',
          title: 'Draft Saved!',
          html: `Your progress has been saved.<br><strong>Reference: ${data.application_id}</strong>`,
          confirmButtonColor: '#0D1B3E',
          confirmButtonText: 'Continue Editing',
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Save Failed', text: data.message, confirmButtonColor: '#0D1B3E' });
      }
    })
    .catch(() => {
      Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not connect. Please try again.', confirmButtonColor: '#0D1B3E' });
    });
}

function submitApplication() {
  // Check confirmation checkbox
  const checkbox = document.getElementById('confirm_accuracy');
  if (!checkbox || !checkbox.checked) {
    Swal.fire({
      icon: 'warning',
      title: 'Confirmation Required',
      text: 'Please confirm that all information is accurate before submitting.',
      confirmButtonColor: '#0D1B3E',
    });
    return;
  }

  // Check required docs
  const missingDocs = VisaApp.requiredDocs.filter(f => !VisaApp.uploadedFiles[f]);
  if (missingDocs.length > 0) {
    const labels = missingDocs.map(f => VisaApp.docConfigs[f].label).join('<br>• ');
    Swal.fire({
      icon: 'error',
      title: 'Missing Required Documents',
      html: `Please upload the following required documents:<br><br><strong>• ${labels}</strong>`,
      confirmButtonColor: '#0D1B3E',
    });
    return;
  }

  Swal.fire({
    title: 'Submitting Application…',
    text: 'Please wait while we process your submission.',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });

  const fd = buildFormData(false);

  fetch('ajax/submit_application.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        sessionStorage.removeItem('vv_app_id');
        sessionStorage.removeItem('vv_id');

        Swal.fire({
          icon: 'success',
          title: 'Application Submitted! 🎉',
          html: `
            <p>Your study visa application has been submitted successfully.</p>
            <div style="background:#EEF2FF;border-radius:10px;padding:16px;margin:16px 0;">
              <div style="font-size:12px;color:#64748B;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Application Reference</div>
              <div style="font-size:22px;font-weight:800;color:#0D1B3E;letter-spacing:2px;">${data.application_id}</div>
            </div>
            <p style="color:#64748B;font-size:13px;">Our consultants will review your application and contact you within <strong>24 hours</strong>.</p>
          `,
          confirmButtonColor: '#D4AF37',
          confirmButtonText: 'Done',
          customClass: { confirmButton: 'swal-gold-btn' },
          allowOutsideClick: false,
        }).then(() => {
          window.location.reload();
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Submission Failed', text: data.message, confirmButtonColor: '#0D1B3E' });
      }
    })
    .catch(() => {
      Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not connect. Please try again.', confirmButtonColor: '#0D1B3E' });
    });
}

// ================================================================
// APP ID BANNER
// ================================================================
function showAppIdBanner(appId) {
  const banner = document.getElementById('app-id-banner');
  const idSpan = document.getElementById('app-id-text');
  if (banner && idSpan) {
    idSpan.textContent = appId;
    banner.style.display = 'inline-flex';
  }
}

// ================================================================
// ADMIN: Update Status (AJAX)
// ================================================================
function adminUpdateStatus(id) {
  const status = document.getElementById('status_select').value;
  const notes  = document.getElementById('status_notes').value;

  if (!status) {
    Swal.fire({ icon: 'warning', title: 'Select Status', text: 'Please choose a new status.', confirmButtonColor: '#0D1B3E' });
    return;
  }

  Swal.fire({
    title: 'Update Status?',
    text:  `Change status to "${status}"?`,
    icon:  'question',
    showCancelButton: true,
    confirmButtonColor: '#0D1B3E',
    cancelButtonColor:  '#6B7280',
    confirmButtonText:  'Yes, Update',
  }).then(result => {
    if (!result.isConfirmed) return;

    const fd = new FormData();
    fd.append('id',     id);
    fd.append('status', status);
    fd.append('notes',  notes);

    fetch('index.php?page=admin&action=update_status', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success', title: 'Status Updated!',
            text: data.message, confirmButtonColor: '#0D1B3E',
          }).then(() => location.reload());
        } else {
          Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#0D1B3E' });
        }
      });
  });
}

function adminAddNote(id) {
  const note = document.getElementById('admin_note_input').value.trim();
  if (!note) {
    Swal.fire({ icon: 'warning', title: 'Empty Note', text: 'Please type a note first.', confirmButtonColor: '#0D1B3E' });
    return;
  }

  const fd = new FormData();
  fd.append('id',   id);
  fd.append('note', note);

  Swal.fire({ title: 'Saving…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

  fetch('index.php?page=admin&action=add_note', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        Swal.fire({ icon: 'success', title: 'Note Added', text: data.message, confirmButtonColor: '#0D1B3E' })
          .then(() => location.reload());
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#0D1B3E' });
      }
    });
}
