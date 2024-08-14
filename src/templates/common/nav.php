<?php include_once __DIR__ . '/../../pageChecker.php' ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Bookshop</a>
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="/">Home</a>
                <?php if(!isset($_SESSION['username']) || isCurrentPage('logout.php')) { ?>
                    <?php if (!isCurrentPage('login.php')) { ?>
                        <a class="nav-link" href="/login.php">Login</a>
                    <?php } ?>
                <?php } else { ?>
                    <a class="nav-link" href="/logout.php">Logout</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>