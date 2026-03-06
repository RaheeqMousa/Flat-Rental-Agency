<?php
    session_start();
    require_once '../database.inc.php';

    if (!isset($_SESSION['user']['account_id']) || !isset($_SESSION['user']['user_id'])) {
        header("Location:../auth/login.php");
    }

    $flat_id = isset($_GET['id']) ? $_GET['id']:'';
    $today = date('Y-m-d');

    $pdo=connect_db();

    $query="select flat_id, preview_id, preview_day, preview_time_start, preview_time_end, phone
            from flat_preview
            where flat_id = :flat_id;";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':flat_id',$flat_id);
    $statement->execute();
    $previews = $statement->fetchAll();

    $query="select preview_id from preview_requests where flat_id=:flat_id;";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':flat_id', $flat_id);
    $taken_previews = $statement->fetchAll();

    $taken_flat_id=[];
    foreach($taken_previews as $prev){
        $taken_flat_id[]=$prev['flat_id'];
    }
    $pdo=null;

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
            
            <main>
                <h2>Appointments</h2>
                
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Start time</th>
                            <th>End time</th>
                            <th>Phone</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($previews as $preview){
                                $preview_id=$preview['flat_id'];
                                $taken=in_array($preview_id,$taken_flat_id);

                                echo "<tr class='" . ($taken ? 'taken_preview' : 'available_preview') . "'>
                                    <td>" . htmlspecialchars($preview['preview_day']) . "</td>
                                    <td>" . htmlspecialchars($preview['preview_time_start']) . "</td>
                                    <td>" . htmlspecialchars($preview['preview_time_end']) . "</td>
                                    <td>" . htmlspecialchars($preview['phone']) . "</td>
                                    <td>";

if ($taken) {
    echo "<button disabled class='disabled-button'>Booked</button>";
} else {
    echo "<form action='book_preview.php' method='post'>
            <input type='hidden' name='preview_id' value='".$preview['preview_id']. "'>
            <input type='hidden' name='flat_id' value='".$flat_id. "'>
            <button type='submit' class='book-button'>Book</button>
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

    </body>
</html>