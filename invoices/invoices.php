<?php require_once "../component/header.php"; ?>
<?php require_once "../component/sidebar.php"; ?>
<?php $conn = $crud->conn; ?>

<div class="page-wrapper">
    <div class="content">
        
        <?php 
        // 1. SAVE KORAR CODE
        if(isset($_POST['save'])){
            $patient_id = $conn->real_escape_string($_POST['patient_id']);
            $invoice_date = $conn->real_escape_string($_POST['invoice_date']);
            $status = 1;

            $sub_amount = 0;
            if(isset($_POST['item_total'])) foreach($_POST['item_total'] as $it) $sub_amount += $it;
            
            $discount = $conn->real_escape_string($_POST['discount']);
            $vat = $conn->real_escape_string($_POST['vat']);

            $sql = "INSERT INTO `invoices`(`patient_id`, `sub_amount`, `discount`, `vat`, `invoice_date`, `status`) 
                    VALUES ('$patient_id','$sub_amount','$discount','$vat','$invoice_date','$status')";

            if($conn->query($sql)){
                $last_id = $conn->insert_id;
                if(isset($_POST['item_name'])){
                    for($i=0; $i<count($_POST['item_name']); $i++){
                        if(!empty($_POST['item_name'][$i])){
                            $name = $conn->real_escape_string($_POST['item_name'][$i]);
                            $price = $conn->real_escape_string($_POST['item_price'][$i]);
                            $dis = $conn->real_escape_string($_POST['item_discount'][$i]);
                            $tax = $conn->real_escape_string($_POST['item_tax'][$i]);
                            $conn->query("INSERT INTO `invoice_details` 
                            (`invoice_id`, `Name`, `price`, `discount`, `tax`) 
                            VALUES ('$last_id','$name','$price','$dis','$tax')");
                        }
                    }
                }
                echo "<script>alert('Invoice Saved Successfully'); window.location='invoice.php';</script>";
                exit;
            } else {
                echo "<div class='alert alert-danger'>DB Error: ".$conn->error."</div>";
            }
        }

        // 2. DELETE KORAR CODE
        if(isset($_GET['delete_id'])){
            $del_id = $_GET['delete_id'];
            $conn->query("DELETE FROM invoice_details WHERE invoice_id='$del_id'");
            $conn->query("DELETE FROM invoices WHERE id='$del_id'");
            echo "<script>alert('Invoice Deleted'); window.location='invoice.php';</script>";
        }

        // 3. JODI ID THAKE TAHOLE SINGLE INVOICE DEKHABE
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
            $inv = $conn->query("SELECT i.*, p.name as patient_name, p.address, p.phone 
                                 FROM invoices i 
                                 LEFT JOIN patients p ON i.patient_id = p.id 
                                 WHERE i.id='$id'")->fetch_object();
            $items = $conn->query("SELECT * FROM invoice_details WHERE invoice_id='$id'");
            $grand_total = ($inv->sub_amount - $inv->discount) + $inv->vat;
        ?>
        
        <!-- SINGLE INVOICE VIEW / PRINT -->
        <div class="row">
            <div class="col-sm-12 text-right m-b-20">
                <button onclick="window.print()" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
                <a href="invoice.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to List</a>
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
                            <p><b>Invoice No:</b> INV-<?php echo $inv->id; ?><br>
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
                                    <th>#</th><th>Item Name</th><th>Price</th><th>Discount</th><th>Tax</th><th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sl=1; while($row = $items->fetch_object()){ $item_total = ($row->price - $row->discount) + $row->tax; ?>
                                <tr>
                                    <td><?php echo $sl++; ?></td>
                                    <td><?php echo $row->Name; ?></td>
                                    <td><?php echo number_format($row->price, 2); ?></td>
                                    <td><?php echo number_format($row->discount, 2); ?></td>
                                    <td><?php echo number_format($row->tax, 2); ?></td>
                                    <td class="text-right"><?php echo number_format($item_total, 2); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table">
                                <tr><td>Sub Amount:</td><td class="text-right"><?php echo number_format($inv->sub_amount, 2); ?></td></tr>
                                <tr><td>Discount:</td><td class="text-right"><?php echo number_format($inv->discount, 2); ?></td></tr>
                                <tr><td>VAT:</td><td class="text-right"><?php echo number_format($inv->vat, 2); ?></td></tr>
                                <tr><td><h4>Grand Total:</h4></td><td class="text-right"><h4><?php echo number_format($grand_total, 2); ?> TK</h4></td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php } else { ?>
        
        <!-- SECTION 1: ADD INVOICE FORM -->
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="card-title">Add Invoice</h4>
                    <form method="post" action="" id="invoiceForm">
                        
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Patient *</label>
                            <div class="col-md-4">
                                <select name="patient_id" class="form-control" required>
                                    <option value="">-- Select Patient --</option>
                                    <?php $patients = $crud->common_select("patients","*"); if($patients['status']) foreach ($patients['data'] as $p) echo "<option value='".$p->id."'>".$p->name."</option>"; ?>
                                </select>
                            </div>
                            <label class="col-form-label col-md-2">Invoice Date *</label>
                            <div class="col-md-4">
                                <input type="date" name="invoice_date" value="<?php echo date('Y-m-d');?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="card-box">
                            <h4 class="text-blue h4">Items</h4>
                            <button type="button" id="addItem" class="btn btn-primary mb-3">+ Add Item</button>
                            <div class="table-responsive">
                            <table class="table table-bordered" id="itemTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Item Name</th><th>Price</th><th>Discount</th><th>Tax</th><th>Total</th><th width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="item_name[]" class="form-control" placeholder="Napa, CBC Test"></td>
                                        <td><input type="number" name="item_price[]" class="form-control calc item_price"></td>
                                        <td><input type="number" name="item_discount[]" value="0" class="form-control calc item_discount"></td>
                                        <td><input type="number" name="item_tax[]" value="0" class="form-control calc item_tax"></td>
                                        <td><input type="number" name="item_total[]" class="form-control item_total" readonly></td>
                                        <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-bordered">
                                    <tr><td>Sub Amount</td><td><input type="number" id="sub_amount" name="sub_amount" class="form-control" readonly></td></tr>
                                    <tr><td>Discount</td><td><input type="number" name="discount" id="discount" value="0" class="form-control calc"></td></tr>
                                    <tr><td>VAT</td><td><input type="number" name="vat" id="vat" value="0" class="form-control calc"></td></tr>
                                    <tr><td><h5>Grand Total</h5></td><td><h5><input type="number" id="grand_total" class="form-control" readonly></h5></td></tr>
                                </table>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 text-right">
                                <button type="submit" name="save" class="btn btn-success btn-lg">Save Invoice</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECTION 2: INVOICE DETAILS BUTTON + LIST -->
        <div class="row">
            <div class="col-sm-12 text-right m-b-20">
                <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#invoiceDetailsList">
                    <i class="fa fa-list"></i> Invoice Details
                </button>
            </div>
        </div>

        <div class="collapse" id="invoiceDetailsList">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <h4 class="page-title">Invoice List</h4>
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Invoice ID</th><th>Patient Name</th><th>Invoice Date</th>
                                        <th>Sub Amount</th><th>Discount</th><th>VAT</th><th>Grand Total</th><th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sl=1;
                                    $result = $conn->query("SELECT i.*, p.name as patient_name FROM invoices i 
                                                            LEFT JOIN patients p ON i.patient_id = p.id 
                                                            WHERE i.status=1 ORDER BY i.id DESC");
                                    while($row = $result->fetch_object()){ 
                                        $grand_total = ($row->sub_amount - $row->discount) + $row->vat;
                                    ?>
                                    <tr>
                                        <td><?php echo $sl++; ?></td>
                                        <td>INV-<?php echo $row->id; ?></td>
                                        <td><?php echo $row->patient_name; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($row->invoice_date)); ?></td>
                                        <td><?php echo number_format($row->sub_amount, 2); ?></td>
                                        <td><?php echo number_format($row->discount, 2); ?></td>
                                        <td><?php echo number_format($row->vat, 2); ?></td>
                                        <td><b><?php echo number_format($grand_total, 2); ?></b></td>
                                        <td class="text-right">
                                            <a href="invoice.php?id=<?php echo $row->id; ?>" class="btn btn-sm btn-primary">View/Print</a>
                                            <a href="invoice.php?delete_id=<?php echo $row->id; ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script>
$(document).ready(function(){
    $("#addItem").click(function(){ $("#itemTable tbody").append($("#itemTable tbody tr:first").clone().find("input").val('').end()); });
    $(document).on('click', '.removeRow', function(){ if($("#itemTable tbody tr").length > 1){ $(this).closest('tr').remove(); calculateTotal(); } });

    $(document).on('keyup change', '.calc', function(){ calculateTotal(); });
    function calculateTotal(){
        let sub_amount = 0;
        $("#itemTable tbody tr").each(function(){
            let row = $(this);
            let price = parseFloat(row.find('.item_price').val()) || 0;
            let dis = parseFloat(row.find('.item_discount').val()) || 0;
            let tax = parseFloat(row.find('.item_tax').val()) || 0;
            let total = (price - dis) + tax;
            row.find('.item_total').val(total.toFixed(2));
            sub_amount += total;
        });
        $("#sub_amount").val(sub_amount.toFixed(2));
        let discount = parseFloat($("#discount").val()) || 0;
        let vat = parseFloat($("#vat").val()) || 0;
        let grand_total = (sub_amount - discount) + vat;
        $("#grand_total").val(grand_total.toFixed(2));
    }
    calculateTotal();
});
</script>

<style>
@media print {
    .page-wrapper { margin: 0; }
    .btn, .sidebar, .header { display: none !important; }
}
</style>

<?php require_once "../component/footer.php" ?>