<?php
    session_start();
    require_once '../database.inc.php';

    if (!isset($_SESSION['user']['account_id']) || !isset($_SESSION['user']['user_id'])) {
        header("Location:../auth/login.php");
        exit();
    }

    $flat_id = isset($_GET['id']) ? $_GET['id'] : '';

    $pdo = connect_db();

    //get all preview slots for this flat
    $query = "SELECT flat_id, preview_id, preview_date, preview_time_start, preview_time_end, phone
            FROM flat_preview
            WHERE flat_id = :flat_id";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':flat_id', $flat_id);
    $statement->execute();
    $previews = $statement->fetchAll();

    //get already booked preview IDs */
    $query = "SELECT preview_id 
            FROM preview_requests 
            WHERE flat_id = :flat_id";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':flat_id', $flat_id);
    $statement->execute();
    $taken_previews = $statement->fetchAll();

    //store booked preview IDs
    $taken_preview_ids = [];
    foreach ($taken_previews as $prev) {
        $taken_preview_ids[] = $prev['preview_id'];
    }

    $pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>3lRaheeq agency</title>
    <link rel="stylesheet" href="../main.css">
    </head>

    <body>

        <?php include('../includes/header.php') ?>

        <section class="page-navigation-main">

        <?php include('../includes/leftSideNavigation.php') ?>

            <main class="height-70vh px-16 py-16">

            <h2>Appointments</h2>

            <table>
                <thead>
                    <tr>
                    <th>Date</th>
                    <th>Start time</th>
                    <th>End time</th>
                    <th>Phone</th>
                    <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    foreach ($previews as $preview) {

                        $preview_id = $preview['preview_id'];
                        $taken = in_array($preview_id, $taken_preview_ids);

                        echo "<tr class='" . ($taken ? "taken_preview" : "available_preview") . "'>";

                        echo "<td>" . htmlspecialchars($preview['preview_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($preview['preview_time_start']) . "</td>";
                        echo "<td>" . htmlspecialchars($preview['preview_time_end']) . "</td>";
                        echo "<td>" . htmlspecialchars($preview['phone']) . "</td>";
                        echo "<td>";

                        if ($taken) {
                            echo "<button disabled class='disabled-button'>Booked</button>";
                        } else {
                            echo "
                            <form action='book_preview.php' method='post'>
                                <input type='hidden' name='preview_id' value='".$preview_id."'>
                                <input type='hidden' name='flat_id' value='".$flat_id."'>
                                <button type='submit' class='confirm-button'  >Book</button>
                            </form>";
                        }

                        echo "</td></tr>";
                    }
                    ?>

                </tbody>
            </table>

            </main>

        </section>

        <?php include('../includes/footer.php') ?>


        <script src="../main.js"></script>

    </body>
</html>