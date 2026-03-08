<?php
    session_start();
    require_once '../database.inc.php';

    $message_id = $_GET['id'] ?? null;

    $pdo=connect_db();
    $query="select * from messages where message_id = :id;";

    $statement = $pdo->prepare($query);
    $statement->bindValue(':id',$message_id);
    $statement->execute();

    $message = $statement->fetch();

    if($message['status']==='unread'){
        $query="update messages set status='read' where message_id=:id;";
        $statement=$pdo->prepare($query);
        $statement->bindValue(':id',$message_id);
        $statement->execute();
    }
    
    //get sender name
    $sender_user=null;
    if($message['sender_account_id']){
        $query="select user.* 
                from accounts a
                join users user on user.user_id=a.user_id
                where a.account_id=:id;";
                
        $statement = $pdo->prepare($query);
        $statement->bindValue(':id',$message['sender_account_id']);
        $statement->execute();
        $sender_user=$statement->fetch();
    }
    
    $pdo=null;

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>3lRaheeq agency</title>
        <link rel="stylesheet" href="../main.css">
    </head>

    <body>
        <?php include('../includes/header.php') ?>
    
        <section class="page-navigation-main">
            <?php include('../includes/leftSideNavigation.php') ?>

            <main class="row flex-direction-column justify-content-center height-70vh ">
                <section class="row flex-direction-column justify-content-center gap-16 shadow py-16 px-16 border-16">
                    <h2 class="mb-16"><?php echo $message['title'] ?></h2>
                    <p>
                        <strong>From:</strong> 
                        <?php echo $message['sender_account_id'] ? $sender_user['name'] . ", user ID: " . $sender_user['user_id'] : 'System' ?>
                    </p>
                    <p>
                        <strong>Sent At:</strong> 
                        <?php echo $message['sent_at']?>
                    </p>
                    <p>
                        <strong>Message:</strong>
                        <?php echo $message['message_body'] ?>
                    </p>

                    <section class="row justify-content-center gap-16 mt-8">
                    <?php
                            if($message['confirm']){

                                echo " <form action='RejectActionForMessages.php' method='post'>
                                            <input type='hidden' name='id' value='".$message['confirm']."'>
                                            <input type='hidden' name='title' value='".$message['title']."'>
                                            <button type='submit' class='cancel-button'>Reject</button>
                                        </form>";

                            if($message['title']==="New flat pending approval"){

                                echo " <form action='ConfirmRentOffer.php' method='post'>
                                            <input type='hidden' name='id' value='".$message['confirm']."'>
                                            <button type='submit' class='confirm-button'>Confirm</button>
                                        </form>";
                            }
                            if($message['title']==="Rented Flat"){
                                echo " <form action='ConfirmCustomerRent.php' method='post'>
                                        <input type='hidden' name='id' value='".$message['confirm']."'>
                                        <button type='submit' class='confirm-button'>Confirm</button>
                                    </form>";
                            }
                            if($message['title']==="preview request"){
                                
                                echo " <form action='ConfirmPreview.php' method='post'>
                                        <input type='hidden' name='id' value='".$message['confirm']."'>
                                        <button type='submit' class='confirm-button'>Confirm</button>
                                    </form>";
                            }
                        
                        }
                    
                    ?>
                    </section>

                    <p><a href="../index.php">Back</a></p>
                </section>
                
            </main>
        </section>


        
        <?php include('../includes/footer.php') ?>

    </body>
</html>
