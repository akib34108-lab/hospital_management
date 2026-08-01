<?php
    session_start();
    $base_url = "http://localhost/shifa/";
    require_once  ($_SERVER['DOCUMENT_ROOT'] . "/shifa/crud/crud_class.php");
    $crud = new crud_class();
?>