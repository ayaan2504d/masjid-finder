```php
<?php
require_once 'config/database.php';
include '../home/header.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT * FROM masjids WHERE id=$id")
);

if(isset($_POST['update'])){

$name = $_POST['name'];
$sect = $_POST['sect'];
$address = $_POST['address'];

mysqli_query($conn,
"UPDATE masjids SET
name='$name',
sect='$sect',
address='$address'
WHERE id=$id");

header("Location: masjids.php");
exit;
}
?>

<div class="container py-5">

<h2>✏️ Edit Masjid</h2>

<form method="POST">

<input type="text" name="name"
value="<?php echo $data['name']; ?>"
class="form-control mb-3">

<select name="sect" class="form-control mb-3">

<option <?php if($data['sect']=='Sunni') echo 'selected'; ?>>
Sunni
</option>

<option <?php if($data['sect']=='Shia') echo 'selected'; ?>>
Shia
</option>

</select>

<textarea name="address"
class="form-control mb-3">

<?php echo $data['address']; ?>

</textarea>

<button type="submit"
name="update"
class="btn btn-primary">

Update

</button>

</form>

</div>

<?php include '../home/footer.php'; ?>
```
