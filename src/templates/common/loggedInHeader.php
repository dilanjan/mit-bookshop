<?php
session_start();
include_once __DIR__ . '/../../config.php';

include_once __DIR__ . '/../../pageChecker.php';
checkUserLogged();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/bootstrap.min.css">
    <script src="<?php echo BASE_URL; ?>dist/js/bootstrap.bundle.min.js"></script>
    <title>BookshoPOS</title>
</head>
<body>
<?php include __DIR__ . '/nav.php'; ?>
