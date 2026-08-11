<?php include '../connection.php';
$id = $_GET['id'];
// Soft delete korle
$delete = $crud->common_update('patient_tests', ['Deleted_at'=>date('Y-m-d H:i:s')], ['id'=>$id]);



echo $delete['message'];
echo "<br><a href='test_list.php'>Back to List</a>";
?>