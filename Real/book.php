<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
$conn = new mysqli("sql200.infinityfree.com", "if0_39282857", "G6wDbAohtp4I", "if0_39282857_realestate");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Plot Booking Form</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
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
            <a class="nav-link active" href="book.php">Booking Form</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="User-data.php">User Submissions</a>
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

  <!-- Main Form Section -->
  <div class="container mt-5">
    <h2 class="mb-4 text-center">Plot Registration / Booking Form</h2>

    <form id="userForm" class="p-4 bg-white shadow rounded">
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" required />
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="tel" class="form-control" id="phone" required />
      </div>

      <div class="mb-3">
        <label for="plot" class="form-label">Plot Number</label>
        <input type="text" class="form-control" id="plot" required />
      </div>

      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select" id="status" required>
          <option value="">Select status</option>
          <option value="Registered">Registered</option>
          <option value="Booked">Booked</option>
          <option value="Agreement">Agreement</option>
        </select>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>

    <div id="responseMsg" class="mt-3"></div>

    <div class="row mt-4 g-2">
      <div class="col-12 col-md-6 d-grid">
        <a href="User-data.php" class="btn btn-success">View Submissions</a>
      </div>
      <div class="col-12 col-md-6 d-grid">
        <a href="Edit.php" class="btn btn-warning">Edit User Status</a>
      </div>
    </div>
  </div>

  <!-- JS for Submissionsession_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
} -->
  <script>
    document.getElementById('userForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const name = document.getElementById('name').value;
      const phone = document.getElementById('phone').value;
      const plot = document.getElementById('plot').value;
      const status = document.getElementById('status').value;

      const res = await fetch('submit.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, phone, plot, status })
      });

      const result = await res.json();
      document.getElementById('responseMsg').innerHTML =
        `<div class="alert alert-success">${result.message}</div>`;
      document.getElementById('userForm').reset();
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
