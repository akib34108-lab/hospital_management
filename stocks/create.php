<?php require_once "../component/header.php"; ?>
<!-- Sidebar Start -->
<?php require_once "../component/sidebar.php"; 

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Add Stock</h4>
                <h6>Create a new stock record</h6>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form action="<?= $base_url ?>stock/create.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <select name="product_id" class="form-control" required>
                            <option value="">-- Select Product --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= (int)$p->id ?>"
                                    <?= (isset($_POST['product_id']) && $_POST['product_id'] == $p->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p->product_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warehouse</label>
                        <select name="warehouse_id" class="form-control" required>
                            <option value="">-- Select Warehouse --</option>
                            <?php foreach ($warehouses as $w): ?>
                                <option value="<?= (int)$w->id ?>"
                                    <?= (isset($_POST['warehouse_id']) && $_POST['warehouse_id'] == $w->id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($w->warehouse_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="0"
                               value="<?= htmlspecialchars($_POST['quantity'] ?? 0) ?>" required>
                    </div>

                    <button type="submit" class="btn btn-submit me-2">Save</button>
                    <a href="<?= $base_url ?>stock/list.php" class="btn btn-cancel">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

</div> <!-- /.main-wrapper -->

<?php require_once "../component/footer.php" ?> 
