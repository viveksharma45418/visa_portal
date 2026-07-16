<?php
/**
 * ApplicationModel — CRUD operations for study_visa_applications
 */
class ApplicationModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // ----------------------------------------------------------------
    // Generate a unique application ID like VV-2026-0001
    // ----------------------------------------------------------------
    public function generateApplicationId(): string
    {
        $year = date('Y');
        $stmt = $this->db->prepare(
            "SELECT application_id FROM study_visa_applications
             WHERE application_id LIKE :prefix ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute([':prefix' => "VV-{$year}-%"]);
        $last = $stmt->fetchColumn();

        if ($last) {
            $num = (int) substr($last, -4);
            $next = $num + 1;
        } else {
            $next = 1;
        }

        return sprintf('VV-%s-%04d', $year, $next);
    }

    // ----------------------------------------------------------------
    // Save or update a draft application (no validation enforced)
    // ----------------------------------------------------------------
    public function saveDraft(array $data, array $files = []): array
    {
        $existingId = $data['existing_id'] ?? null;

        if ($existingId) {
            // Update existing record
            $record = $this->getById((int)$existingId);
            if (!$record) {
                return ['success' => false, 'message' => 'Application not found.'];
            }
            $appId = $record['application_id'];
        } else {
            $appId = $this->generateApplicationId();
        }

        // Handle file uploads
        $filePaths = $this->processUploads($files, $appId);

        $sql = "INSERT INTO study_visa_applications (
                    application_id, full_name, date_of_birth, email, mobile, city,
                    passport_number, passport_expiry, marital_status,
                    destination_country, preferred_intake, course_program,
                    preferred_university, highest_qualification, ielts_score,
                    annual_budget, visa_refusals, refusal_details, additional_notes,
                    passport_file, passport_photo, tenth_marksheet, twelfth_marksheet,
                    bachelor_degree, ielts_scorecard, bank_statement, sop_file,
                    status, ip_address
                ) VALUES (
                    :application_id, :full_name, :date_of_birth, :email, :mobile, :city,
                    :passport_number, :passport_expiry, :marital_status,
                    :destination_country, :preferred_intake, :course_program,
                    :preferred_university, :highest_qualification, :ielts_score,
                    :annual_budget, :visa_refusals, :refusal_details, :additional_notes,
                    :passport_file, :passport_photo, :tenth_marksheet, :twelfth_marksheet,
                    :bachelor_degree, :ielts_scorecard, :bank_statement, :sop_file,
                    'Draft', :ip_address
                ) ON DUPLICATE KEY UPDATE
                    full_name = VALUES(full_name),
                    date_of_birth = VALUES(date_of_birth),
                    email = VALUES(email),
                    mobile = VALUES(mobile),
                    city = VALUES(city),
                    passport_number = VALUES(passport_number),
                    passport_expiry = VALUES(passport_expiry),
                    marital_status = VALUES(marital_status),
                    destination_country = VALUES(destination_country),
                    preferred_intake = VALUES(preferred_intake),
                    course_program = VALUES(course_program),
                    preferred_university = VALUES(preferred_university),
                    highest_qualification = VALUES(highest_qualification),
                    ielts_score = VALUES(ielts_score),
                    annual_budget = VALUES(annual_budget),
                    visa_refusals = VALUES(visa_refusals),
                    refusal_details = VALUES(refusal_details),
                    additional_notes = VALUES(additional_notes),
                    passport_file    = COALESCE(VALUES(passport_file), passport_file),
                    passport_photo   = COALESCE(VALUES(passport_photo), passport_photo),
                    tenth_marksheet  = COALESCE(VALUES(tenth_marksheet), tenth_marksheet),
                    twelfth_marksheet= COALESCE(VALUES(twelfth_marksheet), twelfth_marksheet),
                    bachelor_degree  = COALESCE(VALUES(bachelor_degree), bachelor_degree),
                    ielts_scorecard  = COALESCE(VALUES(ielts_scorecard), ielts_scorecard),
                    bank_statement   = COALESCE(VALUES(bank_statement), bank_statement),
                    sop_file         = COALESCE(VALUES(sop_file), sop_file),
                    ip_address = VALUES(ip_address)";

        $params = [
            ':application_id'       => $appId,
            ':full_name'            => $this->sanitize($data['full_name'] ?? ''),
            ':date_of_birth'        => $data['date_of_birth'] ?: null,
            ':email'                => $this->sanitize($data['email'] ?? ''),
            ':mobile'               => $this->sanitize($data['mobile'] ?? ''),
            ':city'                 => $this->sanitize($data['city'] ?? ''),
            ':passport_number'      => $this->sanitize($data['passport_number'] ?? ''),
            ':passport_expiry'      => $data['passport_expiry'] ?: null,
            ':marital_status'       => $data['marital_status'] ?: null,
            ':destination_country'  => $data['destination_country'] ?: null,
            ':preferred_intake'     => $data['preferred_intake'] ?: null,
            ':course_program'       => $this->sanitize($data['course_program'] ?? ''),
            ':preferred_university' => $this->sanitize($data['preferred_university'] ?? ''),
            ':highest_qualification'=> $data['highest_qualification'] ?: null,
            ':ielts_score'          => $this->sanitize($data['ielts_score'] ?? ''),
            ':annual_budget'        => $data['annual_budget'] ?: null,
            ':visa_refusals'        => $data['visa_refusals'] ?? 'No',
            ':refusal_details'      => $this->sanitize($data['refusal_details'] ?? ''),
            ':additional_notes'     => $this->sanitize($data['additional_notes'] ?? ''),
            ':passport_file'        => $filePaths['passport_file'] ?? null,
            ':passport_photo'       => $filePaths['passport_photo'] ?? null,
            ':tenth_marksheet'      => $filePaths['tenth_marksheet'] ?? null,
            ':twelfth_marksheet'    => $filePaths['twelfth_marksheet'] ?? null,
            ':bachelor_degree'      => $filePaths['bachelor_degree'] ?? null,
            ':ielts_scorecard'      => $filePaths['ielts_scorecard'] ?? null,
            ':bank_statement'       => $filePaths['bank_statement'] ?? null,
            ':sop_file'             => $filePaths['sop_file'] ?? null,
            ':ip_address'           => $_SERVER['REMOTE_ADDR'] ?? '',
        ];

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $insertedId = (int)$this->db->lastInsertId();
        if ($insertedId === 0) {
            // Row was updated, fetch id
            $r = $this->getByApplicationId($appId);
            $insertedId = $r['id'] ?? 0;
        }

        return [
            'success'        => true,
            'id'             => $insertedId,
            'application_id' => $appId,
            'message'        => 'Draft saved successfully.',
        ];
    }

    // ----------------------------------------------------------------
    // Submit application (changes status to Submitted)
    // ----------------------------------------------------------------
    public function submitApplication(int $id): array
    {
        $stmt = $this->db->prepare(
            "UPDATE study_visa_applications SET status = 'Submitted', updated_at = NOW()
             WHERE id = :id AND status = 'Draft'"
        );
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
            // Maybe already submitted or ID wrong — still return success
            $rec = $this->getById($id);
            if ($rec) {
                return ['success' => true, 'application_id' => $rec['application_id']];
            }
            return ['success' => false, 'message' => 'Application not found.'];
        }

        $rec = $this->getById($id);
        return ['success' => true, 'application_id' => $rec['application_id'] ?? ''];
    }

    // ----------------------------------------------------------------
    // Fetch single application by DB id
    // ----------------------------------------------------------------
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM study_visa_applications WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // ----------------------------------------------------------------
    // Fetch single application by application_id (VV-2026-XXXX)
    // ----------------------------------------------------------------
    public function getByApplicationId(string $appId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM study_visa_applications WHERE application_id = :app_id LIMIT 1"
        );
        $stmt->execute([':app_id' => $appId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // ----------------------------------------------------------------
    // Get all applications with optional search + status filter
    // ----------------------------------------------------------------
    public function getAll(string $search = '', string $status = ''): array
    {
        $where  = [];
        $params = [];

        if ($search !== '') {
            $where[] = "(full_name LIKE :search1 OR email LIKE :search2 OR application_id LIKE :search3 OR mobile LIKE :search4)";
            $searchVal          = "%{$search}%";
            $params[':search1'] = $searchVal;
            $params[':search2'] = $searchVal;
            $params[':search3'] = $searchVal;
            $params[':search4'] = $searchVal;
        }

        if ($status !== '') {
            $where[]          = "status = :status";
            $params[':status'] = $status;
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $this->db->prepare(
            "SELECT * FROM study_visa_applications {$whereClause} ORDER BY created_at DESC"
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // ----------------------------------------------------------------
    // Count applications grouped by status (for admin stats)
    // ----------------------------------------------------------------
    public function countByStatus(): array
    {
        $stmt = $this->db->query(
            "SELECT status, COUNT(*) as total FROM study_visa_applications GROUP BY status"
        );
        $rows    = $stmt->fetchAll();
        $counts  = [];
        foreach ($rows as $row) {
            $counts[$row['status']] = (int)$row['total'];
        }
        return $counts;
    }

    public function getTotalCount(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM study_visa_applications")->fetchColumn();
    }

    // ----------------------------------------------------------------
    // Update application status (admin)
    // ----------------------------------------------------------------
    public function updateStatus(int $id, string $status, string $notes = ''): bool
    {
        $allowedStatuses = ['Draft','Submitted','Under Review','Documents Requested','Approved','Rejected'];
        if (!in_array($status, $allowedStatuses, true)) {
            return false;
        }

        $old = $this->getById($id);

        $stmt = $this->db->prepare(
            "UPDATE study_visa_applications
             SET status = :status, updated_at = NOW()
             WHERE id = :id"
        );
        $stmt->execute([':status' => $status, ':id' => $id]);

        // Log the change
        if ($old) {
            $log = $this->db->prepare(
                "INSERT INTO admin_activity_log (application_id, action, old_value, new_value, notes, admin_ip)
                 VALUES (:app_id, 'status_change', :old, :new, :notes, :ip)"
            );
            $log->execute([
                ':app_id' => $old['application_id'],
                ':old'    => $old['status'],
                ':new'    => $status,
                ':notes'  => $notes,
                ':ip'     => $_SERVER['REMOTE_ADDR'] ?? '',
            ]);

            // Trigger email/message notification to applicant
            NotificationHelper::sendStatusEmail($old, $status, $notes);
        }

        return true;
    }

    // ----------------------------------------------------------------
    // Add admin note to an application
    // ----------------------------------------------------------------
    public function addNote(int $id, string $note): bool
    {
        $rec = $this->getById($id);
        if (!$rec) return false;

        $existing  = $rec['admin_notes'] ?? '';
        $timestamp = date('Y-m-d H:i');
        $newNote   = $existing
            ? $existing . "\n\n[{$timestamp}] " . $note
            : "[{$timestamp}] " . $note;

        $stmt = $this->db->prepare(
            "UPDATE study_visa_applications SET admin_notes = :notes, updated_at = NOW() WHERE id = :id"
        );
        $stmt->execute([':notes' => $newNote, ':id' => $id]);

        // Log
        $log = $this->db->prepare(
            "INSERT INTO admin_activity_log (application_id, action, notes, admin_ip)
             VALUES (:app_id, 'note_added', :notes, :ip)"
        );
        $log->execute([
            ':app_id' => $rec['application_id'],
            ':notes'  => $note,
            ':ip'     => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        return true;
    }

    // ----------------------------------------------------------------
    // Process file uploads — returns array of saved paths
    // ----------------------------------------------------------------
    public function processUploads(array $files, string $appId): array
    {
        $saved   = [];
        $fields  = [
            'passport_file','passport_photo','tenth_marksheet','twelfth_marksheet',
            'bachelor_degree','ielts_scorecard','bank_statement','sop_file'
        ];

        $appDir = UPLOAD_PATH . DIRECTORY_SEPARATOR . $appId;
        if (!is_dir($appDir)) {
            mkdir($appDir, 0755, true);
        }

        foreach ($fields as $field) {
            if (!isset($files[$field]) || $files[$field]['error'] !== UPLOAD_ERR_OK) {
                continue;
            }

            $file = $files[$field];
            $size = $file['size'];

            if ($size > MAX_UPLOAD_SIZE) {
                continue; // Skip oversized files silently
            }

            // Validate MIME type using finfo
            $finfo    = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);
            if (!in_array($mimeType, ALLOWED_MIME_TYPES, true)) {
                continue;
            }

            // Get extension from original filename safely
            $origName  = basename($file['name']);
            $ext       = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            if (!in_array($ext, ALLOWED_EXTENSIONS, true)) {
                continue;
            }

            // Generate safe unique filename
            $safeField  = preg_replace('/[^a-z0-9_]/', '', $field);
            $uniqueName = $safeField . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $destPath   = $appDir . DIRECTORY_SEPARATOR . $uniqueName;

            if (move_uploaded_file($file['tmp_name'], $destPath)) {
                $saved[$field] = UPLOAD_URL . '/' . $appId . '/' . $uniqueName;
            }
        }

        return $saved;
    }

    // ----------------------------------------------------------------
    // Helper: sanitize string input
    // ----------------------------------------------------------------
    private function sanitize(string $val): string
    {
        return trim(strip_tags($val));
    }

    // ----------------------------------------------------------------
    // Required document fields
    // ----------------------------------------------------------------
    public function getRequiredDocFields(): array
    {
        return ['passport_file','passport_photo','tenth_marksheet','twelfth_marksheet','bank_statement'];
    }

    // ----------------------------------------------------------------
    // Check if all required docs are uploaded for an application
    // ----------------------------------------------------------------
    public function hasRequiredDocs(array $record): bool
    {
        foreach ($this->getRequiredDocFields() as $field) {
            if (empty($record[$field])) return false;
        }
        return true;
    }
}
