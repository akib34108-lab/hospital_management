<?php 
require_once "../component/connection.php";

$conn = $crud->conn;
$id = $_GET['id'];

// Delete query
$sql = "DELETE FROM prescriptions WHERE id='$id'";

if($conn->query($sql)){
    echo "<script>alert('Prescription Deleted Successfully'); window.location='prescription_list.php';</script>";
} else {
    echo "<script>alert('Error: ".$conn->error."'); window.location='prescription_list.php';</script>";
}
?>