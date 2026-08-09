<?php require_once "../component/header.php";?>
<?php require_once "../component/sidebar.php";?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-4 col-3">
                <h4 class="page-title">Create Invoice</h4>
            </div>
        </div>

        <?php
        $patient_discount = 0;
        $patient_id = isset($_POST['patient_id'])? $_POST['patient_id'] : '';
        $show_test = false;

        if(isset($_POST['load_patient']) &&!empty($patient_id)){
            $show_test = true;
            $patient_res = $crud->common_select("patients", "discount_percent", ["id" => $patient_id]);
            if($patient_res['status'] && !empty($patient_res['data'])){
                $patient_discount = $patient_res['data'][0]->discount_percent;
            }
        }
       ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Select Patient <span class="text-danger">*</span></label>
                                        <select name="patient_id" class="form-control select" required>
                                            <option value="">-- Select Patient --</option>
                                            <?php
                                            $patients = $crud->common_select("patients", "id, name", [], "AND", "name", "ASC");
                                            if($patients['status']){
                                                foreach($patients['data'] as $p){
                                                    $sel = ($patient_id == $p->id)? 'selected' : '';
                                                    echo "<option value='{$p->id}' $sel>{$p->name}</option>";
                                                }
                                            }
                                           ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Patient Discount %</label>
                                        <input type="text" name="patient_discount" id="patient_discount" class="form-control" value="<?= $patient_discount?>" readonly>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Invoice Date</label>
                                        <input type="date" name="invoice_date" class="form-control" value="<?= date('Y-m-d')?>" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Paid Amount</label>
                                        <input type="number" step="0.01" name="paid_amount" class="form-control" value="0">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" name="load_patient" class="btn btn-primary btn-block">Load</button>
                                    </div>
                                </div>
                            </div>

                            <?php if($show_test):?>
                            <hr>
                            <div class="text-right mb-3">
                                <button type="button" id="add_test" class="btn btn-success"><i class="fa fa-plus"></i> Add Test</button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="invoice_table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Test Name</th>
                                            <th width="100">Price</th>
                                            <th width="80">Qty</th>
                                            <th width="100">Disc %</th>
                                            <th width="100">VAT %</th>
                                            <th width="120">Sub Total</th>
                                            <th width="70">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right"><b>Sub Total</b></td>
                                            <td colspan="2" class="text-right"><b><span id="sub_total">0.00</span></b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-right"><b>Total Discount</b></td>
                                            <td colspan="2" class="text-right"><b><span id="discount_amount">0.00</span></b></td>
                                        </tr>
                                        <tr class="bg-primary text-white">
                                            <td colspan="5" class="text-right"><b>Grand Total</b></td>
                                            <td colspan="2" class="text-right"><b><span id="grand_total">0.00</span></b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <button type="submit" name="save_invoice" formaction="store.php" class="btn btn-primary">Save Invoice</button>
                            <?php else:?>
                                <div class="alert alert-info mt-3">Please Select a Patient and Click Load Button</div>
                            <?php endif;?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    $('#add_test').click(function(){
        let patient_dis = parseFloat($('#patient_discount').val()) || 0; // Uporer discount value niye nilam
        let row = `<tr>
            <td>
                <select name="test_id[]" class="form-control test_id" required>
                    <option value="">-- Select Test --</option>
                    <?php
                    $tests = $crud->common_select("lab_category", "id, test_name, price", [], "AND", "test_name", "ASC");
                    if($tests['status']){
                        foreach($tests['data'] as $t){
                            echo "<option value='{$t->id}' data-price='{$t->price}'>{$t->test_name} - {$t->price} TK</option>";
                        }
                    }
                   ?>
                </select>
            </td>
            <td><input type="number" step="0.01" name="price[]" class="form-control price" readonly></td>
            <td><input type="number" name="qty[]" class="form-control qty" value="1" min="1"></td>
            <td><input type="number" step="0.01" name="discount[]" class="form-control discount" value="${patient_dis}"></td> <!-- Auto boshbe -->
            <td><input type="number" step="0.01" name="vat[]" class="form-control vat" value="0"></td>
            <td><input type="number" step="0.01" name="sub_total[]" class="form-control sub_total" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm remove_row">X</button></td>
        </tr>`;
        $('#invoice_table tbody').append(row);
    });

    $(document).on('click', '.remove_row', function(){ 
        $(this).closest('tr').remove(); 
        calculateTotal(); 
    });

    $(document).on('change', '.test_id', function(){
        let price = $(this).find(':selected').data('price');
        $(this).closest('tr').find('.price').val(price);
        calculateRow($(this).closest('tr'));
    });

    $(document).on('keyup change', '.qty,.discount,.vat,.price', function(){ 
        calculateRow($(this).closest('tr')); 
    });

    function calculateRow(row){
        let price = parseFloat(row.find('.price').val()) || 0;
        let qty = parseFloat(row.find('.qty').val()) || 0;
        let dis = parseFloat(row.find('.discount').val()) || 0;
        let vat = parseFloat(row.find('.vat').val()) || 0;

        let total = price * qty;
        let dis_amount = total * (dis / 100);
        let after_dis = total - dis_amount;
        let vat_amount = after_dis * (vat / 100);
        let sub_total = after_dis + vat_amount;

        row.find('.sub_total').val(sub_total.toFixed(2));
        calculateTotal();
    }

    function calculateTotal(){
        let sub_total = 0;
        let total_discount = 0;
        $('#invoice_table tbody tr').each(function(){
            let price = parseFloat($(this).find('.price').val()) || 0;
            let qty = parseFloat($(this).find('.qty').val()) || 0;
            let dis = parseFloat($(this).find('.discount').val()) || 0;
            let row_total = price * qty;
            let row_dis = row_total * (dis / 100);
            total_discount += row_dis;
            sub_total += parseFloat($(this).find('.sub_total').val()) || 0;
        });

        $('#sub_total').text((sub_total + total_discount).toFixed(2)); // discount er age total
        $('#discount_amount').text(total_discount.toFixed(2));
        $('#grand_total').text(sub_total.toFixed(2)); // discount bader por
    }
});
</script>

<?php require_once "../component/footer.php";?>