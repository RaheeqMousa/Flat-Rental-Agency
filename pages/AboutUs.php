<?php
    session_start();

    require_once('../database.inc.php');

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>3lRaheeq agency</title>
        <link href="../main.css" rel="stylesheet">
    </head>

    <body>
        <?php include('../includes/header.php') ?>

        <section class="page-navigation-main">
            <?php include('../includes/leftSideNavigation.php') ?>

            <main>

                <div class="row flex-direction-column gap-32" id="about-us" >
                        <section id="agency-intro" >
                            <h2>3lRaheeq Agency</h2>
                            <div  class="row justify-content-center">
                                <p>Is a flat rental agency that rent different types of flats for its customers. It was established in 2025 to simplify the flat rental process in the Birzeit region. Also, Known for reliability and customer-first service, we have grown through digital innovation and award-winning service delivery. Moreover, Got the award <strong>Green Building Group</strong> by Sustainability in Urban Housing</p>
                            </div>         
                        </section>
                        <section id="agency-activities">
                            <h2>Main Business Activities</h2>
                            <ul class="row justify-content-center gap-32 ">
                                <li class="row">Flat Search and Filters</li>
                                <li class="row">Flat Registration by owners or customers</li>
                                <li class="row">Rental Management System</li>
                                <li class="row">Appointment Booking for Viewings</li>
                                <li class="row">Secure Login for Customers, Owners, and Managers</li>
                                <li class="row">Real-time Notifications and Messaging</li>
                            </ul>
                        </section>
</div>
            </main>

        </section>

        <?php include('../includes/footer.php') ?>

    </body>
</html>