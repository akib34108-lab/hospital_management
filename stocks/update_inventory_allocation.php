<?php
require_once "../component/connection.php";

$id = isset($_POST['id']) && is_numeric($_POST['id']) ? (int) $_POST['id'] : 0;
$data = [
    'source_id' => $_POST['source_id'] ?? '',
    'source_type' => $_POST['source_type'] ?? '',
    'inventory_list_id' => $_POST['inventory_list_id'] ?? '',
    'qty' => $_POST['qty'] ?? '',
    'issue_date' => $_POST['issue_date'] ?? '',
    'return_date' => $_POST['return_date'] ?? null,
    'actual_return_date' => $_POST['actual_return_date'] ?? null
];

if ($id <= 0) {
    $result = ['status' => false, 'message' => 'Invalid inventory allocation.'];
} else {
    $result = $crud->common_update("inventory_transaction", $data, ['id' => $id]);
}

if ($result['status']) {
    $_SESSION['message'] = array('success', 'Success', $result['message']);
} else {
    $_SESSION['message'] = array('danger', 'Error', $result['message']);
}

echo "<script>window.location.href = '" . $base_url . "stocks/inventory_transactions.php';</script>";
?>
