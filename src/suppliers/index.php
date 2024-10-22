<?php
include_once __DIR__ . '/../db_conn.php';
include_once __DIR__ . '/../util.php';
include_once __DIR__ . '/../config.php';

if (!isset($_GET['s_id'])) { ?>
    <p>Invalid supplier profile selected.</p>
<?php } else {
    $supplierQuery = "SELECT * from suppliers where id=" . $_GET['s_id'];

    $supplier = $conn->query($supplierQuery);
    $supplierRow = $supplier->fetch_assoc();

    $supplierProducts = false;

    if (isset($supplierRow['id'])) {
        $supplierProductsQuery = "SELECT pr.id as pr_id, pr.product_name from products as pr "
            . "where supplier_id=" . $_GET['s_id'];

        $supplierProducts = $conn->query($supplierProductsQuery);
    }
?>

<div class="container">
    <div class="row mt-5">
        <div class="col m-auto">
            <h1>Supplier: <?php echo $supplierRow['company_name']; ?></h1>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-6">
            <table class="table">
                <tbody>
                    <tr>
                        <th scope="col">Supplier ID</th>
                        <td><?php echo $supplierRow['id']; ?></td>
                    </tr>
                    <tr>
                        <th scope="col">Company Name</th>
                        <td><?php echo $supplierRow['company_name']; ?></td>
                    </tr>
                    <tr>
                        <th scope="col">Address</th>
                        <td><?php echo $supplierRow['address_line_1'].'<br/>'.$supplierRow['address_line_2']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <table class="table">
                <tbody>
                <tr>
                    <th scope="col">Contact Person</th>
                    <td><?php echo $supplierRow['first_name'] . " " . $supplierRow['last_name'] ; ?></td>
                </tr>
                <tr>
                    <th scope="col">Contact No.</th>
                    <td><?php echo $supplierRow['contact_no']; ?></td>
                </tr>
                <tr>
                    <th scope="col">Email</th>
                    <td><?php echo $supplierRow['email']; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($supplierProducts->num_rows > 0) { ?>
        <div class="row mt-5">
            <div class="col-12">
                <p class="fs-4">Supplier Products</p>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Product ID</th>
                        <th scope="col">Product name</th>
<!--                        <th scope="col" class="text-end">Item price</th>-->
<!--                        <th scope="col" class="text-end">Quantity</th>-->
<!--                        <th scope="col" class="text-end">Row total</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($supplierProduct = $supplierProducts->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $supplierProduct['pr_id']; ?></td>
                            <td><?php echo $supplierProduct['product_name']; ?></td>
<!--                            <td class="text-end">--><?php //echo formatCurrency($orderItemsRows['product_price']); ?><!--</td>-->
<!--                            <td class="text-end">--><?php //echo $orderItemsRows['qty']; ?><!--</td>-->
<!--                            <td class="text-end">--><?php //echo formatCurrency($orderItemsRows['row_total']); ?><!--</td>-->
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
</div>
<?php } ?>