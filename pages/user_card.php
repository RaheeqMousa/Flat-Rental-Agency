<?php
    session_start();
    require_once '../database.inc.php';

    $id = isset($_GET['id'])? $_GET['id']:'';

    $pdo=connect_db();
    $query="select user.user_id, user.name as name, a.city as city, user.telephone_number as telephone_number, user.email as email,
            user.mobile_number as mobile_number
            from users user
            join address a on a.address_id=user.address_id
            where user.user_id=:user_id;";

    $statement=$pdo->prepare($query);
    $statement->bindValue(':user_id',$id);
    $statement->execute();
    $user_card = $statement->fetch();

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
        
        <?php include('../includes/header.php'); ?>

        <section class="page-navigation-main">

            <?php include('../includes/leftSideNavigation.php') ?>

            <main class="height-70vh row justify-content-center">                
                <div class="card shadow px-32 py-64 border-16 row flex-direction-column gap-16">
                    <h2><?php echo $user_card['name']; ?></h2>
                    <p><strong>City:</strong> <?php echo $user_card['city']; ?></p>
                    <p>
                        <span>
                            <img src="../Images/phone-solid.svg" width="30" height="30" alt="telephone image" title="this is telephone image"> 
                        </span>
                        <?php echo $user_card['telephone_number']; ?>
                    </p>

                    <p>
                        <span>
                            <img src="../Images/mobile-screen-solid.svg" width="30" height="30" alt="mobile image" title="this is mobile image"> 
                        </span>
                        <?php echo $user_card['mobile_number']; ?>
                    </p>
                    <p>
                        <span>
                            <img src="../Images/envelope-solid.svg" width="30" height="30" alt="phone image" title="phone image"> 
                        </span>
                        <?php echo $user_card['email']; ?>
                    </p>
                </div>
            </main>

        </section>

        <?php include('../includes/footer.php'); ?>

    </body>
</html>
