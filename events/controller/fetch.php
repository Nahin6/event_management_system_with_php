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
$offset = ($page - 1) * $limit;
try {
    $searchCondition = "";
    if (!empty($search)) {
        $searchCondition = "AND (events.name LIKE :search OR events.location LIKE :search)";
    }

    $totalSql = "SELECT COUNT(*) FROM events WHERE created_by = :user_id $searchCondition";
    $totalStmt = $conn->prepare($totalSql);
    $totalStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    if (!empty($search)) {
        $searchTerm = "%$search%";
        $totalStmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
    }
    $totalStmt->execute();
    $totalEvents = $totalStmt->fetchColumn();
    $totalPages = ceil($totalEvents / $limit);

    // Fetch events with pagination
    $sql = "SELECT events.id, events.name, events.location, events.max_capacity, users.username AS created_by, events.image
            FROM events 
            JOIN users ON events.created_by = users.id
             WHERE events.created_by = :user_id $searchCondition
            ORDER BY events.id DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    if (!empty($search)) {
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
    }
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($events)) {
        echo "<p class='text-center text-muted'>No events found.</p>";
    } else {
        foreach ($events as $index => $event) {
            echo "<tr>
                    <td>" . ( $offset+$index + 1) . " </td>
                    <td>{$event['name']}</td>
                    <td>{$event['location']}</td>
                    <td>{$event['max_capacity']}</td>
                
                    <td><img src='./uploads/events/{$event['image']}' alt='Event Image' width='50'></td>

                    <td class='d-flex gap-2'>
                        <a href='edit.php?id={$event['id']}' class='btn btn-primary btn-sm'>Edit</a>
                        <a href='../attendee/attendeeList.php?id={$event['id']}' class='btn btn-primary btn-sm'>Attendee List</a>
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