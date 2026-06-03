```php
<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "masjid_finder";

$conn = mysqli_connect(
    $host,
    $user,
    $pass,
    $db
);

if(!$conn){
    die("Database Error");
}
?>
```
