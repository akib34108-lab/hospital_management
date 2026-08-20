<?php require_once "../../component/header.php"; ?>
<!-- sidebar -->
<?php require_once "../../component/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-sm-5 col-5">
                        <h4 class="page-title">Rooms</h4>
                    </div>
                    <div class="col-sm-7 col-7 text-right m-b-30">
                        <a href="add_room.php" class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Add Room</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0 datatable">
                                <thead>
                                    <tr>
                                        <th>Room ID</th>
                                        <th>Patient ID</th>
                                        <th>Room Type</th>
                                        <th>Bed Number</th>
                                        <th>Charge Per Day(taka)</th>
                                        <th>Status</th>
                                        <th class="text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        // Fetch department from the database
                                        if(isset($_GET['page']) && is_numeric($_GET['page'])){
                                            $page = (int)$_GET['page'];
                                        } else {
                                            $page = 1;
                                        }
                                        $rooms = $crud->common_select("rooms", "*", "", 10, ($page-1)*10);
                                        
                                        if($rooms['status']){
                                        foreach ($rooms['data'] as $room) { ?>
                                        <td><?= $room->room_number ?></td>
                                        <td><?= $room->patient_id ?></td>
                                        <td>
                                        <?php
                                            $roomType = [1 => 'General',2 => 'Semi-Private',3 => 'Private',4 => 'Deluxe',5 => 'VIP',6 => 'ICU',7 => 'CCU',8 => 'NICU',9=>'Isolation', 10=>'OT', 11=>'Observation', 12=>'Delivery'];
                                        ?>
                                        <?= htmlspecialchars($roomType[(int)$room   ->room_type] ?? 'N/A') ?>
                                        </td>
                                        <td><?= $room->available_beds ?></td>
                                        <td><?= $room->room_charge ?></td>
                                        <td>
                                            <?php if ($room->status == '1') { ?>
                                            <span class="badge bg-success">Available</span>
                                            <?php } else if ($room->status == '2') { ?>
                                            <span class="badge bg-warning">Occupied</span>
                                            <?php } else { ?>
                                            <span class="badge bg-danger">Under Maintenance</span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-left">
                                            <a href="<?= $base_url ?>ward/rooms/edit_room.php?id=<?= $room->id ?>"><i class="fa fa-edit pr-2" style="color: #20865f; font-size: 24px;"></i></a>
                                            <a onclick="return confirm('Are you sure you want to delete this room?');" href="<?= $base_url ?>ward/rooms/delete_room.php?id=<?= $room->id ?>"><i class="fa fa-trash" style="color: #dc3545; font-size: 24px;"></i></a>
                                        </td>
                                    </tr>
                                            <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                            <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                            <?php
                                $total_records = $crud->number_of_records("rooms");
                                $records_per_page = 10;
                                $total_pages = ceil($total_records / $records_per_page);
                            ?>
                                <ul class="pagination">
                                    <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">Previous</a>
                                    </li>
                                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="<?= $base_url ?>ward/rooms/rooms.php?page=<?= $i ?>"><?= $i ?></a></li>
                                    <?php } ?>
                                    
                                    <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">Next</a>
                                    </li>
                                </ul>
                             </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require_once "../../component/footer.php"; ?>