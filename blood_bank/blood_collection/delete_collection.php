<?php
    require_once "../../component/connection.php";

    $result = $crud->common_delete("blood_collection", ['id' => $_GET['id']]);
    if ($result['status']) {
        $_SESSION['message'] = array('success','Success', $result['message']);
    } else {
        $_SESSION['message'] = array('danger','Error', $result['message']);
    }

    echo "<script>window.location.href = '".$base_url."blood_bank/blood_collection/collection.php';</script>";
?>  