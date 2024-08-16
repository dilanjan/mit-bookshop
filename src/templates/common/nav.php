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
                <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>">Home</a>

                <?php if (isset($_SESSION['username'])) { ?>
                    <!-- place your page links inside this. this will only allow authenticated users to view these -->
                    <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>pos.php">POS</a>
                <?php } ?>

                <?php if(!isset($_SESSION['username']) || isCurrentPage('logout.php')) { ?>
                    <?php if (!isCurrentPage('login.php')) { ?>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>login.php">Login</a>
                    <?php } ?>
                <?php } else { ?>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>logout.php">Logout</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>