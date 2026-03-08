<?php
    require_once('../database.inc.php');
    session_start();

    if (!isset($_POST['id'])) {
        header('Location:../index.html');
    }
    $ids = explode(' ', $_POST['id']);
    $preview_id = trim($ids[0]);
    $customer_account_id = trim($ids[1]);
    $owner_id = trim($ids[2]);

    $pdo = connect_db();

    $query = "UPDATE preview_requests SET status = :status WHERE preview_id = :id";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':id', $preview_id);
    $statement->bindValue(':status', 'approved');
    $statement->execute();

    echo "preview_id: '$preview_id' updated rows: ".$statement->rowCount();
    
    $query="select s.name as name, s.mobile_number as mobile_number from users s where user_id=:id;";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':id',$owner_id);
    $statement->execute();
    $user=$statement->fetch();


    $query="insert into messages (receiver_account_id, title,message_body, status)
            values (:receiver_account_id,:title,:message_body, 'unread');";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':receiver_account_id',$customer_account_id);
    $statement->bindValue(':title',"preview accepted");
    $statement->bindValue(':message_body',"Your Appointment has been confirmed by the owner ".$user['name']." ,mobile number is ".$user['mobile_number']);
    $statement->execute();

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

            <main class="height-70vh row flex-direction-column justify-content-center align-items-center">             
                <section class="shadow border-16 py-32 px-16  ">
                    <?php
                       echo "<p>Customer preview confirmed successfully.</p>";
                    ?>
                </section>    
            </main>

        </section>

        <?php include('../includes/footer.php') ?>

    </body>
</html>
