```php
<?php

$host = "localhost";
$user = "root";
$pass = "";

$conn = mysqli_connect($host,$user,$pass);

if(!$conn){
    die("Connection Failed");
}

$sql = "CREATE DATABASE IF NOT EXISTS masjid_finder";
mysqli_query($conn,$sql);

mysqli_select_db($conn,"masjid_finder");

$masjids = "

CREATE TABLE IF NOT EXISTS masjids(

id INT AUTO_INCREMENT PRIMARY KEY,

name VARCHAR(255) NOT NULL,

sect VARCHAR(50) NOT NULL,

address TEXT,

latitude VARCHAR(100),

longitude VARCHAR(100),

fajr TIME,
zuhr TIME,
asr TIME,
maghrib TIME,
isha TIME,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

)

";

mysqli_query($conn,$masjids);

echo "Database Installed Successfully";
?>
```
