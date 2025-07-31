<?php
$conn = new mysqli("sql200.infinityfree.com", "if0_39282857", "G6wDbAohtp4I", "if0_39282857_realestate");
if ($conn->connect_error) {
  die(json_encode(["message" => "DB Connection failed"]));
}

$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'];
$phone = $data['phone'];
$plot = $data['plot'];
$status = $data['status'];

$registered_date = $booked_date = $agreement_date = null;
$dateNow = date("Y-m-d H:i:s");

if ($status === 'Registered') $registered_date = $dateNow;
if ($status === 'Booked') $booked_date = $dateNow;
if ($status === 'Agreement') $agreement_date = $dateNow;

$sql = "INSERT INTO user_details (name, phone, plot, status, registered_date, booked_date, agreement_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $name, $phone, $plot, $status, $registered_date, $booked_date, $agreement_date);
$stmt->execute();

echo json_encode(["message" => "Data submitted successfully"]);
?>
