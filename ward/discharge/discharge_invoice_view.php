<?php

require_once "../../component/header.php";
require_once "../../component/sidebar.php";

$discharge_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($discharge_id <= 0) {
    echo "<script>window.location='discharge.php';</script>";
    exit;
}

// ==========================================
// GET DISCHARGE
// ==========================================

$discharge_result = $crud->common_select("discharges", "*", ["discharge_id" => $discharge_id]);

if (!$discharge_result["status"] || empty($discharge_result["data"])) {
    echo "<script>alert('Discharge record not found');window.location='discharge.php';</script>";
    exit;
}

$d = $discharge_result["data"][0];
$patient_id = $d->patient_id;
$admission_id = $d->admission_id;

// ==========================================
// GET INVOICE
// ==========================================

$invoice_result = $crud->common_select("discharge_invoices", "*", ["discharge_id" => $discharge_id]);

if (!$invoice_result["status"] || empty($invoice_result["data"])) {
    echo "<script>alert('Invoice not found');window.location='discharge_invoice.php?id=$discharge_id';</script>";
    exit;
}

$invoice = $invoice_result["data"][0];

// ==========================================
// CALCULATE PAYMENT AGAIN
// ==========================================

$total_amount = (float)$invoice->total_amount;
$paid_amount = (float)$invoice->paid_amount;

if ($total_amount < 0) {
    $total_amount = 0;
}

if ($paid_amount < 0) {
    $paid_amount = 0;
}

if ($paid_amount > $total_amount) {
    $paid_amount = $total_amount;
}

$due_amount = $total_amount - $paid_amount;

if ($due_amount < 0) {
    $due_amount = 0;
}

// ==========================================
// PAYMENT STATUS
// ==========================================

if ($total_amount == 0) {
    $payment_status = "Paid";
} elseif ($paid_amount == 0) {
    $payment_status = "Unpaid";
} elseif ($paid_amount < $total_amount) {
    $payment_status = "Partial";
} else {
    $payment_status = "Paid";
    $due_amount = 0;
}

// ==========================================
// GET ADMISSION
// ==========================================

$admission_result = $crud->common_select("patient_admissions", "*", ["id" => $admission_id]);

$room_number = "N/A";
$stay_days = 0;
$charge_per_day = 0;

if ($admission_result["status"] && !empty($admission_result["data"])) {
    $a = $admission_result["data"][0];
    $admission_date = $a->admission_date;
    $discharge_date = $a->discharge_date;

    if (!empty($admission_date) && !empty($discharge_date)) {
        $start = new DateTime($admission_date);
        $end = new DateTime($discharge_date);
        $stay_days = $start->diff($end)->days;

        if ($stay_days < 1) {
            $stay_days = 1;
        }
    }

    if (!empty($a->room_id)) {
        $room_result = $crud->common_select("rooms", "*", ["id" => $a->room_id]);

        if ($room_result["status"] && !empty($room_result["data"])) {
            $room = $room_result["data"][0];
            $room_number = $room->room_number;
            $charge_per_day = (float)$room->charge_per_day;
        }
    }
}

// ==========================================
// GET PATIENT
// ==========================================

$patient_name = "N/A";
$patient_phone = "N/A";

$patient_result = $crud->common_select("patients", "*", ["id" => $patient_id]);

if ($patient_result["status"] && !empty($patient_result["data"])) {
    $patient = $patient_result["data"][0];

    if (isset($patient->name)) {
        $patient_name = $patient->name;
    }

    if (isset($patient->phone)) {
        $patient_phone = $patient->phone;
    }
}
?>

<style>
.discharge-invoice-page {
    padding-bottom: 40px;
}

.invoice-box {
    background: #fff;
    border: 1px solid #ddd;
    padding: 30px;
    border-radius: 6px;
}

.invoice-header {
    border-bottom: 2px solid #ddd;
    padding-bottom: 20px;
    margin-bottom: 25px;
}

.invoice-title {
    font-size: 26px;
    font-weight: 600;
}

.invoice-subtitle {
    color: #777;
    margin-top: 5px;
}

.invoice-number {
    font-size: 18px;
    font-weight: 600;
}

.info-label {
    font-weight: 600;
    margin-bottom: 5px;
}

.info-value {
    color: #555;
}

.summary-table th {
    width: 70%;
}

.total-row {
    font-size: 18px;
    font-weight: 600;
}

.grand-total {
    font-size: 20px;
    font-weight: 700;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 13px;
    display: inline-block;
}

.status-paid {
    background: #e6f7ed;
    color: #198754;
}

.status-partial {
    background: #fff3cd;
    color: #856404;
}

.status-unpaid {
    background: #f8d7da;
    color: #842029;
}

@media print {
    .sidebar,
    .page-header,
    .no-print,
    footer {
        display: none !important;
    }

    .page-wrapper {
        margin-left: 0 !important;
    }

    .content {
        padding: 0 !important;
    }

    .invoice-box {
        border: none;
        padding: 0;
    }

    body {
        background: #fff !important;
    }
}
</style>

<div class="page-wrapper discharge-invoice-page">
    <div class="content">

        <!-- PAGE HEADER -->

        <div class="page-header no-print">
            <div class="page-title">
                <h4>Discharge Invoice</h4>
                <h6>Patient discharge billing invoice</h6>
            </div>

            <div class="page-btn">
                <a href="discharge.php" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i>
                    Back to Discharge
                </a>
            </div>
        </div>

        <!-- INVOICE -->

        <div class="invoice-box">

            <!-- HEADER -->

            <div class="invoice-header">
                <div class="row">

                    <div class="col-md-6">
                        <div class="invoice-title">
                            SHIFA Hospital
                        </div>

                        <div class="invoice-subtitle">
                            Hospital Management System
                        </div>
                    </div>

                    <div class="col-md-6 text-md-right">
                        <div class="invoice-number">
                            Invoice No:
                            <?php echo htmlspecialchars($invoice->invoice_no); ?>
                        </div>

                        <p class="mb-0">
                            Date:
                            <?php
                            if (isset($invoice->created_at) && !empty($invoice->created_at)) {
                                echo date("d M Y, h:i A", strtotime($invoice->created_at));
                            } else {
                                echo date("d M Y");
                            }
                            ?>
                        </p>
                    </div>

                </div>
            </div>

            <!-- PATIENT INFORMATION -->

            <div class="row mb-4">

                <div class="col-md-4 mb-3">
                    <div class="info-label">Patient ID</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($patient_id); ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-label">Patient Name</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($patient_name); ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-label">Patient Phone</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($patient_phone); ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-label">Admission ID</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($admission_id); ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-label">Room</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($room_number); ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-label">Stay Days</div>
                    <div class="info-value">
                        <?php echo (int)$stay_days; ?> Day(s)
                    </div>
                </div>

            </div>

            <!-- BILL DETAILS -->

            <div class="table-responsive">
                <table class="table table-bordered summary-table">

                    <thead>
                        <tr>
                            <th>Bill Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>Bed / Room Bill</td>
                            <td>
                                ৳ <?php echo number_format($invoice->bed_bill, 2); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Doctor Fee</td>
                            <td>
                                ৳ <?php echo number_format($invoice->doctor_fee, 2); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Test Bill</td>
                            <td>
                                ৳ <?php echo number_format($invoice->test_bill, 2); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Medicine Bill</td>
                            <td>
                                ৳ <?php echo number_format($invoice->medicine_bill, 2); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Service Bill</td>
                            <td>
                                ৳ <?php echo number_format($invoice->service_bill, 2); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Other Bill</td>
                            <td>
                                ৳ <?php echo number_format($invoice->other_bill, 2); ?>
                            </td>
                        </tr>

                        <tr>
                            <td>Discount</td>
                            <td>
                                - ৳ <?php echo number_format($invoice->discount, 2); ?>
                            </td>
                        </tr>

                        <tr class="total-row">
                            <th>Total Amount</th>
                            <th>
                                ৳ <?php echo number_format($total_amount, 2); ?>
                            </th>
                        </tr>

                        <tr>
                            <td>Paid Amount</td>
                            <td>
                                ৳ <?php echo number_format($paid_amount, 2); ?>
                            </td>
                        </tr>

                        <tr class="grand-total">
                            <th>Due Amount</th>
                            <th>
                                ৳ <?php echo number_format($due_amount, 2); ?>
                            </th>
                        </tr>

                    </tbody>

                </table>
            </div>

            <!-- PAYMENT INFORMATION -->

            <div class="row mt-4">

                <div class="col-md-6">
                    <div class="info-label">Payment Method</div>

                    <div class="info-value">
                        <?php echo htmlspecialchars($invoice->payment_method); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-label">Payment Status</div>

                    <div>
                        <?php
                        $status_class = "status-unpaid";

                        if ($payment_status == "Paid") {
                            $status_class = "status-paid";
                        } elseif ($payment_status == "Partial") {
                            $status_class = "status-partial";
                        }
                        ?>

                        <span class="status-badge <?php echo $status_class; ?>">
                            <?php echo htmlspecialchars($payment_status); ?>
                        </span>
                    </div>
                </div>

            </div>

            <!-- FOOTER -->

            <div class="row mt-5">

                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        Thank you for choosing SHIFA Hospital.
                    </p>
                </div>

                <div class="col-md-6 text-md-right">
                    <strong>Authorized Signature</strong>
                </div>

            </div>

            <!-- BUTTONS -->

            <div class="text-right mt-4 no-print">

                <button type="button" onclick="window.print()" class="btn btn-primary">
                    <i class="fa fa-print"></i>
                    Print Invoice
                </button>

                <a href="discharge_invoice.php?id=<?php echo $discharge_id; ?>" class="btn btn-warning">
                    <i class="fa fa-edit"></i>
                    Edit Invoice
                </a>

                <a href="discharge.php" class="btn btn-secondary">
                    Back
                </a>

            </div>

        </div>

    </div>
</div>

<?php require_once "../../component/footer.php"; ?>