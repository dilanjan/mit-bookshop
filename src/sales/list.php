<?php
    $tableName = 'orders';

    include_once __DIR__ . '/../paginated_table.php';
    include_once __DIR__ . '/util.php';
    include_once __DIR__ . '/../config.php';
?>
<div class="container">
    <div class="row mt-5">
        <div class="col m-auto">
            <h1>Orders</h1>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Order Id</th>
                        <th scope="col" class="text-end">Order Total</th>
                        <th scope="col" class="text-end">Created at</th>
                        <th scope="col" class="text-end">Updated at</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <th scope="row">
                                    <a href="<?php echo BASE_URL . "sales.php?order_id=" . $row['id']; ?>">
                                        <?php echo paddedOrderId($row['id']); ?>
                                    </a>
                                </th>
                                <td class="text-end"><?php echo formatCurrency($row['order_total']); ?></td>
                                <td class="text-end"><?php echo getDateTimeInColTimezone($row['created_at']); ?></td>
                                <td class="text-end"><?php echo getDateTimeInColTimezone($row['updated_at']); ?></td>
                            </tr>
                        <?php } ?>
                </tbody>
            </table>

            <div class="row mt-5">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                            <li class="page-item"><a class="page-link" href="<?php echo BASE_URL . "sales.php?page=$i"; ?>"><?php echo $i; ?></a></li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>