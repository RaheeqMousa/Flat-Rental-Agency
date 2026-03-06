<?php
    require_once('../database.inc.php');
    include_once('../classes/Flat.php');
    session_start();

    $user=isset($_SESSION['user'])??null;

    if(isset($_GET['buttonAction']) && $user==null){
        $_SESSION['redirect_after_login'] = $_GET['buttonAction'];
        header("Location:../auth/login.php");
    }
    else if (isset($_GET['buttonAction']) && $user!=null){
        $loc=$_GET['buttonAction'];
        header("Location: ".$loc);
    }
    
    $id=$_GET['id']??null;
    $pdo = connect_db();

    $query = "select * from flats where flat_id=:id;";
    $flat=null;
    $statement = $pdo->prepare($query);
    if($id){
        $statement->bindValue(':id',$id);
        $statement->execute();
        $flat=$statement->fetchObject('Flat');
    } 
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

        <section class="page-navigation-main height-70vh">

            <?php include('../includes/leftSideNavigation.php') ?>

            <main>
                <section class='flat-detail gap-32'>
                    <?php
                        if($flat){
                           echo $flat->getFlatDetail();
                        }
                    ?>    
                </section>
            </main>

        </section>

        <?php include('../includes/footer.php') ?>
        <script src="../main.js"></script>
    </body>
</html>