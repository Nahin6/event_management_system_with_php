<?php
session_start();
include '../../config/database.php';

if (!isset($_SESSION['user'])) {
    echo "<p class='text-danger'>Unauthorized access.</p>";
    exit;
}

$user_id = $_SESSION['user']['id'];
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$offset = ($page - 1) * $limit;

try {
    $conditions = "WHERE events.created_by = :user_id";
    $params = [':user_id' => $user_id];

    if (!empty($search)) {
        $conditions .= " AND (events.name LIKE :search OR events.location LIKE :search)";
        $params[':search'] = "%$search%";
    }

    if (!empty($start_date)) {
        $conditions .= " AND DATE(events.created_at) >= :start_date";
        $params[':start_date'] = $start_date;
    }

    if (!empty($end_date)) {
        $conditions .= " AND DATE(events.created_at) <= :end_date";
        $params[':end_date'] = $end_date;
    }

    // Get total count of events
    $totalSql = "SELECT COUNT(*) FROM events $conditions";
    $totalStmt = $conn->prepare($totalSql);
    foreach ($params as $key => $value) {
        $totalStmt->bindValue($key, $value, PDO::PARAM_STR);
    }
    $totalStmt->execute();
    $totalEvents = $totalStmt->fetchColumn();
    $totalPages = ceil($totalEvents / $limit);

    $sql = "SELECT events.id, events.name, events.location, events.max_capacity,events.current_capacity, events.image, users.username AS created_by 
            FROM events 
            JOIN users ON events.created_by = users.id
            $conditions
            ORDER BY events.id DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($events)) {
        echo "<p class='text-center text-muted'>No events found.</p>";
    } else {
        foreach ($events as $index => $event) {
            echo "<tr>
                    <td>" . ($offset + $index + 1) . " </td>
                    <td>{$event['name']}</td>
                    <td>{$event['location']}</td>
                    <td>{$event['max_capacity']}</td>
                    <td>{$event['current_capacity']}</td>
                    <td><img src='./uploads/events/{$event['image']}' alt='Event Image' width='50'></td>
                    <td class='d-flex gap-2'>
                        <a href='edit.php?id={$event['id']}' class='btn btn-primary btn-sm'>Edit</a>
                        <a href='../attendee/attendeeList.php?id={$event['id']}' class='btn btn-primary btn-sm'>Attendee List</a>
                        <a href='../attendee/controller/export.php?event_id={$event['id']}' class='btn btn-success btn-sm'>  <i class='fa fa-download m-2'></i>Export attendee</a>
                        <a href='controller/delete.php?id={$event['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'>Delete</a>
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
    echo "<p class='text-danger'>Error fetching events: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
