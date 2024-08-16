<?php include __DIR__ . '/src/templates/common/header.php'; ?>

<?php
    session_destroy();
?>

    <div class="container">
        <div class="row vh-100">
            <div class="col m-auto">
                <p class="text-center">You are logged out from the system.</p>
            </div>
        </div>
    </div>
<?php include __DIR__ . '/src/templates/common/footer.php'; ?>