<?php

    require_once "../component/connection.php";

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

    $appointment_data = [
        'serial_no' => $_POST['serial_no'],
        'patient_id' => $_POST['patient_id'],
        'doctor_id' => $_POST['doctor_id'],
        'age' => $_POST['age'],
        'appointment_date' => $_POST['appointment_date'],
        'app_schedule_id' => $_POST['schedule_id'],
        'note' => $_POST['note'],
        'status' => $_POST['status'],
        'created_at' => date('Y-m-d H:i:s'),
    ];

        $result = $crud->common_insert("appointments", $appointment_data);
        if ($result['status']) {
            $_SESSION['message'] = array('success','Success', $result['message']);
        } else {
            $_SESSION['message'] = array('danger','Error', $result['message']);
        }

        echo "<script>window.location.href = '".$base_url."appointment/appointment_list.php';</script>";
    ?>
