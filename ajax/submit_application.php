<?php
/**
 * AJAX: Submit Application
 * Full validation + required doc check, then marks as Submitted.
 */
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/models/ApplicationModel.php';

$model = new ApplicationModel();

// ── Collect POST data ──────────────────────────────────────────
$data = [
    'existing_id'           => $_POST['existing_id'] ?? null,
    'full_name'             => trim($_POST['full_name'] ?? ''),
    'date_of_birth'         => trim($_POST['date_of_birth'] ?? ''),
    'email'                 => trim($_POST['email'] ?? ''),
    'mobile'                => trim($_POST['mobile'] ?? ''),
    'city'                  => trim($_POST['city'] ?? ''),
    'passport_number'       => trim($_POST['passport_number'] ?? ''),
    'passport_expiry'       => trim($_POST['passport_expiry'] ?? ''),
    'marital_status'        => $_POST['marital_status'] ?? '',
    'destination_country'   => $_POST['destination_country'] ?? '',
    'preferred_intake'      => $_POST['preferred_intake'] ?? '',
    'course_program'        => trim($_POST['course_program'] ?? ''),
    'preferred_university'  => trim($_POST['preferred_university'] ?? ''),
    'highest_qualification' => $_POST['highest_qualification'] ?? '',
    'ielts_score'           => trim($_POST['ielts_score'] ?? ''),
    'annual_budget'         => $_POST['annual_budget'] ?? '',
    'visa_refusals'         => $_POST['visa_refusals'] ?? 'No',
    'refusal_details'       => trim($_POST['refusal_details'] ?? ''),
    'additional_notes'      => trim($_POST['additional_notes'] ?? ''),
];

// ── Server-side Validation ─────────────────────────────────────
$errors = [];

if (empty($data['full_name']))             $errors[] = 'Full name is required.';
if (empty($data['date_of_birth']))         $errors[] = 'Date of birth is required.';
if (empty($data['email']))                 $errors[] = 'Email address is required.';
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
if (empty($data['mobile']))                $errors[] = 'Mobile number is required.';
if (empty($data['city']))                  $errors[] = 'City is required.';
if (empty($data['passport_number']))       $errors[] = 'Passport number is required.';
if (empty($data['passport_expiry']))       $errors[] = 'Passport expiry date is required.';
if (empty($data['marital_status']))        $errors[] = 'Marital status is required.';
if (empty($data['destination_country']))   $errors[] = 'Destination country is required.';
if (empty($data['preferred_intake']))      $errors[] = 'Preferred intake is required.';
if (empty($data['course_program']))        $errors[] = 'Course / Program is required.';
if (empty($data['highest_qualification'])) $errors[] = 'Highest qualification is required.';
if (empty($data['annual_budget']))         $errors[] = 'Annual budget is required.';
if (empty($data['visa_refusals']))         $errors[] = 'Visa refusal answer is required.';
if ($data['visa_refusals'] === 'Yes' && empty($data['refusal_details']))
    $errors[] = 'Refusal details are required.';

// Date validations
if (!empty($data['date_of_birth'])) {
    $dob = strtotime($data['date_of_birth']);
    if ($dob >= time()) $errors[] = 'Date of birth cannot be today or in the future.';
}
if (!empty($data['passport_expiry'])) {
    $expiry  = strtotime($data['passport_expiry']);
    $minDate = strtotime('+18 months');
    if ($expiry < $minDate) $errors[] = 'Passport must be valid for at least 18 months.';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// ── Save / Update Draft first ──────────────────────────────────
try {
    $saveResult = $model->saveDraft($data, $_FILES);
    if (!$saveResult['success']) {
        echo json_encode($saveResult);
        exit;
    }

    $dbId = $saveResult['id'];

    // Check required documents after save
    $record = $model->getById($dbId);
    if (!$model->hasRequiredDocs($record)) {
        echo json_encode([
            'success' => false,
            'message' => 'Please upload all required documents (Passport, Photo, 10th, 12th Marksheet, Bank Statement).',
        ]);
        exit;
    }

    // Mark as Submitted
    $submitResult = $model->submitApplication($dbId);
    echo json_encode($submitResult);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
