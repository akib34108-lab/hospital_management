<?php
    require_once "connection.php";
    if(!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']){
        echo "<script>window.location='{$base_url}login.php'</script>";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $base_url; ?>assets/assets/img/favicon.ico">
    <title>SHIFA - Medical & Hospital</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base_url; ?>assets/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base_url; ?>assets/assets//css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base_url; ?>assets/assets//css/style.css">
</head>