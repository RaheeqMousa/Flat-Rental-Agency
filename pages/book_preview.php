<?php
    session_start();
    require_once('../database.inc.php');

    $pdo=connect_db();

    $customer_id = $_SESSION['user']['user_id'];
    $customer_account_id = $_SESSION['user']['account_id'];
    $flat_id = $_POST['flat_id'];
    $preview_id = $_POST['preview_id'];


    //if user entered an id in the url, the id maybe already booked
    $query="select count(*) from preview_requests where preview_id=:preview_id and flat_id=:flat_id and status = 'booked';";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':preview_id',$preview_id);
    $statement->bindValue(':flat_id',$flat_id);
    $statement->execute();
    if ($statement->fetchColumn() > 0) {
        echo "This Preview has already been booked.";
    }

    //i used this to get the owner data in order to use that data to make a message for him
    $query="
    select owner.user_id, a.account_id, owner.name as owner_name, flat.refference_number
    from flats flat
    join users owner on flat.owner_id=owner.user_id
    join accounts a on a.user_id=owner.user_id
    where flat.flat_id=:flat_id;";

    $statement = $pdo->prepare($query);
    $statement->bindValue(':flat_id',$flat_id);
    $statement->execute();
    $owner = $statement->fetch();


    //i saved the customer book as a pending
    $query="insert into preview_requests (preview_id, flat_id, customer_id, status)
            values(:prev_id,:flat_id,:customer_id,:status);";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':prev_id',$preview_id);
    $statement->bindValue(':flat_id',$flat_id);
    $statement->bindValue(':customer_id',$customer_id);
    $statement->bindValue(':status','pending');
    $statement->execute();
    $last_insert_preview_id=$pdo->lastInsertId();

    // send a message to owner
    $query="insert into messages (sender_account_id,receiver_account_id, title,message_body, status, confirm)
            values (:sender_account_id,:receiver_account_id,:title,:message_body, 'unread', :confirm);";
    $message = "A customer has requested to preview your flat (ID: $flat_id). Please confirm.";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':sender_account_id',$customer_account_id);
    $statement->bindValue(':receiver_account_id',$owner['account_id']);
    $statement->bindValue(':title',"preview request");
    $statement->bindValue(':message_body',"A customer has requested to preview your flat". $owner['refference_number']." Please confirm.");
    $statement->bindValue(':confirm',"PreviewRequest");
    $statement->bindValue(':confirm',$last_insert_preview_id." ".$customer_account_id." ".$owner['user_id']);
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

            <main class='height-70vh row flex-direction-column justify-content-center align-items-center'>
                <div class=" shadow py-16 px-16 border-16 ">
                    <?php
                        echo"<p>Appointment request has been sent to the owner, wait approval</p>";
                    ?>  
                </div>
                  
            </main>

        </section>

        <?php include('../includes/footer.php') ?>

    </body>
</html>