<?php
    $tableName = 'suppliers';

    include_once __DIR__ . '/../paginated_table.php';
    include_once __DIR__ . '/../util.php';
    include_once __DIR__ . '/../config.php';
?>
<div class="container">
    <div class="row mt-5">
        <div class="col m-auto">
            <h1>Suppliers</h1>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Supplier Id</th>
                        <th scope="col" class="text-end">Company Name</th>
                        <th scope="col" class="text-end">Supplier Name</th>
                        <th scope="col" class="text-end">Contact No</th>
                        <th scope="col" class="text-end">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <th scope="row">
                                    <a href="<?php echo BASE_URL . "suppliers.php?s_id=" . $row['id']; ?>">
                                        <?php echo $row['id']; ?>
                                    </a>
                                </th>
                                <td class="text-end"><?php echo $row['company_name']; ?></td>
                                <td class="text-end"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                <td class="text-end"><?php echo $row['contact_no']; ?></td>
                                <td class="text-end"><?php echo $row['email']; ?></td>
                            </tr>
                        <?php } ?>
                </tbody>
            </table>

            <div class="row mt-5">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                            <li class="page-item"><a class="page-link" href="<?php echo BASE_URL . "suppliers.php?page=$i"; ?>"><?php echo $i; ?></a></li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>