<?php
class AttendeeRegistration
{
    private $conn;
    private $data;
    private $submitType;

    public function __construct($conn, $data, $submitType)
    {
        $this->conn = $conn;
        $this->data = $data;
        $this->submitType = $submitType;
    }

    public function handleRequest()
    {
        ob_start();
        try {
            $this->conn->beginTransaction();

            // Validate input
            $this->validateInput();

            $name = $this->data['name'];
            $phone = $this->data['phone'];
            $email = $this->data['email'] ?? null;
            $event_id = $this->data['event_id'];

            // Check event capacity
            $event = $this->getEvent($event_id);
            if (!$event) {
                throw new Exception("Event not found.");
            }

            if ($event['current_capacity'] >= $event['max_capacity']) {
                throw new Exception("Sorry, the event is fully booked!");
            }

            // Register attendee
            $this->registerAttendee($name, $phone, $email, $event_id);

            // Update event capacity
            $this->updateEventCapacity($event_id, $event['current_capacity'] + 1);

            $this->conn->commit();

            // Handle response based on request type
            if ($this->submitType == 'ajax') {
                ob_end_clean();
                header('Content-Type: application/json');
                echo json_encode(["success" => true, "message" => "Attendee registered successfully!"]);
                exit;
            } else {
                $this->sendResponse(true, "Attendee registered successfully!");
            }
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            if ($this->submitType == 'ajax') {

                ob_end_clean();
                header('Content-Type: application/json');
                echo json_encode(["success" => false, "message" => htmlspecialchars($e->getMessage())]);
                exit;
            } else {
                $this->sendResponse(false, $e->getMessage());
            }
        }
    }

    private function validateInput()
    {
        if (empty($this->data['name']) || empty($this->data['phone']) || empty($this->data['event_id'])) {
            throw new Exception("Required fields are missing.");
        }
    }

    private function getEvent($event_id)
    {
        $stmt = $this->conn->prepare("SELECT max_capacity, current_capacity FROM events WHERE id = :event_id");
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function registerAttendee($name, $phone, $email, $event_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO attendees (name, phone, email, event_id) VALUES (:name, :phone, :email, :event_id)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function updateEventCapacity($event_id, $new_capacity)
    {
        $stmt = $this->conn->prepare("UPDATE events SET current_capacity = :capacity WHERE id = :event_id");
        $stmt->bindParam(':capacity', $new_capacity, PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function sendResponse($success, $message)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($success) {
            $_SESSION['success'] = htmlspecialchars($message);
        } else {
            $_SESSION['error'] = htmlspecialchars($message);
        }
        header("Location: ../register.php");
        exit;
    }
}