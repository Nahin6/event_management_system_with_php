<?php
session_start();
include '../../config/database.php';

$event_id = isset($_GET['event_id']) ? (int) $_GET['event_id'] : 0;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$offset = ($page - 1) * $limit;

try {
    $params = [':event_id' => $event_id, ':limit' => $limit, ':offset' => $offset];

    $searchCondition = "";
    if (!empty($search)) {
        $searchCondition = "AND (name LIKE :search OR phone LIKE :search)";
        $params[':search'] = "%$search%"; 
    }

    $sql = "SELECT * FROM attendees WHERE event_id = :event_id $searchCondition ORDER BY id DESC LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, (strpos($key, 'limit') !== false || strpos($key, 'offset') !== false) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }

    $stmt->execute();
    $attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$attendees && empty($search)){
        echo "<h4 class='text-info text-center mt-4'>No attendees registered yet</h4>";
        exit; 
    }
    $countParams = [':event_id' => $event_id];
    if (!empty($search)) {
        $countParams[':search'] = "%$search%";
    }

    $countSql = "SELECT COUNT(*) as total FROM attendees WHERE event_id = :event_id $searchCondition";
    $countStmt = $conn->prepare($countSql);

    foreach ($countParams as $key => $value) {
        $countStmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $countStmt->execute();
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($total / $limit);

    // Display attendees
    foreach ($attendees as $index => $attendee) {
        echo "<tr>
                <td>" . ($offset + $index + 1) . "</td>
                <td>" . htmlspecialchars($attendee['name']) . "</td>
                <td>" . htmlspecialchars($attendee['phone']) . "</td>
                <td>" . htmlspecialchars($attendee['email']) . "</td>
                <td><a href='controller/delete.php?id={$attendee['id']}&event_id={$event_id}' class='btn btn-danger' onclick='return confirm(\"Are you sure?\");'>Delete</a></td>
              </tr>";
    }

    // Pagination
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
