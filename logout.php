<?php
 require_once "component/connection.php";
    session_destroy();
    echo "<script>window.location='$base_url/login.php'</script>";
?>