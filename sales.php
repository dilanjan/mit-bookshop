<?php
// This loggedInHeader should only be used for authenticated pages.
include __DIR__ . '/src/templates/common/loggedInHeader.php';

// write your code below this line -------------
?>

<?php if(isset($_GET['order_id'])) { ?>
    <?php include __DIR__ . '/src/sales/index.php'; ?>
<?php } else { ?>
    <?php include __DIR__ . '/src/sales/list.php'; ?>
<?php } ?>

<?php

// Don't write below this line ------------------

include __DIR__ . '/src/templates/common/footer.php';
?>
