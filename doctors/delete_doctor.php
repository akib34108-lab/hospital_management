<?php
    require_once "../component/connection.php";

    $result = $crud->common_delete("doctors", ['id' => $_GET['id']]);
    if ($result['status']) {
        $_SESSION['message'] = array('success','Success', $result['message']);
    } else {
        $_SESSION['message'] = array('danger','Error', $result['message']);
    }

    echo "<script>window.location.href = '".$base_url."doctors/doctors.php';</script>";
?>  