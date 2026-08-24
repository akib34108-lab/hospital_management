<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>
<?php $conn = $crud->conn; ?>

<?php 
$edit_id = isset($_GET['edit_id']) ? $conn->real_escape_string($_GET['edit_id']) : '';
$edit_data = null;
$edit_items = [];
$lab_tests = $crud->common_select("lab_category","*")['data'];
$patients = $crud->common_select("patients","*")['data'];

if($edit_id != ''){
    $edit_data = $conn->query("SELECT * FROM invoices WHERE id='$edit_id'")->fetch_object();
    $edit_items_result = $conn->query("SELECT * FROM invoice_details WHERE invoice_id='$edit_id'");
    while($row = $edit_items_result->fetch_object()){ $edit_items[] = $row; }
}
?>

<div class="page-wrapper">
    <div class="content">
        
        <?php 
        if(isset($_POST['save'])){
            $patient_id = $conn->real_escape_string($_POST['patient_id']);
            $invoice_date = $conn->real_escape_string($_POST['invoice_date']);
            $status = 1;
            $invoice_id_post = $conn->real_escape_string($_POST['invoice_id']); 

            $sub_amount = $conn->real_escape_string($_POST['sub_amount']);
            $discount_tk = $conn->real_escape_string($_POST['discount_tk']); 
            $tax_tk = $conn->real_escape_string($_POST['tax_tk']); 
            $grand_total = $conn->real_escape_string($_POST['grand_total']);

            if($invoice_id_post != ''){ 
                $sql = "UPDATE `invoices` SET `patient_id`='$patient_id',`sub_amount`='$sub_amount',`discount`='$discount_tk',`tax`='$tax_tk',`invoice_date`='$invoice_date' WHERE id='$invoice_id_post'";
                $conn->query($sql);
                $last_id = $invoice_id_post;
                $conn->query("DELETE FROM invoice_details WHERE invoice_id='$last_id'");
            } else { 
                $sql = "INSERT INTO `invoices`(`patient_id`, `sub_amount`, `discount`, `tax`, `invoice_date`, `status`) 
                        VALUES ('$patient_id','$sub_amount','$discount_tk','$tax_tk','$invoice_date','$status')";
                $conn->query($sql);
                $last_id = $conn->insert_id;
            }

            if(isset($_POST['item_name'])){
                for($i=0; $i<count($_POST['item_name']); $i++){
                    if(!empty($_POST['item_name'][$i])){
                        $name = $conn->real_escape_string($_POST['item_name'][$i]);
                        $price = $conn->real_escape_string($_POST['item_price'][$i]);
                        $dis = $conn->real_escape_string($_POST['item_discount'][$i]); // %
                        $item_tax = $conn->real_escape_string($_POST['item_tax'][$i]); // %
                        $conn->query("INSERT INTO `invoice_details` 
                        (`invoice_id`, `Name`, `price`, `discount`, `tax`) 
                        VALUES ('$last_id','$name','$price','$dis','$item_tax')");
                    }
                }
            }
            $msg = $invoice_id_post != '' ? 'Invoice Updated Successfully' : 'Invoice Saved Successfully';
            echo "<script>alert('$msg'); window.location='invoice_list.php';</script>";
            exit;
        }

        if(isset($_GET['delete_id'])){
            $del_id = $_GET['delete_id'];
            $conn->query("DELETE FROM invoice_details WHERE invoice_id='$del_id'");
            $conn->query("DELETE FROM invoices WHERE id='$del_id'");
            echo "<script>alert('Invoice Deleted'); window.location='invoices.php';</script>";
        }

        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
            $inv = $conn->query("SELECT i.*, p.name as patient_name, p.address, p.phone 
                                 FROM invoices i 
                                 LEFT JOIN patients p ON i.patient_id = p.id 
                                 WHERE i.id='$id'")->fetch_object();
            $items = $conn->query("SELECT * FROM invoice_details WHERE invoice_id='$id' AND deleted_at IS NULL");
            
            // PAYMENT HISAB ADD KORLAM
            $paid_res = $conn->query("SELECT SUM(amount) as total_paid FROM payments WHERE invoice_id='$id'");
            $paid_row = $paid_res->fetch_object();
            $total_paid = $paid_row->total_paid ? $paid_row->total_paid : 0;
            $payable_amount = ($inv->sub_amount - $inv->discount) + $inv->tax;
            $due = $payable_amount - $total_paid;
            $grand_total = $payable_amount;
        ?>
        
        <!-- PRINT VIEW -->
        <div class="row no-print">
            <div class="col-sm-12 text-right m-b-20">
                <button onclick="window.print()" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
                <a href="invoices.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to List</a>
            </div>
        </div>

        <div class="row" id="print_area">
            <div class="col-md-12">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>SHIFA HOSPITAL</h3>
                            <p>Chittagong, Bangladesh<br>Phone: 01xxxxxxxxx</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <h4 class="text-blue">INVOICE</h4>
                            <p><b>Invoice No:</b> INV-<?php echo str_pad($inv->id, 4, '0', STR_PAD_LEFT); ?><br>
                               <b>Date:</b> <?php echo date('d-m-Y', strtotime($inv->invoice_date)); ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Bill To:</h5>
                            <p>
                                <b>Name:</b> <?php echo $inv->patient_name; ?><br>
                                <b>Phone:</b> <?php echo $inv->phone; ?><br>
                                <b>Address:</b> <?php echo $inv->address; ?>
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive m-t-20">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th><th>Item Name</th><th>Price</th><th>Disc %</th><th>Tax %</th><th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sl=1; while($row = $items->fetch_object()){ 
                                    $dis_tk = ($row->price * $row->discount) / 100;
                                    $tax_tk_item = (($row->price - $dis_tk) * $row->tax) / 100; // discount er por tax
                                    $item_total = ($row->price - $dis_tk) + $tax_tk_item; 
                                ?>
                                <tr>
                                    <td><?php echo $sl++; ?></td>
                                    <td><?php echo $row->Name; ?></td>
                                    <td><?php echo number_format($row->price, 2); ?></td>
                                    <td><?php echo $row->discount; ?>%</td>
                                    <td><?php echo $row->tax; ?>%</td>
                                    <td class="text-right"><?php echo number_format($item_total, 2); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table table-bordered">
                                <tr><td>Sub Amount:</td><td class="text-right"><?php echo number_format($inv->sub_amount, 2); ?> TK</td></tr>
                                <tr><td>Discount:</td><td class="text-right"><?php echo number_format($inv->discount, 2); ?> TK</td></tr>
                                <tr><td>TAX:</td><td class="text-right"><?php echo number_format($inv->tax, 2); ?> TK</td></tr>
                                <tr style="background:#f5f5f5;"><td><b>Payable Amount:</b></td><td class="text-right"><b><?php echo number_format($payable_amount, 2); ?> TK</b></td></tr>
                                <tr><td><b>Total Paid:</b></td><td class="text-right text-success"><b><?php echo number_format($total_paid, 2); ?> TK</b></td></tr>
                                <tr style="background:#ffecec;"><td><b>Due Amount:</b></td><td class="text-right text-danger"><b><?php echo number_format($due, 2); ?> TK</b></td></tr>
                            </table>
                        </div>
                    </div>

                    <div class="row" style="margin-top:50px;">
                        <div class="col-6">____________________ <br> Customer Signature</div>
                        <div class="col-6 text-right">____________________ <br> Authorized Signature</div>
                    </div>
                </div>
            </div>
        </div>

        <?php } else { ?>
        
        <!-- ADD/EDIT FORM -->
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="card-title"><?php echo $edit_id ? 'Diagnosis' : 'Add Invoice'; ?></h4> 
                    <form method="post" action="" id="invoiceForm">
                        <input type="hidden" name="invoice_id" value="<?php echo $edit_id; ?>"> 
                        
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Patient *</label>
                            <div class="col-md-4">
                                <select name="patient_id" id="patient_id" class="form-control" required>
                                    <option value="">-- Select Patient --</option>
                                    <?php foreach ($patients as $p){ 
                                        $selected = ($edit_data && $edit_data->patient_id == $p->id) ? 'selected' : ''; 
                                        echo "<option value='".$p->id."' data-discount='".$p->discount_percent."' $selected>".$p->name."</option>"; 
                                    } ?>
                                </select>
                            </div>
                            <label class="col-form-label col-md-2">Invoice Date *</label>
                            <div class="col-md-4">
                                <input type="date" name="invoice_date" value="<?php echo $edit_data ? $edit_data->invoice_date : date('Y-m-d');?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="card-box">
                            <h4 class="text-blue h4">Items</h4>
                            <button type="button" id="addItem" class="btn btn-primary mb-3">+ Add Item</button>
                            <div class="table-responsive">
                            <table class="table table-bordered" id="itemTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Price</th>
                                        <th>Discount %</th>
                                        <th>Tax %</th>
                                        <th>Total</th>
                                        <th width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($edit_items)){ 
                                        foreach($edit_items as $item){ ?>
                                        <tr>
                                            <td>
                                                <select name="item_name[]" class="form-control calc item_name">
                                                    <option value="">-- Select Test --</option>
                                                    <?php foreach($lab_tests as $t){ 
                                                        $selected = ($item->Name == $t->test_name) ? 'selected' : '';
                                                        echo "<option value='".$t->test_name."' data-price='".$t->price."' $selected>".$t->test_name."</option>"; 
                                                    } ?>
                                                </select>
                                            </td>
                                            <td><input type="number" name="item_price[]" value="<?php echo $item->price; ?>" class="form-control calc item_price"></td>
                                            <td><input type="number" name="item_discount[]" value="<?php echo $item->discount; ?>" class="form-control calc item_discount"></td>
                                            <td><input type="number" name="item_tax[]" value="<?php echo $item->tax; ?>" class="form-control calc item_tax"></td>
                                            <td><input type="number" name="item_total[]" class="form-control item_total" readonly></td>
                                            <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                                        </tr>
                                        <?php } 
                                    } else {  ?>
                                    <tr>
                                        <td>
                                            <select name="item_name[]" class="form-control calc item_name">
                                                <option value="">-- Select Test --</option>
                                                <?php foreach($lab_tests as $t){ 
                                                    echo "<option value='".$t->test_name."' data-price='".$t->price."'>".$t->test_name."</option>"; 
                                                } ?>
                                            </select>
                                        </td>
                                        <td><input type="number" name="item_price[]" class="form-control calc item_price" value="0"></td>
                                        <td><input type="number" name="item_discount[]" value="0" class="form-control calc item_discount"></td>
                                        <td><input type="number" name="item_tax[]" value="0" class="form-control calc item_tax"></td>
                                        <td><input type="number" name="item_total[]" class="form-control item_total" readonly></td>
                                        <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-bordered">
                                    <tr><td>Sub Amount</td><td><input type="number" id="sub_amount" name="sub_amount" class="form-control" readonly></td></tr>
                                    <tr><td>Discount TK</td><td><input type="number" id="discount_tk" name="discount_tk" class="form-control" readonly></td></tr>
                                    <tr><td>TAX TK</td><td><input type="number" id="tax_tk" name="tax_tk" class="form-control" readonly></td></tr>
                                    <tr><td><h5>Grand Total</h5></td><td><h5><input type="number" id="grand_total" name="grand_total" class="form-control" readonly></h5></td></tr>
                                </table>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 text-right">
                                <button type="submit" name="save" class="btn btn-success btn-lg"><?php echo $edit_id ? 'Update Invoice' : 'Save Invoice'; ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php } ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const patientId = document.getElementById('patient_id');
    const itemTable = document.getElementById('itemTable');
    const addItemBtn = document.getElementById('addItem');

    // Patient change
    patientId.addEventListener('change', function () {

        const selectedOption = this.options[this.selectedIndex];
        const patientDiscount = parseFloat(
            selectedOption.getAttribute('data-discount')
        ) || 0;

        document.querySelectorAll('.item_discount').forEach(function (input) {
            input.value = patientDiscount;
        });

        calculateTotal();
    });


    // Page load
    window.addEventListener('load', function () {

        const selectedOption = patientId.options[patientId.selectedIndex];

        const patientDiscount = parseFloat(
            selectedOption.getAttribute('data-discount')
        ) || 0;

        const firstDiscount = document.querySelector('.item_discount');

        if (
            patientDiscount > 0 &&
            firstDiscount &&
            parseFloat(firstDiscount.value) === 0
        ) {
            firstDiscount.value = patientDiscount;
        }

        calculateTotal();
    });


    // Item change
    itemTable.addEventListener('change', function (e) {

        if (e.target.classList.contains('item_name')) {

            const select = e.target;
            const selectedOption = select.options[select.selectedIndex];

            const price = parseFloat(
                selectedOption.getAttribute('data-price')
            ) || 0;

            const row = select.closest('tr');
            const priceInput = row.querySelector('.item_price');

            priceInput.value = price;

            calculateTotal();
        }
    });


    // Calculate Total
    function calculateTotal() {

        let sub_amount = 0;
        let total_discount_tk = 0;
        let total_tax_tk = 0;

        const rows = itemTable.querySelectorAll('tbody tr');

        rows.forEach(function (row) {

            const priceInput = row.querySelector('.item_price');
            const discountInput = row.querySelector('.item_discount');
            const taxInput = row.querySelector('.item_tax');
            const totalInput = row.querySelector('.item_total');

            const price = parseFloat(priceInput?.value) || 0;
            const dis_per = parseFloat(discountInput?.value) || 0;
            const tax_per = parseFloat(taxInput?.value) || 0;

            // Discount
            const dis_tk = (price * dis_per) / 100;

            // Discount এর পরে Tax
            const tax_tk = ((price - dis_tk) * tax_per) / 100;

            // Row total
            const total = (price - dis_tk) + tax_tk;

            if (totalInput) {
                totalInput.value = total.toFixed(2);
            }

            sub_amount += price;
            total_discount_tk += dis_tk;
            total_tax_tk += tax_tk;
        });


        // Grand Total
        const grand_total =
            sub_amount -
            total_discount_tk +
            total_tax_tk;


        document.getElementById('sub_amount').value =
            sub_amount.toFixed(2);

        document.getElementById('discount_tk').value =
            total_discount_tk.toFixed(2);

        document.getElementById('tax_tk').value =
            total_tax_tk.toFixed(2);

        document.getElementById('grand_total').value =
            grand_total.toFixed(2);
    }


    // Add Item
    addItemBtn.addEventListener('click', function () {

        const tbody = itemTable.querySelector('tbody');
        const firstRow = tbody.querySelector('tr:first-child');

        const newRow = firstRow.cloneNode(true);

        // সব input reset
        newRow.querySelectorAll('input').forEach(function (input) {
            input.value = '0';
        });

        // সব select reset
        newRow.querySelectorAll('select').forEach(function (select) {
            select.value = '';
        });


        // Current patient discount
        const selectedOption =
            patientId.options[patientId.selectedIndex];

        const patientDiscount = parseFloat(
            selectedOption.getAttribute('data-discount')
        ) || 0;

        const discountInput =
            newRow.querySelector('.item_discount');

        if (discountInput) {
            discountInput.value = patientDiscount;
        }


        tbody.appendChild(newRow);

        calculateTotal();
    });


    // Remove Item
    itemTable.addEventListener('click', function (e) {

        const removeButton = e.target.closest('.removeRow');

        if (!removeButton) {
            return;
        }

        const rows = itemTable.querySelectorAll('tbody tr');

        if (rows.length > 1) {
            removeButton.closest('tr').remove();
        }

        calculateTotal();
    });


    // Input change / keyup
    itemTable.addEventListener('input', function (e) {

        if (e.target.classList.contains('calc')) {
            calculateTotal();
        }
    });


    itemTable.addEventListener('change', function (e) {

        if (e.target.classList.contains('calc')) {
            calculateTotal();
        }
    });


    // Initial calculation
    calculateTotal();

});
</script>

<style>
@media print {
    body * { visibility: hidden; }
    #print_area, #print_area * { visibility: visible; }
    #print_area { position: absolute; left: 0; top: 0; width: 100%; }
    .no-print { display: none !important; }
    .sidebar, .header { display: none !important; }
}
</style>

<?php require_once "../component/footer.php" ?>