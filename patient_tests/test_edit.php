<?php include '../connection.php';
$id = $_GET['id'];
$result = $crud->common_select('patient_tests', '*', ['id'=>$id]);
$data = $result['data'][0];

if(isset($_POST['update'])){
    $update_data = [
        'result' => $_POST['result'],
        'status' => $_POST['status'],
        'Updated_by' => $_SESSION['user_id']?? 1
    ];
    $update = $crud->common_update('patient_tests', $update_data, ['id'=>$id]);
    echo $update['message'];
}
?>
<h2>Edit Test Result</h2>
<form method="POST">
    <label>Result:</label>
    <textarea name="result"><?=$data->result?></textarea><br><br>
    <label>Status:</label>
    <select name="status">
        <option <?=$data->status=='Pending'?'selected':''?>>Pending</option>
        <option <?=$data->status=='Completed'?'selected':''?>>Completed</option>
    </select><br><br>
    <button type="submit" name="update">Update</button>
</form>