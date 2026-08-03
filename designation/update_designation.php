<?php
    require_once "../component/connection.php";

    $id = $_GET['id'];
    $result = $crud->common_update("designation", $_POST, ['id' => $id]);
    if ($result['status']) {
        $_SESSION['message'] = array('success','Success', $result['message']);
    } else {
        $_SESSION['message'] = array('danger','Error', $result['message']);
    }
    echo "<script>window.location.href = '".$base_url."designation/designation.php';</script>";
?>  