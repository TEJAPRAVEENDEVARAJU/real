<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
$conn = new mysqli("sql200.infinityfree.com", "if0_39282857", "G6wDbAohtp4I", "if0_39282857_realestate");
$result = $conn->query("SELECT * FROM user_details ORDER BY id ");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Submissions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
  <style>
    .badge-Registered { background-color: #0d6efd; }
    .badge-Booked { background-color: #ffc107; color: black; }
    .badge-Agreement { background-color: #198754; }
    td.editable:hover { background-color: #e9ecef; cursor: pointer; }
  </style>
</head>
<body class="bg-light">
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">RealEstate Portal</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
     <ul class="navbar-nav ms-auto">
             <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " href="book.php">Booking Form</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="User-data.php">User Submissions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="Edit.php">Edit Status</a>
          </li>
           <li class="nav-item ms-4">
               <a class="btn btn-outline-light" href="logout.php">Logout</a>
         </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container mt-5">
    <h2 class="mb-4 text-center">User Submissions</h2>

  <!-- One Exact Match Filter -->
<div class="row mb-3">
  <div class="col-md-12">
    <input type="text" id="searchExact" class="form-control" placeholder="Search by Plot No or Phone No (Exact)">
  </div>
</div>


    <div class="table-responsive">
      <table id="userTable" class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Plot</th>
            <th>Status</th>
            <th>Registered Date</th>
            <th>Booked Date</th>
            <th>Agreement Date</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; while($row = $result->fetch_assoc()): ?>
          <tr data-id="<?= $row['id'] ?>">
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['plot']) ?></td>
            <td><span class="badge badge-pill badge-<?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
            <td><?= $row['registered_date'] ? date("d-m-Y h:i A", strtotime($row['registered_date'])) : '-' ?></td>
            <td><?= $row['booked_date'] ? date("d-m-Y h:i A", strtotime($row['booked_date'])) : '-' ?></td>
            <td><?= $row['agreement_date'] ? date("d-m-Y h:i A", strtotime($row['agreement_date'])) : '-' ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

  <script>
   $(document).ready(function () {
  var table = $('#userTable').DataTable({
    dom: 'Bfrtip',
    buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
    order: [[0, 'asc']]
  });

  // Custom exact match filter for phone OR plot using one input
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    const searchVal = $('#searchExact').val().trim();
    const phone = data[2].trim();
    const plot = data[3].trim();

    if (searchVal === "") return true;
    return searchVal === phone || searchVal === plot;
  });

  $('#searchExact').on('keyup change', function () {
    table.draw();
  });
});
  </script>
</body>
</html>
