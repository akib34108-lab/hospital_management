<?php
        // Define a list of known local environments
    $local_hosts = ['localhost', '127.0.0.1', '::1'];

    if (in_array($_SERVER['HTTP_HOST'], $local_hosts) || in_array($_SERVER['REMOTE_ADDR'], $local_hosts)) {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/shifa/component/connection.php";
    } else {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/component/connection.php";
    }
    if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']){
        echo "<script>window.location='{$base_url}dashboard.php'</script>";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>SHIFA - Medical & Hospital</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $base_url; ?>assets/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base_url; ?>assets/assets//css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= $base_url; ?>assets/assets//css/style.css">
    <!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->
</head>
