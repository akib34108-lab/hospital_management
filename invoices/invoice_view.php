<?php require_once "../component/header.php";?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php";?>
<!-- Sidebar End -->

<?php
$id = $_GET['id'];
// trainee_id -> patient_id, trainees -> patients, transaction_id add
$sql = "SELECT invoices.*, patients.name as patient_name, patients.discount_percent,
        payments.transaction_id, payments.payment_method, payments.amount as paid_amount
        FROM invoices
        JOIN patients ON invoices.patient_id = patients.id
        LEFT JOIN payments ON payments.invoice_id = invoices.id
        WHERE invoices.id = $id AND invoices.deleted_at IS NULL";

$data = $crud->common_query($sql);

if (!$data['status'] || empty($data['data'])) {
    $_SESSION['message'] = array('danger', 'Error', 'Invoice not found.');
    echo "<script>window.location.href = '". $base_url. "invoices/invoice_list.php';</script>"; // file name change
    exit;
}
$invoice = $data['data'][0];

$invoice_date = date('d-m-Y', strtotime($invoice->invoice_date));
?>

<div class="main-content">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-column flex-md-row flex-lg-row mt-3">
                <div class="flex-grow-1">
                    <h3 class="mb-2 text-size-26 text-color-2">Invoice #<?= $invoice->invoice_no?></h3>
                </div>
                <div class="mt-3 mt-lg-0 no-print">
                    <a href="<?= $base_url;?>invoices/invoice_list.php" class="btn btn-secondary"> <!-- file name change -->
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                    <button onclick="window.print()" class="btn btn-success ms-2">
                        <i class="fa-solid fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body p-4">

            <div class="row border-bottom pb-3 mb-4">
                <div class="col-md-6">
                    <h4 class="fw-bold text-primary mb-1">Shifa Hospital & Diagnostic</h4> <!-- Hospital Name -->
                    <p class="mb-0 text-muted">Chittagong, Bangladesh</p>
                    <p class="mb-0 text-muted">Phone: +880 18XXXXXXXX</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5 class="mb-1">Invoice #: <span class="fw-bold"><?= $invoice->invoice_no?></span></h5>
                    <p class="mb-0 text-muted">Date: <?= $invoice_date?></p>
                    <p class="mb-0 text-muted">Patient Discount: <span class="fw-bold"><?= number_format($invoice->discount_percent, 2)?> %</span></p> <!-- Notun -->
                    <p class="mb-0 text-muted">Status:
                        <?php
                        if ($invoice->payment_status == 0) { echo '<span class="badge bg-warning text-dark">Pending</span>'; }
                        elseif ($invoice->payment_status == 1) { echo '<span class="badge bg-success">Paid</span>'; }
                        elseif ($invoice->payment_status == 2) { echo '<span class="badge bg-info">Partial</span>'; }
                       ?>
                    </p>
                </div>
            </div>

            <!-- Bill To (Patient Info) -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="fw-bold text-uppercase text-muted mb-2">Bill To:</h6>
                    <h5 class="fw-bold"><?= $invoice->patient_name?></h5> <!-- trainee_name -> patient_name -->
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="fw-bold text-uppercase text-muted mb-2">Payment Info:</h6>
                    <p class="mb-1"><strong>Transaction ID:</strong> <?= htmlspecialchars($invoice->transaction_id?? 'N/A')?></p>
                    <p class="mb-1"><strong>Payment Method:</strong>
                        <?php
                        $method = $invoice->payment_method?? 0;
                        if($method == 0) echo 'Bkash';
                        elseif($method == 1) echo 'Cash';
                        elseif($method == 2) echo 'Nagad';
                        elseif($method == 3) echo 'Card';
                        elseif($method == 4) echo 'Bank';
                        else echo 'N/A';
                       ?>
                    </p>
                </div>
            </div>

            <div class="w-100 mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Test Name</th> <!-- Batch -> Test Name -->
                            <th>Price</th>
                            <th>VAT</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        // invoice_details -> invoice_detais, batch_id -> test_id, batches -> lab_category
                        $details_sql = "SELECT invoice_details.*, lab_category.test_name
                                        FROM invoice_details
                                        JOIN lab_category ON invoice_details.test_id = lab_category.id
                                        WHERE invoice_details.invoice_id = $id";
                        $details = $crud->common_query($details_sql);

                        if ($details['status'] &&!empty($details['data'])) {
                            $i = 1;
                            foreach ($details['data'] as $detail) {
                       ?>
                        <tr>
                            <td><?= $i++;?></td>
                            <td><?= $detail->test_name?></td>
                            <td><?= number_format($detail->price, 2)?></td>
                            <td><?= number_format($detail->vat, 2)?></td>
                            <td class="text-end fw-bold"><?= number_format($detail->sub_total, 2)?></td>
                        </tr>
                        <?php
                            }
                        }
                       ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Sub Total</th>
                            <td class="text-end fw-bold"><?= number_format($invoice->sub_total, 2)?></td>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Discount (<?= number_format($invoice->discount_percent, 2)?> %)</th> <!-- % show -->
                            <td class="text-end fw-bold">- <?= number_format($invoice->discount_amount, 2)?></td>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">VAT</th>
                            <td class="text-end fw-bold">+ <?= number_format($invoice->vat, 2)?></td>
                        </tr>
                        <tr class="table-active">
                            <th colspan="4" class="text-end fs-5">Grand Total</th>
                            <td class="text-end fs-5 fw-bold"><?= number_format($invoice->grand_total, 2)?> BDT</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Payment & Notes -->
            <div class="row border-top pt-3">
                <div class="col-md-6">
                    <h6 class="fw-bold text-uppercase text-muted mb-1">Notes:</h6>
                    <p class="text-muted"><?= htmlspecialchars($invoice->notes?? 'No notes')?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="fw-bold text-uppercase text-muted mb-1">Payment Summary:</h6>
                    <p class="mb-1"><strong>Paid Amount:</strong> <?= number_format($invoice->paid_amount, 2)?> BDT</p>
                    <p class="mb-1"><strong>Due Amount:</strong> <?= number_format(($invoice->grand_total - $invoice->paid_amount), 2)?> BDT</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4 pt-3 border-top">
                <p class="text-muted mb-0">Thank you for choosing Shifa Hospital & Diagnostic!</p> <!-- Hospital Name -->
            </div>
        </div>
    </div>
</div>

<?php require_once "../component/footer.php";?>