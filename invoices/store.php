<?php
require_once "../component/connection.php";

if(isset($_POST['save_invoice'])){
    $patient_id = $_POST['patient_id'];
    $invoice_date = $_POST['invoice_date'];
    $paid_amount = $_POST['paid_amount'];
    $patient_discount = $_POST['patient_discount'];

    // 1. Total hisab
    $test_ids = $_POST['test_id'];
    $prices = $_POST['price'];
    $qtys = $_POST['qty'];
    $discounts = $_POST['discount'];
    $vats = $_POST['vat'];
    
    $sub_amount = 0;
    $total_discount = 0;
    $total_tax = 0;

    for($i=0; $i<count($prices); $i++){
        $row_total = $prices[$i] * $qtys[$i];
        $row_dis = $row_total * ($discounts[$i] / 100);
        $row_tax = ($row_total - $row_dis) * ($vats[$i] / 100);
        $sub_amount += $row_total;
        $total_discount += $row_dis;
        $total_tax += $row_tax;
    }
    // patient level discount add
    $total_discount += $sub_amount * ($patient_discount/100);

    // 2. Main Invoice insert - tomar table onujayi
    $invoice_data = [
        'patient_id' => $patient_id,
        'sub_amount' => $sub_amount,
        'discount' => $total_discount,
        'tax' => $total_tax,
        'invoice_date' => $invoice_date,
        'status' => 1 // 1=Active
    ];
    $insert_invoice = $crud->common_insert("invoices", $invoice_data);

    if($insert_invoice['status']){
        $invoice_id = $insert_invoice['last_insert_id'];

        // 3. Invoice Details insert
        $tests_all = $crud->common_select("lab_category", "id, test_name", [], "AND", "id", "ASC");

        for($i=0; $i<count($test_ids); $i++){
            $test_name = '';
            foreach($tests_all['data'] as $t){
                if($t->id == $test_ids[$i]){ $test_name = $t->test_name; break; }
            }

            $item_data = [
                'invoice_id' => $invoice_id,
                'Name' => $test_name,
                'price' => $prices[$i] * $qtys[$i], // total price
                'discount' => $discounts[$i],
                'tax' => $vats[$i]
            ];
            $crud->common_insert("invoice_details", $item_data);
        }

        echo "<script>alert('Invoice Saved Successfully'); window.location='invoice_list.php';</script>";
        exit();
    }else{
        echo "Invoice Save Failed: ".$insert_invoice['error'];
    }
}else{
    header("Location: create.php");
}
?>