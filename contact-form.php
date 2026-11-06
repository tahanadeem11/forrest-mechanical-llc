<?php
header('Content-Type: application/json; charset=utf-8');

function field($key) {
    $v = isset($_POST[$key]) ? trim((string) $_POST[$key]) : '';
    // strip control characters / line breaks to prevent header injection
    return htmlspecialchars(strip_tags($v), ENT_QUOTES, 'UTF-8');
}

$fname    = field('fname');
$phone    = field('phone');
$address  = field('address');
$service  = field('service');
$property = field('property');
$message  = field('msg');
$email    = filter_var(isset($_POST['email']) ? trim($_POST['email']) : '', FILTER_VALIDATE_EMAIL);

if ($fname === '' || $phone === '' || !$email || $message === '') {
    echo json_encode(array('status' => 'error', 'msg' => 'Please fill in all required fields with a valid email address.'));
    exit;
}

$to_email = 'forrestmechanicalhvac@gmail.com';
$subject  = 'New HVAC Service Request - Forrest Mechanical Website';
$host     = preg_replace('/[^a-z0-9.\-]/i', '', isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost');

$body = nl2br(
    "A new service request was submitted on " . $host . " (" . date('m-d-Y H:i') . ").\n\n" .
    "Name: $fname\n" .
    "Phone: $phone\n" .
    "Email: " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "\n" .
    "Service Address: $address\n" .
    "Service Needed: $service\n" .
    "Property Type: $property\n" .
    "Message: $message\n"
);

$headers  = "From: Forrest Mechanical Website <no-reply@" . $host . ">\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

if (@mail($to_email, $subject, $body, $headers)) {
    echo json_encode(array('status' => 'Success', 'msg' => 'Thank you, ' . $fname . '! Your request has been sent. We will contact you shortly.'));
} else {
    echo json_encode(array('status' => 'error', 'msg' => 'Sorry, your request could not be sent. Please call us at (970) 518-1261.'));
}
