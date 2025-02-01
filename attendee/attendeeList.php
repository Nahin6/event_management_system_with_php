<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = "View Events";
ob_start();
include '../config/database.php';

if (isset($_GET['id'])) {
    $event_id = (int) $_GET['id'];
    $user_id = $_SESSION['user']['id'];

    try {
        $sql = "SELECT name FROM events WHERE id = :event_id AND created_by = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            echo "<p class='text-danger'>Event not found.</p>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Error fetching event: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }
}
?>

<body>


    <div class="container mt-5">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success text-center">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <h2 class="text-center">Attendee List for <?= htmlspecialchars($event['name']) ?></h2>
        <div class="row mb-3 mx-auto">
            <div class="col-md-2">
                <select id="pagination-limit" class="form-select form-control">
                    <option selected disabled>Paginate event</option>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" id="search" class="form-control" placeholder="Search attendee with name or phone..">
            </div>

            <div class="col-md-2">
                <input type="date" id="start-date" class="form-control">
            </div>
            <div class="col-md-2">
                <input type="date" id="end-date" class="form-control">
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-3"> <button class="btn btn-primary " id="export-attendees">Export</button></div>
                    <div class="col-md-9">
                        <button class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#registerAttendeeModal">Register attendee</button>
                    </div>
                </div>

            </div>
        </div>
        <div id="no-attendees-message" class="text-center mt-4 text-info" style="display: none;"></div>

        <div id="attendee-container" class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="attendee-list"></tbody>
            </table>
        </div>

    </div>
    <div class="modal fade" id="registerAttendeeModal" tabindex="-1" aria-labelledby="registerAttendeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerAttendeeModalLabel">Register New Attendee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registerAttendeeForm">
                        <input type="hidden" name="event_id" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">

                        <div class="mb-3">
                            <label for="attendee_name" class="form-label">Attendee Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div id="name-error" class="error-text"></div>

                        </div>

                        <div class="mb-3">
                            <label for="attendee_phone" class="form-label">Phone</label>
                            <input type="number" class="form-control" id="phone" name="phone" required>
                            <div id="phone-error" class="error-text"></div>

                        </div>
                        <div class="mb-3">
                            <label for="attendee_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="attendee_email" name="email">
                        </div>

                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    $content = ob_get_clean();
    include '../includes/layout.php';
    ?>

    <script>
        var event_id = <?= isset($_GET['id']) ? $_GET['id'] : 'null'; ?>;
        document.addEventListener('DOMContentLoaded', function () {
            let currentPage = 1;
            let currentLimit = 10;
            let search = '';
            let startDate = '';
            let endDate = '';
            function loadAttendeeList(page, limit, search) {
                fetch(`controller/fetch.php?event_id=${event_id}&page=${page}&limit=${limit}&search=${encodeURIComponent(search)}&start_date=${startDate}&end_date=${endDate}`)
                    .then(response => response.text())
                    .then(data => {
                        let attendeeContainer = document.getElementById('attendee-container');
                        let attendeeList = document.getElementById('attendee-list');
                        let noAttendeesMessage = document.getElementById('no-attendees-message');

                        if (data.includes("No attendees")) {
                            attendeeContainer.style.display = "none";
                            noAttendeesMessage.innerHTML = data;
                            noAttendeesMessage.style.display = "block";
                        } else {
                            attendeeContainer.style.display = "block";
                            noAttendeesMessage.style.display = "none";
                            attendeeList.innerHTML = data;
                        }

                        attachPaginationEvents();
                    })
                    .catch(error => console.error('Error:', error));
            }

            function attachPaginationEvents() {
                document.querySelectorAll('.pagination-link').forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        let page = this.getAttribute('data-page');
                        currentPage = page;
                        loadAttendeeList(currentPage, currentLimit, search, startDate, endDate);

                    });
                });
            }


            document.getElementById('search').addEventListener('input', function () {
                search = this.value;
                currentPage = 1;
                loadAttendeeList(currentPage, currentLimit, search, startDate, endDate);

            });

            document.getElementById('pagination-limit').addEventListener('change', function () {
                currentLimit = this.value;
                currentPage = 1;
                loadAttendeeList(currentPage, currentLimit, search, startDate, endDate);

            });
            document.getElementById('export-attendees').addEventListener('click', function () {
                let event_id = <?= isset($_GET['id']) ? $_GET['id'] : 'null'; ?>;
                let search = document.getElementById('search').value;

                window.location.href = `controller/export.php?event_id=${event_id}&search=${encodeURIComponent(search)}`;
            });
            document.getElementById('start-date').addEventListener('change', function () {
                startDate = this.value;
                currentPage = 1;
                loadAttendeeList(currentPage, currentLimit, search, startDate, endDate);
            });

            document.getElementById('end-date').addEventListener('change', function () {
                endDate = this.value;
                currentPage = 1;
                loadAttendeeList(currentPage, currentLimit, search, startDate, endDate);
            });
            document.getElementById('registerAttendeeForm').addEventListener('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                fetch('controller/storeWithAjax.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(resp => {
                        if (resp.success) {
                            toastr.success(resp.message);
                            document.getElementById('registerAttendeeForm').reset();
                            loadAttendeeList(currentPage, currentLimit, search, startDate, endDate);
                            let modal = document.getElementById('registerAttendeeModal');
                            let bootstrapModal = bootstrap.Modal.getInstance(modal);
                            if (bootstrapModal) {
                                bootstrapModal.hide();
                            }
                        } else {
                            toastr.error('Error: ' + resp.message);
                        }
                    })
                    .catch(error => {
                        toastr.error('Error: ' + error);
                        console.log('Error: ' + error);

                    }
                    );
            });
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 3000
            };
            loadAttendeeList(currentPage, currentLimit, search, startDate, endDate);

        });

    </script>
</body>

</html>