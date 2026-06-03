```php
<?php
require_once '../config/database.php';

$total = mysqli_num_rows(
mysqli_query($conn,"SELECT id FROM masjids")
);
?>

<?php include '../includes/header.php'; ?>

<div class="container py-5">

<div class="row">

<div class="col-md-4">

<div class="card p-4 shadow">

<h3><?php echo $total; ?></h3>

<p>Total Masjids</p>

</div>

</div>

</div>

<div class="mt-4">

<a href="add-masjid.php"
class="btn btn-success">

Add Masjid

</a>

<a href="masjids.php"
class="btn btn-primary">

Manage Masjids

</a>

</div>

</div>

<?php include '../home/footer.php'; ?>
```
