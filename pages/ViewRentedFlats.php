<?php

    session_start();
    require_once('../database.inc.php');
    $customer_id=isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id']: null;

    if($customer_id==null){
        echo "that account id does not exist!";
    }


    $sort =$_GET['sort'] ??  ($_COOKIE['rentalSort'] ?? 'start_date');
    $order=$_GET['order']  ?? ($_COOKIE['rentalOrder'] ?? 'desc');
    
    setcookie('rentalSort', $sort, time() + (24 * 60 * 60));
    setcookie('rentalOrder', $order, time() + (24 * 60 * 60));

    function sortLink($column_name) {
        $sorting = $_GET['sort'] ?? $sorting = ($_COOKIE['rentalSort'] ?? 'start_date');
        $ord =$_GET['order']  ?? ($_COOKIE['rentalOrder'] ?? 'desc');
        
        $sorting_image = 'sort-down-solid.svg';
        $alt = 'This is sorting down image';
        $title = $alt;
        $next='asc';
        if ($column_name === $sorting) {
            if ($ord === 'asc') {
                $sorting_image = 'sort-up-solid.svg';
                $alt = 'This is sorting up image';
                $title = $alt;
                $next = 'desc'; 
            } else {
                $sorting_image = 'sort-down-solid.svg';
                $alt = 'This is sorting down image';
                $title = $alt;
                $next = 'asc';
            }
        }
        $url = "?sort=$column_name&order=$next";
        return "<a href='$url'>
                    <img width='20' height='20' src='../Images/$sorting_image' alt='$alt' title='$title'>
                </a>";
    }

    if(!isset($_GET['order']) && isset($_COOKIE['rentalOrder']) )
        $order = $_COOKIE['rentalOrder'];
    if(!isset($_GET['sort']) && isset($_COOKIE['rentalSort']))
        $sort=$_COOKIE['rentalSort'];

    $pdo = connect_db();
    $query="select s.start_date, s.end_date, flat.location , flat.refference_number,
            flat.monthly_rental_cost as monthly_rental_cost, owner.name as name, owner.user_id as owner_id
            from rentals s
            join flats flat on flat.flat_id=s.flat_id
            join users owner on owner.user_id=flat.owner_id 
            join users customer on customer.user_id=s.customer_id 
            where customer.user_id=:id";

    $query.=" ORDER BY $sort $order;";

    $statement = $pdo->prepare($query);

    $statement->bindValue(':id',$customer_id);
    $statement->execute();
    $rentals=$statement->fetchAll();

    $date_of_today=date('Y-m-d');

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

            <main class="table-area height-70vh">
                <table>
                    <thead>
                        <tr>
                            <th>
                                <?php echo sortLink('flat.refference_number') ?>
                                Flat refference
                            </th>
                            <th>
                                <?php echo sortLink('flat.monthly_rental_cost') ?>
                                Monthly rental cost
                            </th>
                            <th>
                                <?php echo sortLink('s.start_date') ?>
                                Start date
                            </th>
                            <th>
                                <?php echo sortLink('s.end_date') ?>
                                End date
                            </th>
                            <th>
                                <?php echo sortLink('flat.location') ?>
                                location
                            </th>
                            <th>
                                <?php echo sortLink('owner.name') ?>
                                Owner name
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($rentals as $rent){
                                $status="";
                                if($rent['end_date']>$date_of_today){
                                    $status="past";
                                }else{
                                    $status="current";
                                }
                                echo "
                                    <tr class='$status'>
                                        <td>
                                            <a href='user_card.php?id=". $rent['owner_id']. " target='_blank'>".
                                                $rent['refference_number']
                                            ."</a>
                                        </td>
                                        <td>". $rent['monthly_rental_cost']. "</td>
                                        <td>". $rent['start_date']. "</td>
                                        <td>". $rent['end_date']. "</td>
                                        <td>". $rent['location']. "</td>
                                        <td>
                                            <a href='user_card.php?id=".$rent['owner_id']."' target='_blank'>".
                                                $rent['name']
                                            ."</a>
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