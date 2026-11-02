<?php
/**
 * OrderPrintex - Submit Artwork & Quotation Enquiry Handler
 * Validates CSRF, File Upload, Dimensions & Stores in MySQL
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || 
          (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    } else {
        header('Location: ' . url(''));
    }
    exit;
}

// 1. CSRF Verification
if (!csrf_verify()) {
    $error = 'Security session expired. Please refresh the page and try again.';
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $error]);
        exit;
    }
    set_flash('error', $error);
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('get-quote.php')));
    exit;
}

// 2. Validate Inputs
$name     = trim($_POST['customer_name'] ?? '');
$email    = trim(strtolower($_POST['customer_email'] ?? ''));
$phone    = trim($_POST['customer_phone'] ?? '');
$service  = trim($_POST['service_name'] ?? '');
$width    = !empty($_POST['width_inches']) ? floatval($_POST['width_inches']) : null;
$height   = !empty($_POST['height_inches']) ? floatval($_POST['height_inches']) : null;
$quantity = !empty($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;
$material = trim($_POST['material_options'] ?? '');
$notes    = trim($_POST['customer_notes'] ?? '');

$currentUser = auth_user();
$userId = $currentUser ? $currentUser['id'] : null;

// Autofill if logged in
if ($currentUser) {
    if (empty($name)) $name = $currentUser['name'];
    if (empty($email)) $email = $currentUser['email'];
    if (empty($phone)) $phone = $currentUser['phone'] ?? '';
}

if (empty($name) || empty($email) || empty($phone)) {
    $error = 'Please provide your name, valid email address, and phone number.';
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $error]);
        exit;
    }
    set_flash('error', $error);
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('get-quote.php')));
    exit;
}

// 3. Handle File Upload if present
$fileResult = null;
if (!empty($_FILES['artwork_file']) && $_FILES['artwork_file']['error'] !== UPLOAD_ERR_NO_FILE) {
    $fileResult = handle_file_upload($_FILES['artwork_file'], ARTWORK_PATH);
    if (!$fileResult['success']) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $fileResult['error']]);
            exit;
        }
        set_flash('error', $fileResult['error']);
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('get-quote.php')));
        exit;
    }
}

// 4. Resolve service ID if available
$serviceId = null;
if ($service) {
    $svc = db_fetch_one("SELECT id, title FROM services WHERE slug = :s OR title = :t LIMIT 1", [
        's' => $service,
        't' => $service
    ]);
    if ($svc) {
        $serviceId = $svc['id'];
        $service = $svc['title'];
    }
}

// 5. Generate Unique Reference Number
$refNumber = generate_ref('OP-ENQ');

// 6. Insert Enquiry into Database
try {
    $enquiryId = db_insert('enquiries', [
        'ref_number'       => $refNumber,
        'user_id'          => $userId,
        'customer_name'    => $name,
        'customer_email'   => $email,
        'customer_phone'   => $phone,
        'service_id'       => $serviceId,
        'service_name'     => $service ?: 'General Printing',
        'width_inches'     => $width,
        'height_inches'    => $height,
        'quantity'         => $quantity,
        'material_options' => $material,
        'customer_notes'   => $notes,
        'status'           => 'received'
    ]);

    // Save File Reference
    if ($fileResult && $fileResult['success']) {
        db_insert('enquiry_files', [
            'enquiry_id'     => $enquiryId,
            'original_name'  => $fileResult['original_name'],
            'stored_name'    => $fileResult['stored_name'],
            'file_path'      => $fileResult['file_path'],
            'file_size'      => $fileResult['file_size'],
            'mime_type'      => $fileResult['mime_type'],
            'file_extension' => $fileResult['file_extension']
        ]);
    }

    log_activity($userId, 'submit_enquiry', "Created enquiry {$refNumber} for {$email}");

    $successMsg = "Your quotation enquiry has been submitted successfully! Your reference number is {$refNumber}. Our prepress studio will review your artwork and prepare your quotation.";

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode([
            'success'    => true,
            'ref_number' => $refNumber,
            'message'    => $successMsg
        ]);
        exit;
    }

    set_flash('success', $successMsg);
    // If logged in, go to enquiries page; otherwise return with flash
    if ($currentUser) {
        header('Location: ' . url('account/enquiries.php'));
    } else {
        header('Location: ' . url('contact.php?submitted=' . urlencode($refNumber)));
    }
    exit;

} catch (Exception $e) {
    $error = 'Database error while saving enquiry: ' . $e->getMessage();
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $error]);
        exit;
    }
    set_flash('error', $error);
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('get-quote.php')));
    exit;
}
