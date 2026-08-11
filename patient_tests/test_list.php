<?php include '../connection.php';?>
<h2>Patient Test List</h2>
<a href="test_create.php">Add New Test</a><br><br>
<table border="1" cellpadding="5">
<tr><th>ID</th><th>Patient ID</th><th>Test ID</th><th>Date</th><th>Status</th><th>Action</th></tr>
<?php
$result = $crud->common_select('patient_tests');
if($result['status']){
    foreach($result['data'] as $t){
        echo "<tr>
            <td>$t->id</td>
            <td>$t->patient_id</td>
            <td>$t->test_id</td>
            <td>$t->test_date</td>
            <td>$t->status</td>
            <td><a href='test_edit.php?id=$t->id'>Edit</a> | <a href='test_delete.php?id=$t->id'>Delete</a></td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No Data Found</td></tr>";
}
?>
</table>