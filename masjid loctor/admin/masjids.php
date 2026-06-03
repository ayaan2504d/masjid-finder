```php
<?php
require_once 'config/database.php';
include '../home/header.php';

$result = mysqli_query($conn,"SELECT * FROM masjids ORDER BY id DESC");
?>

<div class="container py-5">

<h2 class="mb-4">🕌 Manage Masjids</h2>

<a href="add-masjid.php" class="btn btn-success mb-3">
➕ Add Masjid
</a>

<table class="table table-striped table-bordered">

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Sect</th>
<th>Address</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['sect']; ?></td>
<td><?php echo $row['address']; ?></td>

<td>

<a href="edit-masjid.php?id=<?php echo $row['id']; ?>"
class="btn btn-primary btn-sm">
Edit
</a>

<a href="delete-masjid.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this masjid?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<?php include '../home/footer.php'; ?>
```
