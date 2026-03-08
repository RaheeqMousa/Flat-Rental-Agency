<?php

    require_once('../database.inc.php');
    session_start();

    $ref=isset($_SESSION['rent_data']['form']['refference_number']) ? $_SESSION['rent_data']['form']['refference_number']:'';
    $months= isset($_SESSION['rent_data']['form']['months'])? $_SESSION['rent_data']['form']['months']:'';
    $total=isset($_SESSION['rent_data']['form']['total_amount'])? $_SESSION['rent_data']['form']['months']:'';

    $pdo=connect_db();
    if(isset($_POST['expiry_date']) && isset($_POST['card_number']) && isset($_POST['card_name'])){
    
        $query="insert into payment_card(card_number,card_name,expiry_date) values (:card_number,:card_name,:expiry_date);";
        $statement = $pdo->prepare($query);

        $statement->bindValue(':card_number',$_POST['expiry_date']);
        $statement->bindValue(':card_name',$_POST['card_number']);
        $statement->bindValue(':expiry_date',$_POST['card_name']);
        $statement->execute();

        $query = "insert into messages ( sender_account_id,  title,receiver_account_id, message_body, status, confirm) values 
                    ( :sender, :title, :receiver, :mes,:status , :confirm);";
        $statement = $pdo->prepare($query);

        $statement->bindValue(':sender',$_SESSION['user']['account_id']);
        $statement->bindValue(':receiver',$_SESSION['rent_data']['form']['owner_account_id']);
        $statement->bindValue(':title','Rented Flat');
        $statement->bindValue(':status','unread');
        $statement->bindValue(':mes',"Customer ".$_SESSION['user']['name']." Rented your flat ".$_SESSION['rent_data']['form']['refference_number']."");
        $statement->bindValue(':confirm',$_SESSION['rent_id'].' '.$_SESSION['rent_data']['form']['flat_id'].' '.$_SESSION['user']['account_id']);
        $statement->execute();
        
        $query = "insert into messages (receiver_account_id, title, message_body, status) values 
                    (:receiver, :title, :mes, 'unread');";
        $statement = $pdo->prepare($query);
    
        $statement->bindValue(':receiver','3');
        $statement->bindValue(':title','Rental has been done');
        $statement->bindValue(':mes',$_SESSION['rent_data']['form']['owner_name']." flat ".$ref." is rented by ".$_SESSION['user']['name']." for ".$months." months ");
        $statement->execute();

        $pdo=null;
        header('Location:search.php');
    }

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>3lRaheeq agency</title>
        <link href="../main.css" rel="stylesheet">
    </head>

        <?php include('../includes/header.php') ?>

        <section class="page-navigation-main">

            <?php include('../includes/leftSideNavigation.php') ?>

            <main class="forms-style row flex-direction-column justify-content-center align-items-center gap-32 width-100">
                
                <form method="post" action="ConfirmRent.php" class="">
                    

                    <div class="shadow border-16 py-32 px-16 row  flex-direction-column justify-content-center align-items-center gap-32">
<h2>Confirm Rent</h2>
                    <div class="row flex-direction-column gap-8">           
                        <p>Flat Reference Number: <?php echo $ref; ?></p>
                        <p>Rental Duration: <?php echo $months ?> month</p>
                        <p>Total Rent: <?php echo $total ?></p>
                    </div>

                    <div class="row width-100">
                        <label for="card_number">Credit Card Number (9 digits):</label>
                        <input type="text" id="card_number" name="card_number" placeholder="123412340" pattern="\d{9}" required class="required"><br>
                    </div>

                    <div class="row width-100">
                        <label for="expiry_date">Expiry Date:</label>
                        <input type="date" id="expiry_date" name="expiry_date" required class="required"><br>   
                    </div>

                    <div class="row width-100">
                        <label for="card_name">Card Name:</label>
                        <input type="text" id="card_name" name="card_name" placeholder="Raheeq Mousa" required class="required"><br>
                    </div>
<button type="submit">Confirm Rent</button>
                    </div>

                    
                </form>
            </main>

        </section>


        <?php include('../includes/footer.php') ?>

    </body>
</html>