<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard — Visa Vista Global</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="assets/css/style.css?v=1.3">
  <style>
    .app-id-link { font-weight: 700; color: var(--primary); font-size: 13px; letter-spacing: 0.5px; }
    .app-id-link:hover { color: var(--gold-dark); }
    .mobile-truncate { max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .empty-state { padding: 60px 20px; text-align: center; color: var(--text-muted); }
    .empty-state i { font-size: 48px; opacity: 0.3; margin-bottom: 16px; }
  </style>
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
        <a href="index.php" class="btn-portal" style="color:rgba(255,255,255,0.75)">
          <i class="fas fa-external-link-alt me-1"></i> Client Portal
        </a>
        <span style="color:rgba(255,255,255,0.3);font-size:13px;">|</span>
        <span style="color:rgba(255,255,255,0.55);font-size:13px;">
          <i class="fas fa-user-shield me-1" style="color:var(--gold);"></i>
          <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>
        </span>
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
        <h1 class="mb-1"><i class="fas fa-tachometer-alt me-3" style="color:var(--gold);"></i>Applications Dashboard</h1>
        <p class="mb-0">Manage, review and process all study visa applications</p>
      </div>
      <a href="index.php" class="btn-dashboard">
        <i class="fas fa-plus me-2"></i> New Application
      </a>
    </div>
  </div>
</div>

<div class="admin-content">
  <div class="container">

    <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>
      <?= htmlspecialchars($_GET['error']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
      <?php
        $statusDefs = [
          'total'               => ['label' => 'Total Applications', 'icon' => 'fa-layer-group',    'cls' => 'navy'],
          'Submitted'           => ['label' => 'Submitted',          'icon' => 'fa-paper-plane',    'cls' => 'blue'],
          'Under Review'        => ['label' => 'Under Review',       'icon' => 'fa-search',         'cls' => 'orange'],
          'Approved'            => ['label' => 'Approved',           'icon' => 'fa-check-circle',   'cls' => 'green'],
          'Rejected'            => ['label' => 'Rejected',           'icon' => 'fa-times-circle',   'cls' => 'red'],
          'Draft'               => ['label' => 'Drafts',             'icon' => 'fa-file-edit',      'cls' => 'gold'],
        ];
        $counts['total'] = $total;
        foreach ($statusDefs as $key => $def):
          $count = $counts[$key] ?? 0;
      ?>
      <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
          <div class="sc-icon <?= $def['cls'] ?>"><i class="fas <?= $def['icon'] ?>"></i></div>
          <div>
            <div class="sc-number"><?= number_format($count) ?></div>
            <div class="sc-label"><?= htmlspecialchars($def['label']) ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Applications Table Card -->
    <div class="admin-table-card">
      <div class="admin-table-header">
        <h5><i class="fas fa-list me-2"></i> All Applications</h5>
        <form method="GET" action="index.php" class="admin-table-filters">
          <input type="hidden" name="page" value="admin">
          <input type="text" class="filter-input" name="search"
                 placeholder="Search name, email, ID…"
                 value="<?= htmlspecialchars($search) ?>" style="min-width:200px;">
          <select class="filter-input" name="status" style="min-width:160px;">
            <option value="">All Statuses</option>
            <option value="Draft"               <?= $status==='Draft'               ? 'selected':'' ?>>Draft</option>
            <option value="Submitted"           <?= $status==='Submitted'           ? 'selected':'' ?>>Submitted</option>
            <option value="Under Review"        <?= $status==='Under Review'        ? 'selected':'' ?>>Under Review</option>
            <option value="Documents Requested" <?= $status==='Documents Requested' ? 'selected':'' ?>>Documents Requested</option>
            <option value="Approved"            <?= $status==='Approved'            ? 'selected':'' ?>>Approved</option>
            <option value="Rejected"            <?= $status==='Rejected'            ? 'selected':'' ?>>Rejected</option>
          </select>
          <button type="submit" class="btn-filter">
            <i class="fas fa-search"></i> Search
          </button>
          <?php if ($search || $status): ?>
          <a href="index.php?page=admin" class="btn-portal" style="padding:8px 14px;font-size:13px;">
            <i class="fas fa-times"></i> Clear
          </a>
          <?php endif; ?>
        </form>
      </div>

      <?php if (empty($applications)): ?>
      <div class="empty-state">
        <div><i class="fas fa-folder-open d-block mb-3"></i></div>
        <h5 style="color:var(--text-dark);font-weight:700;">No Applications Found</h5>
        <p style="font-size:14px;">
          <?= ($search || $status) ? 'Try adjusting your search or filter criteria.' : 'No applications have been submitted yet.' ?>
        </p>
      </div>
      <?php else: ?>

      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Application ID</th>
              <th>Applicant</th>
              <th>Contact</th>
              <th>Destination</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($applications as $i => $app):
              $statusClass = match($app['status']) {
                'Draft'               => 'status-draft',
                'Submitted'           => 'status-submitted',
                'Under Review'        => 'status-review',
                'Documents Requested' => 'status-docs',
                'Approved'            => 'status-approved',
                'Rejected'            => 'status-rejected',
                default               => 'status-draft',
              };
              $statusIcon = match($app['status']) {
                'Draft'               => 'fa-file-alt',
                'Submitted'           => 'fa-paper-plane',
                'Under Review'        => 'fa-search',
                'Documents Requested' => 'fa-exclamation-circle',
                'Approved'            => 'fa-check-circle',
                'Rejected'            => 'fa-times-circle',
                default               => 'fa-circle',
              };
            ?>
            <tr>
              <td style="color:var(--text-muted);font-size:12px;"><?= $i + 1 ?></td>
              <td>
                <a href="index.php?page=admin&action=view&id=<?= $app['id'] ?>" class="app-id-link">
                  <?= htmlspecialchars($app['application_id']) ?>
                </a>
              </td>
              <td>
                <div style="font-weight:600;font-size:13.5px;"><?= htmlspecialchars($app['full_name'] ?: '—') ?></div>
                <div style="font-size:12px;color:var(--text-muted);"><?= htmlspecialchars($app['city'] ?: '') ?></div>
              </td>
              <td>
                <div class="mobile-truncate" style="font-size:13px;"><?= htmlspecialchars($app['email'] ?: '—') ?></div>
                <div style="font-size:12px;color:var(--text-muted);"><?= htmlspecialchars($app['mobile'] ?: '') ?></div>
              </td>
              <td>
                <div style="font-size:13px;font-weight:600;"><?= htmlspecialchars($app['destination_country'] ?: '—') ?></div>
                <div style="font-size:12px;color:var(--text-muted);"><?= htmlspecialchars($app['preferred_intake'] ?: '') ?></div>
              </td>
              <td>
                <span class="status-badge <?= $statusClass ?>">
                  <i class="fas <?= $statusIcon ?>"></i>
                  <?= htmlspecialchars($app['status']) ?>
                </span>
              </td>
              <td style="font-size:12px;color:var(--text-muted);white-space:nowrap;">
                <?= date('d M Y', strtotime($app['created_at'])) ?><br>
                <?= date('h:i A', strtotime($app['created_at'])) ?>
              </td>
              <td>
                <a href="index.php?page=admin&action=view&id=<?= $app['id'] ?>"
                   class="btn-choose-file" style="font-size:11px;padding:5px 12px;text-decoration:none;">
                  <i class="fas fa-eye"></i> View
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div style="padding:14px 20px;border-top:1px solid var(--border);font-size:12px;color:var(--text-muted);">
        Showing <?= count($applications) ?> of <?= number_format($total) ?> application(s)
      </div>

      <?php endif; ?>
    </div>

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
