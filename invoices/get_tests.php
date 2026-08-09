<?php
require_once "../component/connection.php";
header('Content-Type: application/json');

// patient_id ekhane lagbe na, karon sob test sobar jonno
$tests = $crud->common_query("SELECT id, test_name, price 
                                FROM `lab_category` 
                                WHERE `deleted_at` IS NULL 
                                ORDER BY `test_name` ASC");

if (!$tests['status'] || empty($tests['data'])) {
    $data = array('status' => false, 'message' => 'No tests found.', 'data' => []);
} else {
    // JS e jate data-price pawa jay tai key name same rakhlam
    $formatted_data = [];
    foreach($tests['data'] as $row){
        $formatted_data[] = [
            'id' => $row->id,
            'batch_name' => $row->test_name, // create.php te batch_name expect kore
            'Price' => $row->price // create.php te Price expect kore
        ];
    }
    $data = array('status' => true, 'message' => 'Tests found.', 'data' => $formatted_data);
}

echo json_encode($data);