<?php require_once "../../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../../component/sidebar.php"; ?>
<!-- Sidebar End -->
 <div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-5 col-5">
                <h4 style="color: #104d6f; font-size: 24px;">Donor List</h4>
            </div>
            <div class="col-sm-7 col-7 text-right m-b-30">
                <a href="<?= $base_url; ?>blood_bank/blood_collection/collection.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#104d6f;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-list" style="font-size:11px;color:#fff;"></i></span>Blood Collection</a>
                <a href="add_donor.php" style="display:inline-flex;align-items:center;gap:9px;padding:9px 17px;background:#104d6f;color:#fff;border-radius:7px;text-decoration:none;font-size:14px;font-weight:600;box-shadow:0 3px 8px rgba(13,110,253,.22);">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:rgba(255,255,255,.18);border-radius:50%;"><i class="fa fa-plus" style="font-size:11px;color:#fff;"></i></span>Add Donor</a>
            </div>
        </div>
        <div class="row">
        <?php
        if(isset($_GET['page']) && is_numeric($_GET['page'])){
            $page = (int)$_GET['page'];
            } else {
            $page = 1;
        }
        $donors = $crud->common_select("donor",'*',[],'AND','id','ASC',10,($page-1)*10);
        if ($donors['status']) {
            foreach ($donors['data'] as $donor) { ?>
        <div class="col-sm-6 col-md-3 mb-3">
            <table class="w-100" style="border: 1px solid #dee2e6; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <tr>
                    <td colspan="2" class="text-right pr-2"><?php if ($donor->donor_eligibility == '1') { ?><span style="display:inline-block;width:8px;height:8px;background:#28a745;border-radius:50%;"></span> Eligible
                    <?php } else { ?><span style="display:inline-block;width:8px;height:8px;background:#dc3545;border-radius:50%;"></span> Not Eligible<?php } ?></td>
                </tr>
                <tr>
                    <th class="text-center" colspan="2" style="font-size: 18px; color: #009efb;"><?= htmlspecialchars($donor->donor_name) ?><br>
                            <?php if ($donor->gender == 1) { ?>
                        <span style="font-size: 12px; color: gray;">Male</span>
                            <?php } else { ?>
                        <span style="font-size: 12px; color: gray;">Female</span>
                            <?php } ?>
                        <span style="font-size: 12px; color: gray;">(<?= htmlspecialchars($donor->age) ?>)</span>
                    </th>
                </tr>
            </table>
            <table border="1" class="w-100" style="border: 1px solid #dee2e6; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <tr class="text-center">
                    <td>ID</td>
                    <td><?= htmlspecialchars($donor->id) ?></td>
                </tr>
                <tr class="text-center">
                    <td>Phone</td>
                    <td><?= htmlspecialchars($donor->phone) ?></td>
                </tr>
                <tr class="text-center">
                    <td>Blood Group</td>
                    <td>
                    <?php
                        $bloodGroups = [1 => 'A+',2 => 'A-',3 => 'B+',4 => 'B-',5 => 'AB+',6 => 'AB-',7 => 'O+',8 => 'O-'];
                    ?>
                    <?= htmlspecialchars($bloodGroups[(int)$donor->blood_group] ?? 'N/A') ?>
                    </td>
                </tr>
            </table>
            <table class="w-100" style="border: 1px solid #dee2e6; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <tr>
                    <td colspan="2" class="text-center">
                        <a href="<?= $base_url ?>blood_bank/donor/view_donor.php?id=<?= $donor->id ?>"><i class="fa fa-user pl-2" style="font-size: 18px;"></i></a>
                        <a href="<?= $base_url ?>blood_bank/donor/edit_donor.php?id=<?= $donor->id ?>"><i class="fa fa-edit pl-2" style="font-size: 18px;"></i></a>
                        <a onclick="return confirm('Are you sure you want to delete this donor?')" href="<?= $base_url ?>blood_bank/donor/delete_donor.php?id=<?= $donor->id ?>"><i class="fa fa-trash pl-2" style="font-size: 18px;"></i></a>
                    </td>
                </tr>
            </table>
        </div>
        <?php }} ?>
    </div>
    <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
        <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
            <?php
            $total_records = $crud->number_of_records("donor");
            $records_per_page = 10;
            $total_pages = ceil($total_records / $records_per_page);
            ?>
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="<?= $base_url ?>donor/donor.php?page=<?= $i ?>"><?= $i ?></a></li>
                <?php } ?>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
</div>
<?php require_once "../../component/footer.php" ?>      