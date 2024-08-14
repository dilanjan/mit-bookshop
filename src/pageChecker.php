<?php
function isCurrentPage($page) {
    return basename($_SERVER['PHP_SELF']) == $page;
}

function checkUserLogged()
{
    if (!isset($_SESSION['username'])) {
        // User is not logged in, redirect to login page
//        header("Location: login.php");
//        exit();
        echo '<script type="text/javascript">
           window.location = "login.php";
      </script>';
        exit();
    }
}