<?php

require_once dirname(__DIR__) . '/config.php';

require_once ROOT_PATH . '/home/header.php';

?>

<div class="container py-5">

<h2 class="mb-4">
🔍 Search Masjid
</h2>

<form method="GET">

<div class="input-group mb-4">

<input
type="text"
name="keyword"
class="form-control"
placeholder="Search by Masjid Name">

<button class="btn btn-success">
Search
</button>

</div>

</form>

<div class="card card-custom p-3">

<h5>Masjid Noor</h5>

<p>
350m Away
</p>

<a href="masjid-details.php"
class="btn btn-masjid">
View Details
</a>

</div>

</div>

<?php


require_once ROOT_PATH . '/home/footer.php';

?>

