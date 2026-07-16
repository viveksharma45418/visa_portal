<?php
/**
 * AdminController — Handles all admin-facing operations.
 */
class AdminController
{
    private ApplicationModel $model;

    public function __construct(ApplicationModel $model)
    {
        $this->model = $model;
    }

    /**
     * Admin dashboard with list, search, filter.
     */
    public function dashboard(): void
    {
        AuthController::requireAuth();
        $search = trim($_GET['search'] ?? '');
        $status = trim($_GET['status'] ?? '');

        $applications = $this->model->getAll($search, $status);
        $counts       = $this->model->countByStatus();
        $total        = $this->model->getTotalCount();

        include BASE_PATH . '/views/admin/dashboard.php';
    }

    /**
     * View a single application in detail.
     */
    public function viewApplication(?string $idRaw): void
    {
        AuthController::requireAuth();
        $id = (int)($idRaw ?? 0);
        if (!$id) {
            $this->redirectToDashboard('Invalid application ID.');
            return;
        }

        $application = $this->model->getById($id);
        if (!$application) {
            $this->redirectToDashboard('Application not found.');
            return;
        }

        include BASE_PATH . '/views/admin/view_application.php';
    }

    /**
     * Update application status (POST).
     */
    public function updateStatus(): void
    {
        AuthController::requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, 'Method not allowed.');
            return;
        }

        $id     = (int)($_POST['id'] ?? 0);
        $status = trim($_POST['status'] ?? '');
        $notes  = trim($_POST['notes'] ?? '');

        if (!$id || !$status) {
            $this->jsonResponse(false, 'Missing required fields.');
            return;
        }

        $result = $this->model->updateStatus($id, $status, $notes);
        if ($result) {
            $this->jsonResponse(true, 'Status updated successfully.');
        } else {
            $this->jsonResponse(false, 'Invalid status or application not found.');
        }
    }

    /**
     * Add internal admin note (POST).
     */
    public function addNote(): void
    {
        AuthController::requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, 'Method not allowed.');
            return;
        }

        $id   = (int)($_POST['id'] ?? 0);
        $note = trim($_POST['note'] ?? '');

        if (!$id || !$note) {
            $this->jsonResponse(false, 'Missing required fields.');
            return;
        }

        $result = $this->model->addNote($id, $note);
        $this->jsonResponse($result, $result ? 'Note added.' : 'Failed to add note.');
    }

    /**
     * Download a document file securely.
     */
    public function downloadDocument(): void
    {
        AuthController::requireAuth();
        $id    = (int)($_GET['id'] ?? 0);
        $field = preg_replace('/[^a-z_]/', '', $_GET['field'] ?? '');

        $allowedFields = [
            'passport_file','passport_photo','tenth_marksheet','twelfth_marksheet',
            'bachelor_degree','ielts_scorecard','bank_statement','sop_file'
        ];

        if (!$id || !in_array($field, $allowedFields, true)) {
            http_response_code(400);
            die('Invalid request.');
        }

        $app = $this->model->getById($id);
        if (!$app || empty($app[$field])) {
            http_response_code(404);
            die('File not found.');
        }

        $relativePath = $app[$field];
        $absPath      = BASE_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

        if (!file_exists($absPath)) {
            http_response_code(404);
            die('File not found on disk.');
        }

        // Serve the file
        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($absPath);
        $filename = basename($absPath);

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($absPath));
        header('Cache-Control: private, no-cache');
        readfile($absPath);
        exit;
    }

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------
    private function jsonResponse(bool $success, string $message, array $data = []): void
    {
        header('Content-Type: application/json');
        echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
        exit;
    }

    private function redirectToDashboard(string $msg = ''): void
    {
        $query = $msg ? '?error=' . urlencode($msg) : '';
        header("Location: index.php?page=admin{$query}");
        exit;
    }
}
