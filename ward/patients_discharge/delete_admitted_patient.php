<?php
    require_once "../../component/connection.php";

    $result = $crud->common_delete("patient_admissions", ['id' => $_GET['id']]);
    if ($result['status']) {
        $_SESSION['message'] = array('success','Success', $result['message']);
    } else {
        $_SESSION['message'] = array('danger','Error', $result['message']);
    }

    echo "<script>window.location.href = '".$base_url."ward/patients_addmission/admitted_patient_list.php';</script>";
?>  