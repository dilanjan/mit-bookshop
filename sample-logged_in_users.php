<?php
// This loggedInHeader should only be used for authenticated pages.
include __DIR__ . '/src/templates/common/loggedInHeader.php';

// write your code below this line -------------
?>

    <div class="container">
        <div class="row">
            <div class="col m-auto">
                <h1 class="text-center">Sample page</h1>
            </div>
        </div>
    </div>

<?php

// Don't write below this line ------------------

include __DIR__ . '/src/templates/common/footer.php';
?>