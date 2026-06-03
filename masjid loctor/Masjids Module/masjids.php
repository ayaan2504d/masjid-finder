<?php

require_once dirname(__DIR__) . '/config.php';

require_once ROOT_PATH . '/home/header.php';

?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🕌 All Masjids</h2>

        <select class="form-select w-auto">
            <option>All</option>
            <option>Sunni</option>
            <option>Shia</option>
        </select>
    </div>

    <div class="row g-4">

        <div class="col-lg-4 col-md-6">
            <div class="card card-custom p-3">
                <h5>Masjid Noor</h5>
                <p class="text-muted">Distance: 350m</p>

                <span class="badge bg-success">
                    Sunni
                </span>

                <hr>

                <p>Zuhr: 1:15 PM</p>

                <a href="masjid-details.php" class="btn btn-masjid">
                    View Details
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card card-custom p-3">
                <h5>Ali Masjid</h5>
                <p class="text-muted">Distance: 650m</p>

                <span class="badge bg-primary">
                    Shia
                </span>

                <hr>

                <p>Zuhr: 1:30 PM</p>

                <a href="masjid-details.php" class="btn btn-masjid">
                    View Details
                </a>
            </div>
        </div>

    </div>

</div>

<?php


require_once ROOT_PATH . '/home/footer.php';

?>
