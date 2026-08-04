<?php
    require_once "../../component/connection.php";

    $id = $_GET['id'];
    $result = $crud->common_update("patient_admissions", $_POST, ['id' => $id]);
    if ($result['status']) {
        $_SESSION['message'] = array('success','Success', $result['message']);
    } else {
        $_SESSION['message'] = array('danger','Error', $result['message']);
    }
    echo "<script>window.location.href = '".$base_url."ward/patients_addmission/admitted_patient_list.php';</script>";
?>  