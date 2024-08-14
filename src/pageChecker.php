<?php
function isCurrentPage($page) {
    return basename($_SERVER['PHP_SELF']) == $page;
}