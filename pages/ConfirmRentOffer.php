<?php
    require_once('../database.inc.php');
    session_start();

    $pdo=connect_db();

    if (!isset($_POST['id'])) {
        header('Location:../index.html');
    }

    function generate_id(){
        $refference_number=0;
        do {
            $refference_number = rand(100000,999999);
            $pdo=connect_db();
            $statement = $pdo->prepare("select count(*) from flats where refference_number=:id;");
            $statement->bindValue(':id',$refference_number);
            $statement->execute();
            $exists = $statement->fetchColumn();
            $pdo=null;
        } while ($exists > 0);
        return $refference_number;
    }

    $id=$_POST['id'];
    $refference_number=generate_id();
    
    //set flat rent offer as approved
    $query="update flats set is_approved=:approved, refference_number=:ref where flat_id=:id;";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':id',$id);
    $statement->bindValue(':approved',1);
    $statement->bindValue(':ref',$refference_number);
    $statement->execute();
    
    //get owner account id
    $query="select a.account_id
            from flats f
            join accounts a on a.user_id = f.owner_id
            where f.flat_id=:id;
            ";
    $statement=$pdo->prepare($query);
    $statement->bindValue(':id',$id);
    $statement->execute();
    $owner=$statement->fetch();
    
    //send message to the owner

    $query = "insert into messages (receiver_account_id, sender_account_id, title, message_body, status) values 
                    (:receiver, :sender, :title, :mes, 'unread');";
    $statement = $pdo->prepare($query);

    $statement->bindValue(':receiver',$owner['account_id']);
    $statement->bindValue(':sender',$_SESSION['user']['account_id']);
    $statement->bindValue(':title','Rent offer accepted');
    $statement->bindValue(':mes',"The flat Rent has been accepted by the manager , the flat on the rent offer refference number is ".$refference_number);
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

            <main class="message-style">             
                    <?php
                       echo "<p>Owner Rent Offer Confirmed Successfully.</p>";
                    ?>
            </main>

        </section>

        <?php include('../includes/footer.php') ?>

    </body>
</html>
