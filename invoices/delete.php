<?php
require_once "../component/connection.php";

$id = $_GET['id'];

$crud->conn->begin_transaction();

try {
    // 1. Invoice soft delete
    $result = $crud->common_update("invoices", ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
    if (!$result['status']) throw new Exception("Failed to delete invoice");

    // 2. Invoice er item gulao soft delete kore dite paro chaile
    // $crud->common_update("invoice_items", ['deleted_at' => date('Y-m-d H:i:s')], ['invoice_id' => $id]);

    $crud->conn->commit();
    $_SESSION['message'] = array('success', 'Success', 'Invoice deleted successfully!');

} catch (Exception $e) {
    $crud->conn->rollback();
    $_SESSION['message'] = array('danger', 'Error', $e->getMessage());
}

echo "<script>window.location.href = '". $base_url. "invoices/invoice_list.php';</script>"; // file name change