<?php
include '../../config/database.php';
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=attendees_event_' . $event_id . '.csv');

$output = fopen('php://output', 'w');

fputcsv($output, ['Name', 'Email', 'Phone', 'Registered Date']);

$query = "SELECT  name, email, phone, registered_at FROM attendees WHERE event_id = ?";
$params = [$event_id];

if (!empty($search)) {
    $query .= " AND (name LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$stmt = $conn->prepare($query);
$stmt->execute($params);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $row['phone'] = "\t" . $row['phone']; // Prefix with tab to keep format
    fputcsv($output, $row);
}

fclose($output);
exit;
?>
