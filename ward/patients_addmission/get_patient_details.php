<?php
    require_once "../../component/connection.php";
    $phone = $_GET['phone'] ?? '';
    $result = $crud->common_select("patients", "*", ["phone" => $phone]);
    if ($result['status']) {
       $data = $result['data'][0];
       echo json_encode([
            "status" => true,
            "data" => $data,
        ]);
    } else {
       echo json_encode([
            "status" => false,
            "message" => "No patient details found"
        ]);
    }
       
?>  