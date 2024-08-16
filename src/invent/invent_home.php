<?php
include '../templates/common/header.php';

include_once __DIR__ . '/../pageChecker.php';
checkUserLogged();

// write your code below this line -------------

?>

    <div class="container">
        <div class="row">
            <div class="col m-auto">
                <h1 class="text-center">Inventory Home</h1>
            </div>
        </div>
    </div>

<?php

// Don't write below this line ------------------

include '../templates/common/footer.php';
?>