<?php
/**
 * AJAX: Save Draft
 * Accepts FormData via POST, saves to DB, returns JSON.
 */
header('Content-Type: application/json');

// Security: Accept only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/ApplicationModel.php';

$model = new ApplicationModel();

// Collect POST data
$data = [
    'existing_id'           => $_POST['existing_id'] ?? null,
    'full_name'             => $_POST['full_name'] ?? '',
    'date_of_birth'         => $_POST['date_of_birth'] ?? '',
    'email'                 => $_POST['email'] ?? '',
    'mobile'                => $_POST['mobile'] ?? '',
    'city'                  => $_POST['city'] ?? '',
    'passport_number'       => $_POST['passport_number'] ?? '',
    'passport_expiry'       => $_POST['passport_expiry'] ?? '',
    'marital_status'        => $_POST['marital_status'] ?? '',
    'destination_country'   => $_POST['destination_country'] ?? '',
    'preferred_intake'      => $_POST['preferred_intake'] ?? '',
    'course_program'        => $_POST['course_program'] ?? '',
    'preferred_university'  => $_POST['preferred_university'] ?? '',
    'highest_qualification' => $_POST['highest_qualification'] ?? '',
    'ielts_score'           => $_POST['ielts_score'] ?? '',
    'annual_budget'         => $_POST['annual_budget'] ?? '',
    'visa_refusals'         => $_POST['visa_refusals'] ?? 'No',
    'refusal_details'       => $_POST['refusal_details'] ?? '',
    'additional_notes'      => $_POST['additional_notes'] ?? '',
];

try {
    $result = $model->saveDraft($data, $_FILES);
    echo json_encode($result);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
