<?php
    require_once('../database.inc.php');
    session_start();

    if (!isset($_POST['id'])) {
        header('Location:../index.html');
    }
    $ids=explode(' ',trim($_POST['id']));

    $renal_id=$ids[0];
    $flat_id=$ids[1];
    $rev_acc_id=$ids[2];

    $pdo=connect_db();
    
    $query="update flats set is_rented = :rent WHERE flat_id = :flat_id;";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':flat_id',$flat_id);
    $statement->bindValue(':rent',1);
    $statement->execute();
    
    //get the refference number
    $query="select f.refference_number from flats f where flat_id=:flat_id;";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':flat_id',$flat_id);
    $statement->execute();
    $flat=$statement->fetch();
    
    //set the rental approved
    $query="update rentals set is_approved= :is_approved where rental_id = :rental_id;";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':rental_id',$renal_id);
    $statement->bindValue(':is_approved',1);
    $statement->execute();
    
    //get rental data
    $query="select * from rentals where rental_id = :rental_id;";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':rental_id',$renal_id);
    $statement->execute();
    $rental=$statement->fetch();
    
    //get customer info
    $query="select customer.* from accounts acc
            join users customer on customer.user_id=acc.user_id
            where acc.account_id= :acc_id;";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':acc_id',$rev_acc_id);
    $statement->execute();
    $customer=$statement->fetch();
    
    //get owner info
    $query="select owner.* from flats f
            join users owner on owner.user_id=f.owner_id
            where f.flat_id= :flat_id;";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':flat_id',$flat_id);
    $statement->execute();
    $owner=$statement->fetch();

    //send confirmation message to the customer
    $query = "insert into messages (receiver_account_id, sender_account_id, title, message_body, status) values 
                    (:receiver, :sender, :title, :mes, 'unread');";
    $statement = $pdo->prepare($query);

    $statement->bindValue(':receiver',$rev_acc_id);
    $statement->bindValue(':sender',$_SESSION['user']['account_id']);
    $statement->bindValue(':title','Rental Confirmed');
    $statement->bindValue(':mes',"Collect the key from ".$_SESSION['user']['name']." connect using mobile number ".$_SESSION['user']['phone']."");
    $statement->execute();
    
    //send messaqge to the owner
    
    $query = "insert into messages (receiver_account_id, sender_account_id, title, message_body, status) values 
                    (:receiver, :sender, :title, :mes, 'unread');";
    $statement = $pdo->prepare($query);

    $statement->bindValue(':receiver',3);
    $statement->bindValue(':sender',$_SESSION['user']['account_id']);
    $statement->bindValue(':title','Rental Confirmed');
    $statement->bindValue(':mes',"The flat ".$flat['refference_number']." has been rented by ".$customer['name']." connect using mobile number ".$customer['mobile_number']." with ".$rental['start_date']." to ".$rental['end_date'].",, which is for the Owner ".$owner['name']." and his mobile number is ".$owner['mobile_number']);
    $statement->execute();


    $pdo=null;
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
                <section class="message-style">
                    <?php
                        echo "<p>Customer rent confirmed successfully.</p>";
                    ?>
                </section>    
            </main>

        </section>

        <?php include('../includes/footer.php') ?>

    </body>
</html>
