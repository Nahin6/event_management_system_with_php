<?php
session_start();
include '../../config/database.php';

if (!isset($_SESSION['user'])) {
    echo "<p class='text-danger'>Unauthorized access.</p>";
    exit;
}

$user_id = $_SESSION['user']['id'];
$event_id = isset($_GET['event_id']) ? (int) $_GET['event_id'] : 0;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$offset = ($page - 1) * $limit;

try {
    // Basic filter conditions
    $conditions = "WHERE event_id = :event_id";
    $params = [':event_id' => $event_id];

    if (!empty($search)) {
        $conditions .= " AND (name LIKE :search OR phone LIKE :search)";
        $params[':search'] = "%$search%";
    }

    if (!empty($start_date)) {
        $conditions .= " AND DATE(registered_at) >= :start_date";
        $params[':start_date'] = $start_date;
    }

    if (!empty($end_date)) {
        $conditions .= " AND DATE(registered_at) <= :end_date";
        $params[':end_date'] = $end_date;
    }

    // Get total count of attendees
    $totalSql = "SELECT COUNT(*) FROM attendees $conditions";
    $totalStmt = $conn->prepare($totalSql);
    foreach ($params as $key => $value) {
        $totalStmt->bindValue($key, $value, PDO::PARAM_STR);
    }
    $totalStmt->execute();
    $totalAttendees = $totalStmt->fetchColumn();
    $totalPages = ceil($totalAttendees / $limit);

    $sql = "SELECT id, name, phone, email, registered_at
            FROM attendees
            $conditions
            ORDER BY id DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($attendees)) {
        echo "<p class='text-center text-muted'>No attendees found.</p>";
    } else {
        foreach ($attendees as $index => $attendee) {
            echo "<tr>
                    <td>" . ($offset + $index + 1) . "</td>
                    <td>{$attendee['name']}</td>
                    <td>{$attendee['phone']}</td>
                    <td>{$attendee['email']}</td>
                    <td class='d-flex gap-2'>
                       
                        <a href='controller/delete.php?id={$attendee['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                    </td>
                  </tr>";
        }
    }

    // Pagination Links
    if ($totalPages > 1) {
        echo "<nav class='mt-2'><ul class='pagination justify-content-center'>";
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($i == $page) ? 'active' : '';
            echo "<li class='page-item $activeClass'><a href='#' class='page-link pagination-link' data-page='$i'>$i</a></li>";
        }
        echo "</ul></nav>";
    }
} catch (PDOException $e) {
    echo "<p class='text-danger'>Error fetching attendees: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
