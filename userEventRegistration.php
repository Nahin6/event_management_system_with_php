<?php
include 'config/database.php';
include 'attendee/controller/AttendeeRegistration.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration = new AttendeeRegistration($conn, $_POST, 'userAjax');
    $registration->handleRequest();  
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}
