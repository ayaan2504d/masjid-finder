<?php
require_once 'config/database.php';

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM masjids WHERE id=$id");

header("Location: masjids.php");
exit;
?>