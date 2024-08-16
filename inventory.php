<?php
// This loggedInHeader should only be used for authenticated pages.
include __DIR__ . '/src/templates/common/loggedInHeader.php';

// write your code below this line -------------
?>

    <div class="container">
        <div class="row mt-5">
            <div class="col m-auto">
                <h1>Bookshop Inventory</h1>
            </div>
        </div>             
    </div>

    <script src="<?php echo BASE_URL; ?>dist/js/jquery-3.7.1.min.js"></script>
    <script type="text/javascript">
        window.baseUrl = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>dist/js/pos.js"></script>
<?php

// Don't write below this line ------------------

include __DIR__ . '/src/templates/common/footer.php';
?>