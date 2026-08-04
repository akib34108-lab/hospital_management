<?php
    require_once "../../component/connection.php";

        $result = $crud->common_insert("patient_admissions", $_POST);
        if ($result['status']) {
            $_SESSION['message'] = array('success','Success', $result['message']);
        } else {
            $_SESSION['message'] = array('danger','Error', $result['message']);
        }
        echo "<script>window.location.href = '" . $base_url . "ward/patients_addmission/admitted_patient_list.php';</script>";
?>  