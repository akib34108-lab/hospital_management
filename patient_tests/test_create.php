<?php include '../connection.php';?>
<!DOCTYPE html>
<html>
<head><title>Patient Test Add</title></head>
<body>
<h2>Patient Test Add</h2>
<form method="POST">
    <label>Patient ID:</label>
    <input type="number" name="patient_id" required><br><br>

    <label>Test ID:</label>
    <input type="number" name="test_id" required><br><br>

    <label>Test Date:</label>
    <input type="date" name="test_date"><br><br>

    <label>Status:</label>
    <select name="status">
        <option value="Pending">Pending</option>
        <option value="Completed">Completed</option>
    </select><br><br>

    <button type="submit" name="save">Save</button>
</form>

<?php
if(isset($_POST['save'])){
    $data = [
        'patient_id' => $_POST['patient_id'],
        'test_id' => $_POST['test_id'],
        'test_date' => $_POST['test_date'],
        'status' => $_POST['status'],
        'Created_by' => $_SESSION['user_id']?? 1
    ];
    $insert = $crud->common_insert('patient_tests', $data);
    if($insert['status']){
        echo "<p style='color:green'>".$insert['message']."</p>";
    } else {
        echo "<p style='color:red'>".$insert['message']."</p>";
    }
}
?>
</body>
</html>