<?php
    require_once "../../component/connection.php";

        $result = $crud->common_insert("admitted_patients", $_POST);
        if ($result['status']) {
            $_SESSION['message'] = array('success','Success', $result['message']);
        } else {
            $_SESSION['message'] = array('danger','Error', $result['message']);
        }
        echo "<script>window.location.href = '".$base_url."ward/admitted_patients/admitted_patients.php';</script>";
?>  