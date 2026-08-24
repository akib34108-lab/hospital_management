<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; 

$id = $_GET['id'];
  $EditInventory = $crud->common_select("inventory_list", "*", ['id' => $id]);
  if (!$EditInventory['status'] || empty($EditInventory['data'])) {
    $_SESSION['message'] = array('danger','Error', 'inventory not found.');
    echo "<script>window.location.href = '".$base_url."inventory_management/inventory.php';</script>";
    exit;
  }

  $EditInventory = $EditInventory['data'][0];
?>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <h4 class="page-title">Edit Patient</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form action="<?= $base_url; ?>inventory_management/update_inventory.php" method="post">
                            <input type="hidden" name="id" value="<?= $EditInventory->id ?>" >
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                    <label>Name</label>
                                    <input class="form-control" name="name" type="text" value="<?= $EditInventory->name ?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>category</label>
                                    <input class="form-control" name="category" type="text" value="<?= $EditInventory->category ?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>quantity</label>
                                    <input class="form-control" name="quantity" type="text" value="<?= $EditInventory->quantity ?>" >
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>supplier info</label>
                                    <input class="form-control" name="supplier_info" type="text" value="<?= $EditInventory->supplier_info ?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>supplier contact</label>
                                    <input class="form-control" name="supplier_contact" type="text" value="<?= $EditInventory->supplier_contact ?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Date</label>
                                    <div class="cal-icon">
                                        <input onblur="getDoctorSchedule(this.value)" type="date" name="date"  class="form-control">
                                    </div>
                                </div>
                            </div>
                        <div class="m-t-20 text-center col-sm-12">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require_once "../component/footer.php" ?> 

