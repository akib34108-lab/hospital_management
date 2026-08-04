<?php
    require_once "../../component/connection.php";

    if(!$_POST['patient_id'] && $_POST['patient_id'] == ''){
        $patient=[
            'name'=>$_POST['name'],
            'phone'=>$_POST['phone'],
            'gender'=>$_POST['gender'],
            'age'=>$_POST['age'],
        ];
        $patient_result = $crud->common_insert("patients", $patient);
        if ($patient_result['status']) {
            $_POST['patient_id'] = $patient_result['data'];
        }
    }

    $admission_data = [
        'admission_no' => $_POST['admission_no'],
        'patient_id' => $_POST['patient_id'],
        'admission_date' => $_POST['admission_date'],
        'reason' => $_POST['reason'],
        'room_id' => $_POST['room_id'],
        'bed_id' => $_POST['bed_id'],
        'doctor_id' => $_POST['doctor_id']
    ];

    $result = $crud->common_insert("patient_admissions", $admission_data);
    if ($result['status']) {
        // Update the bed status to occupied
        $update_bed_status = $crud->common_update("beds", ["is_occupied" => 1], ["id" => $_POST['bed_id']]);
        
        $_SESSION['message'] = array('success','Success', $result['message']);
    } else {
        $_SESSION['message'] = array('danger','Error', $result['message']);
    }
    echo "<script>window.location.href = '" . $base_url . "ward/patients_addmission/admitted_patient_list.php';</script>";
?>  