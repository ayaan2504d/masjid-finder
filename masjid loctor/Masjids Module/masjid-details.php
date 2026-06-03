<?php

require_once dirname(__DIR__) . '/config.php';

require_once ROOT_PATH . '/home/header.php';

?>

<div class="container py-5">

<div class="card card-custom p-4">

    <h2>🕌 Masjid Noor</h2>

    <p class="text-muted">
        Gulshan-e-Iqbal, Karachi
    </p>

    <span class="badge bg-success">
        Sunni
    </span>

    <hr>

    <div class="row">

        <div class="col-md-6">

            <h5>Prayer Timings</h5>

            <table class="table">

                <tr>
                    <td>Fajr</td>
                    <td>5:00 AM</td>
                </tr>

                <tr>
                    <td>Zuhr</td>
                    <td>1:15 PM</td>
                </tr>

                <tr>
                    <td>Asr</td>
                    <td>5:00 PM</td>
                </tr>

                <tr>
                    <td>Maghrib</td>
                    <td>7:15 PM</td>
                </tr>

                <tr>
                    <td>Isha</td>
                    <td>8:45 PM</td>
                </tr>

            </table>

        </div>

        <div class="col-md-6">

            <h5>Information</h5>

            <p>
                Distance: 350m
            </p>

            <p>
                Friday Prayer: 1:30 PM
            </p>

            <a href="#" class="btn btn-success">
                📍 Get Directions
            </a>

        </div>

    </div>

</div>

</div>

<?php


require_once ROOT_PATH . '/home/footer.php';

?>
