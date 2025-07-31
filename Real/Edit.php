 

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
$conn = new mysqli("sql200.infinityfree.com", "if0_39282857", "G6wDbAohtp4I", "if0_39282857_realestate");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


$users = [];
$user = null;
$message = '';

// Step 1: Search by phone OR plot
if (isset($_POST['search'])) {
  $search_value = $conn->real_escape_string($_POST['search_value']);
  $res = $conn->query("SELECT * FROM user_details WHERE phone = '$search_value' OR plot = '$search_value'");
  if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      $users[] = $row;
    }
  } else {
    $message = "<div class='alert alert-danger'>No records found for <strong>$search_value</strong>.</div>";
  }
}

// Step 2: Select user for editing
if (isset($_POST['select'])) {
  $id = (int) $_POST['user_id'];
  $res = $conn->query("SELECT * FROM user_details WHERE id = $id LIMIT 1");
  if ($res && $res->num_rows > 0) {
    $user = $res->fetch_assoc();
  } else {
    $message = "<div class='alert alert-danger'>User not found.</div>";
  }
}

// Step 3: Update status and time
if (isset($_POST['update'])) {
  $id = (int) $_POST['user_id'];
  $status = $conn->real_escape_string($_POST['status']);
  $status_time = $_POST['status_time'];
  $dt = DateTime::createFromFormat('Y-m-d\TH:i', $status_time);

  if ($dt) {
    $formatted = $dt->format('Y-m-d H:i:s');

    $updateSql = "UPDATE user_details SET status = ?, ";
    if ($status === 'Registered') {
      $updateSql .= "registered_date = ?";
    } elseif ($status === 'Booked') {
      $updateSql .= "booked_date = ?";
    } elseif ($status === 'Agreement') {
      $updateSql .= "agreement_date = ?";
    } else {
      $message = "<div class='alert alert-danger'>Invalid status selected.</div>";
    }

    $updateSql .= " WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ssi", $status, $formatted, $id);
    $stmt->execute();

    $message = "<div class='alert alert-success'>Status updated successfully.</div>";
  } else {
    $message = "<div class='alert alert-danger'>Invalid date/time format.</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Booking Status</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
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
            <a class="nav-link" href="User-data.php">User Submissions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="Edit.php">Edit Status</a>
          </li>
           <li class="nav-item ms-4">
               <a class="btn btn-outline-light" href="logout.php">Logout</a>
         </li>
        </ul>
    </div>
  </div>
</nav>


<div class="container mt-4">
  <h2 class="mb-4">Search Booking by Phone or Plot Number</h2>
  <form method="POST" class="row g-2 mb-3">
    <div class="col-12 col-md-8">
      <input type="text" name="search_value" class="form-control" placeholder="Enter Phone or Plot Number" required>
    </div>
    <div class="col-12 col-md-4">
      <button type="submit" name="search" class="btn btn-primary w-100">Search</button>
    </div>
  </form>

  <?= $message ?>

  <?php if (count($users) > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="table-secondary">
          <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Plot No</th>
            <th>Status</th>
            <th>Status Time</th>
            <th>Select</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><?= htmlspecialchars($u['name']) ?></td>
              <td><?= htmlspecialchars($u['phone']) ?></td>
              <td><?= htmlspecialchars($u['plot']) ?></td>
              <td><?= htmlspecialchars($u['status']) ?></td>
              <td><?= htmlspecialchars($u['registered_date'] ?: $u['booked_date'] ?: $u['agreement_date']) ?></td>
              <td>
                <form method="POST">
                  <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                  <button type="submit" name="select" class="btn btn-sm btn-warning">Edit</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <?php if ($user): ?>
    <div class="card mt-4">
      <div class="card-body">
        <h4>Update Status for <strong><?= htmlspecialchars($user['name']) ?></strong> (Plot: <?= htmlspecialchars($user['plot']) ?>)</h4>
        <form method="POST">
          <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
          <div class="mb-3">
            <label class="form-label">New Status</label>
            <select name="status" class="form-select" required>
              <option value="">Select Status</option>
              <option value="Registered">Registered</option>
              <option value="Booked">Booked</option>
              <option value="Agreement">Agreement</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Date and Time</label>
            <input type="datetime-local" name="status_time" class="form-control" required>
          </div>
          <button type="submit" name="update" class="btn btn-success">Update</button>
        </form>
      </div>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
