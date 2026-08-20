<?php require_once "../component/header.php";?>
<?php require_once "../component/sidebar.php";?>
<?php $conn = $crud->conn;

$invoice_id = intval($_GET['id']);

// Invoice data
$invoice = $conn->query("SELECT i.*, p.name, p.phone, p.address FROM invoices i LEFT JOIN patients p ON i.patient_id=p.id WHERE i.id='$invoice_id'")->fetch_object();

// Hisab
$payable_amount = ($invoice->sub_amount - $invoice->discount) + $invoice->tax;
$grand_total = $payable_amount; 

// Payment gula
$paid_res = $conn->query("SELECT SUM(amount) as total_paid FROM payments WHERE invoice_id='$invoice_id'");
$paid_row = $paid_res->fetch_object();
$total_paid = $paid_row->total_paid ? $paid_row->total_paid : 0;
$due = $grand_total - $total_paid;
$payment_history = $conn->query("SELECT * FROM payments WHERE invoice_id='$invoice_id' ORDER BY payment_date DESC, id DESC");

// Payment Save
if(isset($_POST['save_payment'])){
    $amount = $_POST['amount'];
    $method = $_POST['payment_method'];
    $date = $_POST['payment_date'];
    $trx = $conn->real_escape_string($_POST['transaction_id']);
    $conn->query("INSERT INTO payments(invoice_id, amount, payment_method, payment_date, transaction_id) VALUES('$invoice_id', '$amount', '$method', '$date', '$trx')");
    echo "<script>alert('Payment Saved Successfully'); window.location='invoice_view.php?id=$invoice_id';</script>";
    exit;
}
?>

<div class="page-wrapper">
<div class="content">

    <div class="row">
        <div class="col-sm-6">
            <h4 class="page-title">Invoice #INV-<?php echo str_pad($invoice->id, 4, '0', STR_PAD_LEFT);?></h4>
        </div>
        <div class="col-sm-6 text-right">
            <a href="invoice_list.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back to List</a>
            <a href="invoices.php?id=<?php echo $invoice->id;?>" class="btn btn-info"><i class="fa fa-print"></i> Print</a>
        </div>
    </div>

    <!-- Patient + Invoice Info -->
    <div class="row">
        <div class="col-md-6">
            <div class="card-box">
                <h4 class="card-title">Patient Info</h4>
                <p><b>Name:</b> <?php echo $invoice->name;?></p>
                <p><b>Phone:</b> <?php echo $invoice->phone;?></p>
                <p><b>Address:</b> <?php echo $invoice->address;?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-box">
                <h4 class="card-title">Invoice Info</h4>
                <p><b>Date:</b> <?php echo date('d-m-Y', strtotime($invoice->invoice_date));?></p>
                <p><b>Sub Total:</b> <?php echo number_format($invoice->sub_amount,2);?> TK</p>
                <p><b>Discount:</b> <?php echo number_format($invoice->discount,2);?> TK</p>
                <p><b>Tax:</b> <?php echo number_format($invoice->tax,2);?> TK</p>
                <hr>
                <p style="font-size: 18px; color: #ff5722; font-weight:bold;"><b>Payable Amount:</b> <?php echo number_format($payable_amount,2);?> TK</p>
            </div>
        </div>
    </div>

    <!-- ========== PAYMENT SECTION START ========== -->
    <div class="row">
        <!-- Left: Add Payment Form -->
        <div class="col-md-6">
            <div class="card-box">
                <h4 class="card-title">Add Payment</h4>
                <?php if($due > 0){?>
                <form method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount *</label>
                                <input type="number" step="0.01" max="<?php echo $due;?>" name="amount" value="<?php echo $due;?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment Method *</label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="Cash">Cash</option>
                                    <option value="bKash">bKash</option>
                                    <option value="Nagad">Nagad</option>
                                    <option value="Card">Card</option>
                                    <option value="Bank">Bank</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment Date</label>
                                <input type="date" name="payment_date" value="<?php echo date('Y-m-d');?>" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>TRX ID</label>
                                <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="Auto for online payment" readonly>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="save_payment" class="btn btn-success"><i class="fa fa-money"></i> Save Payment</button>
                </form>
                <?php } else {?>
                    <div class="alert alert-success"><i class="fa fa-check"></i> This invoice is fully paid</div>
                <?php }?>
            </div>

            <!-- Payment Summary -->
            <div class="card-box">
                <h4 class="card-title">Payment Summary</h4>
                <table class="table table-bordered">
                    <tr>
                        <td><b>Grand Total</b></td>
                        <td class="text-right"><?php echo number_format($grand_total,2);?> TK</td>
                    </tr>
                    <tr>
                        <td><b>Total Paid</b></td>
                        <td class="text-right text-success"><?php echo number_format($total_paid,2);?> TK</td>
                    </tr>
                    <tr style="background:#ffecec;">
                        <td><b>Due Amount</b></td>
                        <td class="text-right text-danger"><b><?php echo number_format($due,2);?> TK</b></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Right: Payment History -->
        <div class="col-md-6">
            <div class="card-box">
                <h4 class="card-title">Payment History</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>TRX ID</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($payment_history && $payment_history->num_rows > 0){
                            while($ph = $payment_history->fetch_object()){?>
                            <tr>
                                <td><?php echo date('d-m-Y', strtotime($ph->payment_date));?></td>
                                <td class="text-success"><?php echo number_format($ph->amount,2);?></td>
                                <td><?php echo $ph->payment_method;?></td>
                                <td><?php echo!empty($ph->transaction_id)? $ph->transaction_id : 'N/A';?></td>
                            </tr>
                        <?php }
                        } else {?>
                            <tr><td colspan="4" class="text-center">No Payment Yet</td></tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- ========== PAYMENT SECTION END ========== -->

</div>
</div>

<script>
    // Auto TRX ID Generate
    function generateTRX(method) {

        if (method === 'Cash' || method === '') {
            return '';
        }

        const prefix = {
            bKash: 'BK',
            Nagad: 'NG',
            Card: 'CD',
            Bank: 'BN'
        }[method];

        const now = new Date();

        const ymd =
            now.getFullYear().toString().slice(-2) +
            ('0' + (now.getMonth() + 1)).slice(-2) +
            ('0' + now.getDate()).slice(-2);

        const rand = Math.floor(10000 + Math.random() * 90000);

        return prefix + ymd + rand;
    }


    document.addEventListener('DOMContentLoaded', function () {

        const paymentMethod = document.getElementById('payment_method');
        const transactionId = document.getElementById('transaction_id');

        // Page load এ auto TRX
        transactionId.value = generateTRX(paymentMethod.value);

        // Payment method change করলে auto TRX
        paymentMethod.addEventListener('change', function () {
            transactionId.value = generateTRX(this.value);
        });

    });
</script>

<?php require_once "../component/footer.php"?>