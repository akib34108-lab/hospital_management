<?php require_once "../component/header.php";?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php";?>
<!-- Sidebar End -->

<?php
$id = $_GET['id'];
// Patient + Payment info niye aslam
$sql = "SELECT invoices.*, patients.name as patient_name, patients.discount_percent,
        payments.transaction_id, payments.payment_method, payments.amount as paid_amount
        FROM invoices
        JOIN patients ON invoices.patient_id = patients.id
        LEFT JOIN payments ON payments.invoice_id = invoices.id
        WHERE invoices.id = $id AND invoices.deleted_at IS NULL";
$data = $crud->common_query($sql);

if (!$data['status'] || empty($data['data'])) {
    $_SESSION['message'] = array('danger', 'Error', 'Invoice not found.');
    echo "<script>window.location.href = '". $base_url. "invoices/invoice_list.php';</script>";
    exit;
}
$invoice = $data['data'][0];

// Agger test gula niye aslam
$items_sql = "SELECT invoice_items.*, lab_category.test_name
              FROM invoice_items
              JOIN lab_category ON invoice_items.test_id = lab_category.id
              WHERE invoice_items.invoice_id = $id";
$items_data = $crud->common_query($items_sql);
?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-size-26 text-color-2">Edit Invoice #<?= $invoice->invoice_no?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <form action="<?= $base_url;?>invoices/update.php" method="POST" class="p-4" id="invoiceForm">
                    <input type="hidden" name="id" value="<?= $invoice->id?>">

                    <!-- Patient + Date -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="patient_id" class="form-label">Select Patient</label>
                            <select class="form-select" id="patient_id" name="patient_id" required>
                                <option value="">-- Select Patient --</option>
                                <?php
                                $patients = $crud->common_query("SELECT id, name, discount_percent FROM patients WHERE deleted_at IS NULL");
                                if ($patients['status']) {
                                    foreach ($patients['data'] as $patient) {
                                        $selected = ($patient->id == $invoice->patient_id)? 'selected' : '';
                                        echo "<option value='{$patient->id}' data-discount='{$patient->discount_percent}' {$selected}>{$patient->name}</option>";
                                    }
                                }
                              ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date</label>
                            <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="<?= $invoice->invoice_date?>" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="patient_discount" class="form-label">Patient Discount %</label>
                            <input type="number" step="0.01" class="form-control" id="patient_discount" name="patient_discount" value="<?= $invoice->discount_percent?>" readonly>
                        </div>
                    </div>

                    <!-- Tests Table -->
                    <h5 class="mt-4 mb-3">Tests</h5>
                    <div id="test-container">
                        <?php if($items_data['status'] &&!empty($items_data['data'])): $i=0; foreach($items_data['data'] as $item):?>
                        <div class="row test-row mb-2">
                            <div class="col-md-5">
                                <select name="test_id[]" class="form-select test-select" required>
                                    <option value="">-- Select Test --</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" name="price[]" class="form-control price" value="<?= $item->price?>" readonly>
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" name="vat_items[]" class="form-control vat" value="<?= $item->vat?>">
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" name="sub_total_items[]" class="form-control subtotal" value="<?= $item->sub_total?>" readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-test">X</button>
                            </div>
                        </div>
                        <?php $i++; endforeach; endif;?>
                    </div>
                    <button type="button" id="add-test" class="btn btn-sm btn-secondary mb-3">+ Add Test</button>

                    <!-- Total -->
                    <div class="row border-top pt-3">
                        <div class="col-md-3 mb-3">
                            <label>Sub Total</label>
                            <input type="number" step="0.01" id="sub_total" name="sub_total" class="form-control" value="<?= $invoice->sub_total?>" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Discount Amount</label>
                            <input type="number" step="0.01" id="discount_amount" name="discount_amount" class="form-control" value="<?= $invoice->discount_amount?>" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Total VAT</label>
                            <input type="number" step="0.01" id="total_vat" name="vat" class="form-control" value="<?= $invoice->vat?>" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Grand Total</label>
                            <input type="number" step="0.01" id="grand_total" name="grand_total" class="form-control" value="<?= $invoice->grand_total?>" readonly>
                        </div>
                    </div>

                    <!-- Payment -->
                    <h5 class="mt-4 mb-3">Payment</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Paid Amount</label>
                            <input type="number" step="0.01" id="paid_amount" name="paid_amount" class="form-control" value="<?= $invoice->paid_amount?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="0" <?= $invoice->payment_method==0?'selected':''?>>Bkash</option>
                                <option value="1" <?= $invoice->payment_method==1?'selected':''?>>Cash</option>
                                <option value="2" <?= $invoice->payment_method==2?'selected':''?>>Nagad</option>
                                <option value="3" <?= $invoice->payment_method==3?'selected':''?>>Card</option>
                                <option value="4" <?= $invoice->payment_method==4?'selected':''?>>Bank</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Transaction ID</label>
                            <input type="text" name="transaction_id" class="form-control" value="<?= $invoice->transaction_id?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Payment Status</label>
                            <select name="payment_status" class="form-select" required>
                                <option value="0" <?= $invoice->payment_status==0?'selected':''?>>Pending</option>
                                <option value="1" <?= $invoice->payment_status==1?'selected':''?>>Paid</option>
                                <option value="2" <?= $invoice->payment_status==2?'selected':''?>>Partial</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control"><?= $invoice->notes?></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Invoice</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// create.php er same JS ekhane bosbe. Patient change + Test load + hisab
// Shudhu page load e ager test gula load kore dibe
document.addEventListener('DOMContentLoaded', function() {
    let patientId = document.getElementById('patient_id').value;
    if(patientId) loadTests(patientId); // ager test gula load
});
</script>

<?php require_once "../component/footer.php";?>