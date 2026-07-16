<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Application <?= htmlspecialchars($application['application_id']) ?> — Visa Vista Global Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="admin-body">

<!-- Admin Navbar -->
<nav class="admin-navbar">
  <div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;">
      <a href="index.php?page=admin" class="brand-wrap">
        <div class="brand-logo-circle"><i class="fas fa-globe-americas"></i></div>
        <div class="brand-text">
          <div class="name">Visa Vista Global</div>
          <div class="tagline">Admin Dashboard</div>
        </div>
      </a>
      <div style="display:flex;gap:10px;align-items:center;">
        <a href="index.php?page=admin" class="btn-portal" style="color:rgba(255,255,255,0.75);">
          <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
        <a href="index.php?page=admin&action=logout" class="btn-portal"
           style="color:#FCA5A5;border-color:rgba(239,68,68,0.3);"
           onclick="return confirm('Log out of admin panel?')">
          <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- Admin Hero -->
<div class="admin-hero">
  <div class="container">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
      <div>
        <div style="font-size:12px;color:rgba(255,255,255,0.5);letter-spacing:1px;text-transform:uppercase;margin-bottom:6px;">
          Application Detail
        </div>
        <h1 style="font-size:24px;font-weight:800;color:#FFF;margin:0;" class="d-flex align-items-center gap-3">
          <i class="fas fa-id-card" style="color:var(--gold);"></i>
          <?= htmlspecialchars($application['application_id']) ?>
          <?php
            $statusClass = match($application['status']) {
              'Draft'               => 'status-draft',
              'Submitted'           => 'status-submitted',
              'Under Review'        => 'status-review',
              'Documents Requested' => 'status-docs',
              'Approved'            => 'status-approved',
              'Rejected'            => 'status-rejected',
              default               => 'status-draft',
            };
          ?>
          <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($application['status']) ?></span>
        </h1>
        <p style="color:rgba(255,255,255,0.5);margin:6px 0 0;font-size:13px;">
          Submitted: <?= date('d M Y, h:i A', strtotime($application['created_at'])) ?>
          &bull; Updated: <?= date('d M Y, h:i A', strtotime($application['updated_at'])) ?>
        </p>
      </div>
    </div>
  </div>
</div>

<div class="admin-content">
  <div class="container">
    <div class="row g-4">

      <!-- LEFT COLUMN -->
      <div class="col-lg-8">

        <!-- Personal Information -->
        <div class="detail-card">
          <div class="detail-card-header">
            <i class="fas fa-user"></i>
            <h5>Personal Information</h5>
          </div>
          <div class="detail-card-body">
            <div class="detail-grid">
              <?php
                $personalFields = [
                  'full_name'        => 'Full Name',
                  'date_of_birth'    => 'Date of Birth',
                  'email'            => 'Email Address',
                  'mobile'           => 'Mobile / WhatsApp',
                  'city'             => 'City / District',
                  'marital_status'   => 'Marital Status',
                  'passport_number'  => 'Passport Number',
                  'passport_expiry'  => 'Passport Expiry',
                  'ip_address'       => 'IP Address',
                ];
                foreach ($personalFields as $key => $label):
                  $val = $application[$key] ?? '';
                  if (in_array($key, ['date_of_birth', 'passport_expiry']) && $val) {
                    $val = date('d M Y', strtotime($val));
                  }
              ?>
              <div class="detail-field">
                <label><?= htmlspecialchars($label) ?></label>
                <span class="<?= $val ? '' : 'empty' ?>">
                  <?= $val ? htmlspecialchars($val) : 'Not provided' ?>
                </span>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Education & Destination -->
        <div class="detail-card">
          <div class="detail-card-header">
            <i class="fas fa-graduation-cap"></i>
            <h5>Education &amp; Destination</h5>
          </div>
          <div class="detail-card-body">
            <div class="detail-grid">
              <?php
                $eduFields = [
                  'destination_country'   => 'Destination Country',
                  'preferred_intake'      => 'Preferred Intake',
                  'course_program'        => 'Course / Program',
                  'preferred_university'  => 'Preferred University',
                  'highest_qualification' => 'Highest Qualification',
                  'ielts_score'           => 'IELTS / PTE Score',
                  'annual_budget'         => 'Annual Budget',
                  'visa_refusals'         => 'Previous Visa Refusals',
                ];
                foreach ($eduFields as $key => $label):
                  $val = $application[$key] ?? '';
              ?>
              <div class="detail-field">
                <label><?= htmlspecialchars($label) ?></label>
                <span class="<?= $val ? '' : 'empty' ?>">
                  <?= $val ? htmlspecialchars($val) : 'Not provided' ?>
                </span>
              </div>
              <?php endforeach; ?>
            </div>

            <?php if (!empty($application['refusal_details'])): ?>
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
              <label style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.6px;display:block;margin-bottom:6px;">
                Refusal Details
              </label>
              <div style="background:#FFFBEB;border:1px solid rgba(212,175,55,0.3);border-radius:8px;padding:12px;font-size:13px;color:var(--text-dark);">
                <?= nl2br(htmlspecialchars($application['refusal_details'])) ?>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($application['additional_notes'])): ?>
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
              <label style="font-size:11px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.6px;display:block;margin-bottom:6px;">
                Additional Notes
              </label>
              <div class="notes-display">
                <?= nl2br(htmlspecialchars($application['additional_notes'])) ?>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Uploaded Documents -->
        <div class="detail-card">
          <div class="detail-card-header">
            <i class="fas fa-folder-open"></i>
            <h5>Uploaded Documents</h5>
          </div>
          <div class="detail-card-body">
            <?php
              $docFields = [
                'passport_file'     => ['label' => 'Passport (All Pages)',         'icon' => 'fa-passport',    'required' => true],
                'passport_photo'    => ['label' => 'Passport Size Photo',          'icon' => 'fa-camera',      'required' => true],
                'tenth_marksheet'   => ['label' => '10th Marksheet & Certificate', 'icon' => 'fa-file-alt',    'required' => true],
                'twelfth_marksheet' => ['label' => '12th Marksheet & Certificate', 'icon' => 'fa-file-alt',    'required' => true],
                'bachelor_degree'   => ['label' => "Bachelor's Degree / Diploma",  'icon' => 'fa-scroll',      'required' => false],
                'ielts_scorecard'   => ['label' => 'IELTS / PTE / TOEFL Scorecard','icon' => 'fa-language',   'required' => false],
                'bank_statement'    => ['label' => 'Bank Statements (6 Months)',   'icon' => 'fa-university',  'required' => true],
                'sop_file'          => ['label' => 'Statement of Purpose (SOP)',   'icon' => 'fa-pen-fancy',   'required' => false],
              ];
            ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px;">
              <?php foreach ($docFields as $field => $cfg):
                $path    = $application[$field] ?? null;
                $hasFile = !empty($path);
              ?>
              <div style="border:1px solid var(--border);border-radius:10px;padding:14px;display:flex;align-items:center;gap:12px;
                          background:<?= $hasFile ? '#F0FDF4' : '#FAFAFA' ?>;
                          border-color:<?= $hasFile ? 'rgba(16,185,129,0.3)' : 'var(--border)' ?>;">
                <div style="width:38px;height:38px;border-radius:8px;display:flex;align-items:center;justify-content:center;
                            background:<?= $hasFile ? 'rgba(16,185,129,0.12)' : 'rgba(13,27,62,0.06)' ?>;
                            color:<?= $hasFile ? 'var(--success)' : 'var(--text-muted)' ?>;flex-shrink:0;">
                  <i class="fas <?= $cfg['icon'] ?>"></i>
                </div>
                <div style="flex:1;min-width:0;">
                  <div style="font-size:12.5px;font-weight:700;color:var(--text-dark);margin-bottom:3px;display:flex;align-items:center;gap:6px;">
                    <?= htmlspecialchars($cfg['label']) ?>
                    <?php if ($cfg['required']): ?>
                      <span style="font-size:9px;color:var(--danger);font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">REQ</span>
                    <?php endif; ?>
                  </div>
                  <?php if ($hasFile): ?>
                    <a href="index.php?page=admin&action=download&id=<?= $application['id'] ?>&field=<?= $field ?>"
                       class="doc-link" target="_blank" style="font-size:11px;padding:3px 10px;margin:0;">
                      <i class="fas fa-download"></i> Download
                    </a>
                  <?php else: ?>
                    <span style="font-size:12px;color:var(--danger);font-weight:600;">
                      <i class="fas fa-times-circle me-1"></i> Not uploaded
                    </span>
                  <?php endif; ?>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

      </div><!-- /col-lg-8 -->

      <!-- RIGHT COLUMN: Admin Actions -->
      <div class="col-lg-4">

        <!-- Update Status -->
        <div class="detail-card mb-4">
          <div class="detail-card-header">
            <i class="fas fa-exchange-alt"></i>
            <h5>Update Status</h5>
          </div>
          <div class="detail-card-body">
            <div class="status-form-card">
              <div class="mb-3">
                <label class="form-label" style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">
                  New Status
                </label>
                <select class="form-select" id="status_select">
                  <?php
                    $statuses = ['Draft','Submitted','Under Review','Documents Requested','Approved','Rejected'];
                    foreach ($statuses as $s):
                  ?>
                  <option value="<?= $s ?>" <?= $application['status'] === $s ? 'selected' : '' ?>>
                    <?= $s ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label" style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">
                  Notes (Optional)
                </label>
                <textarea class="form-control" id="status_notes" rows="2"
                          placeholder="Reason for status change..."></textarea>
              </div>
              <button type="button" class="btn-next-step w-100"
                      onclick="adminUpdateStatus(<?= $application['id'] ?>)">
                <i class="fas fa-save me-2"></i> Update Status
              </button>

              <a href="https://api.whatsapp.com/send?phone=<?= urlencode(preg_replace('/[^\d]/', '', $application['mobile'])) ?>&text=<?= urlencode("Hello " . $application['full_name'] . ",\n\nWe are updating you regarding your Study Visa Application (" . $application['application_id'] . "). Currently, the status is: *" . $application['status'] . "*.\n\nRegards,\nVisa Vista Global Support") ?>"
                 target="_blank" class="btn-save-draft w-100 mt-2 text-center justify-content-center" style="border-color:#25D366; color:#128C7E; text-decoration:none;">
                <i class="fab fa-whatsapp me-2" style="color:#25D366; font-size:16px;"></i> Send WhatsApp Chat
              </a>
            </div>
          </div>
        </div>

        <!-- Admin Notes -->
        <div class="detail-card mb-4">
          <div class="detail-card-header">
            <i class="fas fa-sticky-note"></i>
            <h5>Internal Notes</h5>
          </div>
          <div class="detail-card-body">
            <?php if (!empty($application['admin_notes'])): ?>
            <div class="notes-display mb-3">
              <?= nl2br(htmlspecialchars($application['admin_notes'])) ?>
            </div>
            <?php else: ?>
            <div style="color:var(--text-muted);font-size:13px;margin-bottom:12px;font-style:italic;">
              No notes added yet.
            </div>
            <?php endif; ?>

            <textarea class="form-control mb-2" id="admin_note_input" rows="3"
                      placeholder="Add an internal note…"></textarea>
            <button type="button" class="btn-save-draft w-100"
                    onclick="adminAddNote(<?= $application['id'] ?>)">
              <i class="fas fa-plus me-2"></i> Add Note
            </button>
          </div>
        </div>

        <!-- Quick Info Card -->
        <div class="detail-card">
          <div class="detail-card-header">
            <i class="fas fa-info-circle"></i>
            <h5>Quick Info</h5>
          </div>
          <div class="detail-card-body">
            <?php
              $reqDocs  = ['passport_file','passport_photo','tenth_marksheet','twelfth_marksheet','bank_statement'];
              $uploaded = array_filter($reqDocs, fn($f) => !empty($application[$f]));
              $pct      = count($reqDocs) > 0 ? (count($uploaded) / count($reqDocs)) * 100 : 0;
            ?>
            <div class="mb-3">
              <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:6px;">
                <span style="font-weight:600;color:var(--text-dark);">Required Documents</span>
                <span style="color:var(--text-muted);"><?= count($uploaded) ?>/<?= count($reqDocs) ?></span>
              </div>
              <div style="background:var(--border);border-radius:50px;height:8px;">
                <div style="background:linear-gradient(90deg,var(--gold),var(--gold-dark));height:8px;border-radius:50px;width:<?= $pct ?>%;transition:width 0.5s;"></div>
              </div>
            </div>
            <div class="detail-field mb-2">
              <label>Course Applied</label>
              <span class="<?= empty($application['course_program']) ? 'empty' : '' ?>">
                <?= htmlspecialchars($application['course_program'] ?: 'Not specified') ?>
              </span>
            </div>
            <div class="detail-field mb-2">
              <label>Budget</label>
              <span class="<?= empty($application['annual_budget']) ? 'empty' : '' ?>">
                <?= htmlspecialchars($application['annual_budget'] ?: 'Not specified') ?>
              </span>
            </div>
            <div class="detail-field">
              <label>Visa Refusals</label>
              <span style="color:<?= $application['visa_refusals']==='Yes' ? 'var(--danger)' : 'var(--success)' ?>;font-weight:700;">
                <?= $application['visa_refusals'] === 'Yes' ? '⚠ Yes' : '✔ No' ?>
              </span>
            </div>
          </div>
        </div>

      </div><!-- /col-lg-4 -->

    </div><!-- /row -->
  </div>
</div>

<!-- Footer -->
<footer class="vv-footer mt-5">
  <div class="container">
    <p>&copy; <?= date('Y') ?> Visa Vista Global — Admin Panel</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
