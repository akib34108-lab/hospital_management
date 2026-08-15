<?php require_once "../component/header.php"; ?>
<!-- sidebar -->
<?php require_once "../component/sidebar.php"; ?>

        <div class="page-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <h4 class="page-title">Add Department</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <form action="<?= $base_url; ?>departments/store_department.php" method="POST" enctype="multipart/form-data" class="p-4">
							<div class="form-group">
								<label for="department_name">Department Name</label>
								<input class="form-control" type="text" id="department_name" name="department_name" required>
							</div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea cols="30" rows="4" class="form-control" id="description" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="display-block">Department Status</label>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="status" id="product_active" value="1" checked>
									<label class="form-check-label" for="product_active">
									Active
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="status" id="product_inactive" value="0">
									<label class="form-check-label" for="product_inactive">
									Inactive
									</label>
								</div>
                            </div>
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary submit-btn">Create Department</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
<?php require_once "../component/footer.php"; ?>
