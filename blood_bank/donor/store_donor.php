<?php
require_once "../../component/connection.php";

$data = $_POST;

// New donor is eligible by default
$data['donor_eligibility'] = 1;

// If no donation date is provided, keep it NULL
if (empty($data['last_donation'])) {
    $data['last_donation'] = null;
}

$result = $crud->common_insert("donor", $data);

if ($result['status']) {
    $_SESSION['message'] = array('success', 'Success', $result['message']);
} else {
    $_SESSION['message'] = array('danger', 'Error', $result['message']);
}

echo "<script>window.location.href = '".$base_url."blood_bank/donor/donor.php';</script>";
?> 