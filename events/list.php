<?php
session_start();
$pageTitle = "View Events";
ob_start();
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
    <h2 class="text-center">Events List</h2>
    <div class="row mb-3 mx-auto">
      <div class="col-md-3 mt-1">
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
      <div class="col-md-3 mt-1">
        <input type="text" id="search" class="form-control" placeholder="Search events with name or location..">
      </div>
      <div class="col-md-3 mt-1">
        <input type="date" id="start-date" class="form-control">
      </div>
      <div class="col-md-3 mt-1">
        <input type="date" id="end-date" class="form-control">
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Location</th>
            <th>Capacity</th>
            <th>Enrolled</th>
            <th>Image</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="event-list"></tbody>
      </table>
    </div>

    <div id="pagination-links" class="text-center"></div>
  </div>

  <?php
  $content = ob_get_clean();
  include '../includes/layout.php';
  ?>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      let currentPage = 1;
      let currentLimit = 10;
      let search = '';
      let startDate = '';
      let endDate = '';

      function loadEvents(page, limit, search, startDate, endDate) {
        fetch(`controller/fetch.php?page=${page}&limit=${limit}&search=${encodeURIComponent(search)}&start_date=${startDate}&end_date=${endDate}`)
          .then(response => response.text())
          .then(data => {
            document.getElementById('event-list').innerHTML = data;
            attachPaginationEvents();
          });
      }

      function attachPaginationEvents() {
        document.querySelectorAll('.pagination-link').forEach(link => {
          link.addEventListener('click', function (e) {
            e.preventDefault();
            let page = this.getAttribute('data-page');
            currentPage = page;
            loadEvents(page, currentLimit, search, startDate, endDate);
          });
        });
      }

      document.getElementById('search').addEventListener('input', function () {
        search = this.value;
        currentPage = 1;
        loadEvents(currentPage, currentLimit, search, startDate, endDate);
      });

      document.getElementById('pagination-limit').addEventListener('change', function () {
        currentLimit = this.value;
        currentPage = 1;
        loadEvents(currentPage, currentLimit, search, startDate, endDate);
      });

      document.getElementById('start-date').addEventListener('change', function () {
        startDate = this.value;
        currentPage = 1;
        loadEvents(currentPage, currentLimit, search, startDate, endDate);
      });

      document.getElementById('end-date').addEventListener('change', function () {
        endDate = this.value;
        currentPage = 1;
        loadEvents(currentPage, currentLimit, search, startDate, endDate);
      });

      loadEvents(currentPage, currentLimit, search, startDate, endDate);
    });

  </script>
</body>

</html>