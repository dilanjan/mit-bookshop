<?php include_once __DIR__ . '/../../pageChecker.php' ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <!-- <a class="navbar-brand" href="#">Bookshop</a>
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> -->
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
               

                <?php if (isset($_SESSION['email'])) { ?>
                    <!-- place your page links inside this. this will only allow authenticated users to view these -->
                    <!-- <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>">Home</a> -->
                    <a class="nav-link" aria-current="page" href="<?php echo BASE_URL; ?>src/user/view_users.php">User Managment</a>
                    <a class="nav-link" aria-current="page" href="<?php echo BASE_URL; ?>inventory.php">Inventory</a>
                    <a class="nav-link" aria-current="page" href="<?php echo BASE_URL; ?>pos.php">POS</a>
                    <a class="nav-link" aria-current="page" href="<?php echo BASE_URL; ?>sales.php">Sales</a>
                    <a class="nav-link" aria-current="page" href="<?php echo BASE_URL; ?>suppliers.php">Suppliers</a>
                <?php } ?>

                <?php if(!isset($_SESSION['email']) || isCurrentPage('logout.php')) { ?>
                    <?php if (!isCurrentPage('index.php')) { ?>
                        <a class="nav-link" href="<?php echo BASE_URL; ?>index.php">Login</a>
                    <?php } ?>
                <?php } else { ?>
                    <!-- <a class="nav-link" href="<?php echo BASE_URL; ?>logout.php">Logout</a> -->
                <?php } ?>
            </div>

             <!-- Username display on the right -->
             <?php if (isset($_SESSION['email'])): ?>
                <div class="ms-auto d-flex align-items-center">
                    <span class="navbar-text me-3">Hello, <?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
                    <a class="btn btn-sm btn-danger" href="<?php echo BASE_URL; ?>logout.php">Logout</a>
                </div>
            <?php endif; ?>


        </div>
    </div>
</nav>