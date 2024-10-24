<?php
function isCurrentPage($page) {
    return basename($_SERVER['PHP_SELF']) == $page;
}

function checkUserLogged()
{
    if (!isset($_SESSION['email'])) {
        // User is not logged in, redirect to login page
        header("Location: index.php");
        exit();
//        echo '<script type="text/javascript">
//           window.location = "index.php";
//      </script>';
//        exit();
    }
}