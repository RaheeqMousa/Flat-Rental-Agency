<?php
    session_start();
    require_once('../database.inc.php');
    $date_of_today=Date('Y-m-d');
    
    $customer_id=isset($_SESSION['user']['account_id']) ? $_SESSION['user']['account_id']: null;

    if($customer_id==null){
        echo "that account id does not exist!";
    }

    $sort =$_GET['sort'] ??  ($_COOKIE['InquireSort'] ?? '');
    $order=$_GET['order']  ?? ($_COOKIE['InquireOrder'] ?? '');
    
    setcookie('InquireSort', $sort, time() + (24 * 60 * 60));
    setcookie('InquireOrder', $order, time() + (24 * 60 * 60));

    function sortLink($column_name) {
        $sorting = $_GET['sort'] ?? $sorting = ($_COOKIE['InquireSort'] ?? '');
        $ord =$_GET['order']  ?? ($_COOKIE['InquireOrder'] ?? '');
        
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

    if(!isset($_GET['order']) && isset($_COOKIE['InquireOrder']) )
        $order = $_COOKIE['InquireOrder'];
    if(!isset($_GET['sort']) && isset($_COOKIE['InquireSort']))
        $sort=$_COOKIE['InquireSort'];

    $pdo = connect_db();
    $query="select r.start_date, r.end_date, flat.location as location , flat.refference_number as refference_number, flat.monthly_rental_cost as monthly_rental_cost,
            owner.name as owner_name, owner.user_id as owner_id, customer.name as customer_name, customer.user_id as customer_id
            from rentals r
            join flats flat on flat.flat_id=r.flat_id
            join users owner on owner.user_id=flat.owner_id
            join users customer on customer.user_id=r.customer_id where 1";


    if( isset($_POST['available_on']) || isset($_POST['from_date']) || isset($_POST['to_date']) || isset($_POST['location'])
             || isset($_POST['owner_name']) || isset($_POST['customer_name']) ){


    
        if (isset($_POST['available_on']) && !empty(trim($_POST['available_on']))) {
            $query.=" and r.start_date <= :available_on and r.end_date >= :available_on";
        }
        if(isset($_POST['from_date'])  && !empty(trim($_POST['from_date'])) && isset($_POST['to_date']) && !empty(trim($_POST['to_date']))){    
            $query.=" and r.start_date >= :from_date and r.end_date <= :to_date";
        }
        if(isset($_POST['location']) && !empty(trim($_POST['location']))){
            $query.=" and lower(flat.location)=lower(:location)";
        }
        if(isset($_POST['owner_name']) && !empty(trim($_POST['owner_name']))){
            $query.=" and lower(owner.name) like lower(:owner_name)";
        }
        if(isset($_POST['customer_name']) && !empty(trim($_POST['customer_name']))){
            $query.=" and lower(customer.name) like lower(:customer_name)";
        }
    }


    if($sort!=null && $order!=null)
        $query.=" ORDER BY $sort $order;";

    $statement = $pdo->prepare($query);
    
    $available_on=isset($_POST['available_on']) ? $_POST['available_on']: "";
    $from_date=isset($_POST['from_date']) ? $_POST['from_date']: "";
    $to_date=isset($_POST['to_date']) ? $_POST['to_date']: "";
    $location=isset($_POST['location']) ? $_POST['to_date']:"";
    $owner_name=isset($_POST['owner_name']) ? $_POST['to_date']:"";
    $customer_name=isset($_POST['customer_name']) ?$_POST['customer_name']: "";

    if (isset($_POST['available_on']) && !empty(trim($_POST['available_on']))) {
        $available_on = trim($_POST['available_on']);
        $statement->bindValue(':available_on', $available_on);
    }
    if(isset($_POST['from_date'])  && !empty(trim($_POST['from_date'])) && isset($_POST['to_date']) && !empty(trim($_POST['to_date']))){   
        $from_date = trim($_POST['from_date']);
        $to_date = trim($_POST['to_date']);
        $statement->bindValue(':from_date', $from_date);
        $statement->bindValue(':to_date', $to_date);
    }
    if(isset($_POST['location']) && !empty(trim($_POST['location']))){
        $name = trim($_POST['location']);
        $statement->bindValue(':location', $location);
    }
    if(isset($_POST['owner_name']) && !empty(trim($_POST['owner_name']))){
        $name = trim($_POST['owner_name']);
        $statement->bindValue(':owner_name', "%$name%");
    }
    if(isset($_POST['customer_name']) && !empty(trim($_POST['customer_name']))){
        $name = trim($_POST['customer_name']);
        $statement->bindValue(':customer_name', "%$name%");
    }
    
    

    $statement->execute();
    $rentals=$statement->fetchAll();

    $pdo=null;
?>

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
            <main class="table-area height-70vh">
                <section >
                    <h2>Flats Inquire Search</h2>
                    <form action="InquireFlats.php" method="post" class="search-set">
                        <label for="location">Location:</label>
                        <input type="text" name="location" id="location" value="<?php echo $location ?>">

                        <label for="available_on">Available On:</label>
                        <input type="date" id="available_on" name="available_on" value="<?php echo $available_on ?>">

                        <label for="from_date">From Date:</label>
                        <input type="date" id="from_date" name="from_date" value="<?php echo $from_date ?>">

                        <label for="to_date">To Date:</label>
                        <input type="date" id="to_date" name="to_date" value="<?php echo $to_date ?>">
                        <br>
                        <label for="owner_name">Owner Name:</label>
                        <input type="text" id="owner_name" name="owner_name" value="<?php echo $owner_name ?>">

                        <label for="customer_name">Customer Name:</label>
                        <input type="text" id="customer_name" name="customer_name" value="<?php echo $customer_name ?>">

                        <button type="submit">Search</button>
                    </form>
                </section>
                <table>
                        <thead>
                            <tr>
                                <th>
                                    <?php echo sortLink("refference_number") ?>
                                    Flat Refference Number
                                </th>
                                <th>
                                    <?php echo sortLink("monthly_rental_cost") ?>
                                    Monthly rental cost
                                </th>
                                <th>
                                    <?php echo sortLink("start_date") ?>
                                    Start Date
                                </th>
                                <th>
                                    <?php echo sortLink("end_date") ?>
                                    End Date
                                </th>
                                <th>
                                    <?php echo sortLink("location") ?>
                                    Location
                                </th>
                                <th>
                                    <?php echo sortLink("owner_name") ?>
                                    Owner
                                </th>
                                <th>
                                    <?php echo sortLink("customer_name") ?>
                                    Customer
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
                                                <a href='user_card.php?id=". $rent['refference_number']. " target='_blank'>".
                                                    $rent['refference_number']
                                                ."</a>
                                            </td>
                                            <td>". $rent['monthly_rental_cost']. "</td>
                                            <td>". $rent['start_date']. "</td>
                                            <td>". $rent['end_date']. "</td>
                                            <td>". $rent['location']. "</td>
                                            <td>
                                                <a href='user_card.php?id=".$rent['owner_id']." target='_blank'>".
                                                    $rent['owner_name']
                                                ."</a>
                                            </td>
                                            <td>
                                                <a href='user_card.php?id=".$rent['customer_id']." target='_blank'>".
                                                    $rent['customer_name']
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