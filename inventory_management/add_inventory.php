<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; ?>
<!-- Sidebar End -->
 
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h4 class="page-title">Add Inventory</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <form action="<?= $base_url; ?>inventory_management/store_inventory.php" method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" name="name" type="text">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>category</label>
                                <input class="form-control" name="category" type="text">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>quantity</label>
                                <input class="form-control" name="quantity" type="text">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>supplier info</label>
                                <input class="form-control" name="supplier_info" type="text">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>supplier contact</label>
                                <input class="form-control" name="supplier_contact" type="text">
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
                        <button class="btn btn-primary submit-btn">Add inventory</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require_once "../component/footer.php" ?> 