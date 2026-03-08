<?php
    session_start();
    require_once('../database.inc.php');

    $query="select s.*, flat.flat_id as id , flat.refference_number as ref_id 
            from rentals s
            join flats flat on flat.flat_id=s.flat_id
            where s.customer_id=:user_id and s.is_approved=0;";
    $pdo=connect_db();
    $statement=$pdo->prepare($query);
    $statement->bindValue(':user_id',$_SESSION['user']['user_id']);
    $statement->execute();
    $rentals=$statement->fetchAll();
    $pdo=null;

    if(isset($_POST['confirm_rent'])){
        $_SESSION['rent_data']['form']['rent_id']=$flat_id;
        header('Location:ConfirmRent.php');
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

    <body>
        <?php include('../includes/header.php') ?>

        <section class="page-navigation-main">

            <?php include('../includes/leftSideNavigation.php') ?>

            <main class="table-area height-70vh">
                <table>
                    <thead>
                        <tr>
                            <th>flat refference</th>
                            <th>Start date</th>
                            <th>End date</th>
                            <th>Total amount</th>
                            <th>Continue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($rentals as $r){
                                echo "
                                    <tr>
                                        <td>
                                            <a href='FlatDetails.php?id=".$r['id']." target='_blank'>"
                                                .$r['ref_id'].
                                            "</a>
                                        </td>
                                        <td>".$r['start_date']."</td>
                                        <td>".$r['end_date']."</td>
                                        <td>".$r['total_amount']."</td>
                                        <td>
                                            <form action='cart.php' method='post'>
                                                <button name='confirm_rent' type='submit' class='confirm-button'>Go Confirm</button>
                                            </form>
                                        </td>
                                    </tr>
                                ";
                            }
                        ?>

                            

                    </tbody>
                </table>
            </main>

        </section>

        <?php include('../includes/footer.php') ?>

    </body>
</html>