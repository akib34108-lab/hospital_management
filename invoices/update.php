<?php
require_once "../component/connection.php";

$id = $_POST['id'];

$crud->conn->begin_transaction();

try {
    // 1. PATIENT ER DISCOUNT UPDATE
    $patient_discount = $_POST['patient_discount'] ?? 0;
    $crud->common_update("patients", ['discount_percent' => $patient_discount], ['id' => $_POST['patient_id']]);

    // 2. INVOICE MASTER UPDATE
    $invoices['patient_id'] = $_POST['patient_id']; // trainee_id -> patient_id
    $invoices['invoice_date'] = $_POST['invoice_date'];
    $invoices['sub_total'] = $_POST['sub_total'];
    $invoices['discount_amount'] = $_POST['discount_amount'] ?? 0;
    $invoices['discount_percent'] = $patient_discount; // discount_type er bodole
    $invoices['vat'] = $_POST['vat'] ?? 0;
    $invoices['grand_total'] = $_POST['grand_total'] ?? 0;
    $invoices['notes'] = $_POST['notes'] ?? '';
    $invoices['payment_status'] = $_POST['payment_status'];
    $invoices['paid_amount'] = $_POST['paid_amount'] ?? 0;
    $invoices['updated_by'] = $_SESSION['user_id'];

    $result = $crud->common_update("invoices", $invoices, ['id' => $id]);
    if (!$result['status']) throw new Exception("Failed to update invoice");

    // 3. PURAN TEST DELETE KORE NOTUN GULA INSERT
    $crud->common_delete("invoice_details", ['invoice_id' => $id]); // invoice_details -> invoice_details

    $test_ids = $_POST['test_id']; // array
    $prices = $_POST['price'];
    $vats = $_POST['vat_items']; // name change kore disi clash er jonno
    $sub_totals = $_POST['sub_total_items'];

    for ($i = 0; $i < count($test_ids); $i++) {
        if (empty($test_ids[$i])) continue;

        $details_data = [
            'invoice_id' => $id,
            'test_id' => $test_ids[$i], // batch_id -> test_id
            'price' => $prices[$i] ?? 0,
            'vat' => $vats[$i] ?? 0,
            'sub_total' => $sub_totals[$i] ?? 0,
            'updated_by' => $_SESSION['user_id']
        ];
        $detail_result = $crud->common_insert("invoice_details", $details_data);
        if (!$detail_result['status']) throw new Exception("Failed to update invoice details");
    }

    // 4. PAYMENT UPDATE / INSERT
    if ($_POST['paid_amount'] > 0) {
        // age payment ase kina check
        $pay_check = $crud->common_query("SELECT id FROM payments WHERE invoice_id = $id ORDER BY id DESC LIMIT 1");
        
        $payment_data = [
            'invoice_id' => $id,
            'amount' => $_POST['paid_amount'],
            'payment_date' => date('Y-m-d'),
            'payment_method' => $_POST['payment_method'] ?? 0,
            'payment_status' => ($_POST['paid_amount'] >= $_POST['grand_total']) ? 1 : 2,
            'transaction_id' => $_POST['transaction_id'] ?? null,
            'updated_by' => $_SESSION['user_id']
        ];

        if ($pay_check['status'] && !empty($pay_check['data'])) {
            $crud->common_update("payments", $payment_data, ['id' => $pay_check['data'][0]->id]);
        } else {
            $crud->common_insert("payments", $payment_data);
        }
    }

    $crud->conn->commit();
    $_SESSION['message'] = array('success', 'Success', 'Invoice updated successfully!');

} catch (Exception $e) {
    $crud->conn->rollback();
    $_SESSION['message'] = array('danger', 'Error', $e->getMessage());
}

echo "<script>window.location.href = '". $base_url. "invoices/invoice_list.php';</script>"; // file name