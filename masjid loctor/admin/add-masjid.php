```php
<?php
require_once 'config/database.php';
include '../home/header.php';

if(isset($_POST['save'])){

$name = $_POST['name'];
$sect = $_POST['sect'];
$address = $_POST['address'];

mysqli_query($conn,"INSERT INTO masjids(name,sect,address)
VALUES('$name','$sect','$address')");

header("Location: masjids.php");
exit;
}
?>

<div class="container py-5">

<h2>➕ Add Masjid</h2>

<form method="POST">

<input type="text" name="name"
class="form-control mb-3"
placeholder="Masjid Name" required>

<select name="sect" class="form-control mb-3">

<option value="Sunni">Sunni</option>
<option value="Shia">Shia</option>

</select>

<textarea name="address"
class="form-control mb-3"
placeholder="Address"></textarea>

<button type="submit"
name="save"
class="btn btn-success">

Save Masjid

</button>

</form>

</div>

<?php include '../home/footer.php'; ?>
```
