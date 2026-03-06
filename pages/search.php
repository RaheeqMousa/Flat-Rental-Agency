<?php
    session_start();

    require_once('../database.inc.php');
    include_once('../classes/Flat.php');
    $sort =$_GET['sort'] ??  ($_COOKIE['sort'] ?? 'monthly_rental_cost');
    $order=$_GET['order']  ?? ($_COOKIE['order'] ?? 'asc');
    
    setcookie('sort', $sort, time() + (24 * 60 * 60));
    setcookie('order', $order, time() + (24 * 60 * 60));

    function sortLink($column_name) {
        $sorting = $_GET['sort'] ?? $sorting = ($_COOKIE['sort'] ?? 'monthly_rental_cost');
        $ord =$_GET['order']  ?? ($_COOKIE['order'] ?? 'asc');
        
        $sorting_image = 'sort-up-solid.svg';
        $alt = 'This is sorting up image';
        $title = $alt;
        $next='desc';
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

    if(!isset($_GET['order']) && isset($_COOKIE['order']) )
        $order = $_COOKIE['order'];
    if(!isset($_GET['sort']) && isset($_COOKIE['sort']))
        $sort=$_COOKIE['sort'];
        
    
    $pdo = connect_db();
    $query = "select * from flats where is_rented = 0 and is_approved=1";

    if( isset($_POST['price']) || isset($_POST['bedrooms']) || isset($_POST['furnished']) || isset($_POST['location'])){
    
        if (isset($_POST['monthly_rental_cost']) && !empty(trim($_POST['monthly_rental_cost']))) {
            $query.=" and monthly_rental_cost<=:price";
        }
        if(isset($_POST['location'])  && !empty(trim($_POST['location']))){    
            $query.=" and lower(location) like lower(:location)";
        }
        if(isset($_POST['furnished']) && !empty(trim($_POST['furnished']))){
            $query.=" and furnished=:furnished";
        }
        if(isset($_POST['bedrooms']) && !empty(trim($_POST['bedrooms']))){
            $query.=" and bedrooms=:bedrooms";
        }
        if(isset($_POST['bathrooms']) && !empty(trim($_POST['bathrooms']))){
            $query.=" and bathrooms=:bathrooms";
        }
     
    }

    $query.=" ORDER BY $sort $order;";

    $search_statement = $pdo->prepare($query);
    
    $price=isset($_POST['monthly_rental_cost']) ? $_POST['monthly_rental_cost']:"";
    $bedrooms=isset($_POST['bedrooms']) ? $_POST['bedrooms']:"";
    $bathrooms=isset($_POST['bedrooms']) ? $_POST['bedrooms']:"";
    $furnished=isset($_POST['furnished']) ? $_POST['furnished']:"";
    $location=isset($_POST['location']) ? $_POST['location']:"";

    if(isset($_POST['location'])  && !empty(trim($_POST['location']))) {
        $name = trim($_POST['location']);
        $search_statement->bindValue(':location', "%$location%");
    }
    if (isset($_POST['monthly_rental_cost']) && !empty($_POST['monthly_rental_cost'])) {
        $price = floatval(trim($_POST['monthly_rental_cost']));
        $search_statement->bindValue(':price', $price);
    }
    if(isset($_POST['bedrooms']) && !empty(trim($_POST['bedrooms']))) {
        $bedrooms = trim($_POST['bedrooms']);
        $search_statement->bindValue(':bedrooms', $bedrooms);
    }
    if(isset($_POST['furnished']) && !empty(trim($_POST['furnished']))) {
        $furnished = trim($_POST['furnished']);
        $search_statement->bindValue(':furnished', $furnished);
    }
    if(isset($_POST['bathrooms']) && !empty(trim($_POST['bathrooms']))){
        $bathrooms=trim($_POST['bathrooms']);
        $search_statement->bindValue(':bathrooms', $bathrooms);
    }
    

    $search_statement->execute();
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
                    <section>
                            <form action="search.php" method="POST" class="search-set">
                                <h2>Filter Flats</h2>
                                <label for="price">Monthly Cost:</label>
                                <input id="price" type="number" min="0" step="0.01" name="monthly_rental_cost" placeholder="Enter price" value="<?php echo $price; ?>">
                                <label for="location">Location:</label>
                                <input id="location" type="text" name="location" placeholder="Enter location" value="<?php echo $location; ?>">
                                <label for="bedrooms">Bedrooms:</label>
                                <input id="bedrooms" type="number" min="0" step="1" name="bedrooms" placeholder="Number of bedrooms" value="<?php echo $bedrooms; ?>">
                                <label for="bathrooms">Bathrooms:</label>
                                <input id="bathrooms" type="number" min="0" step="1" name="bathrooms" placeholder="Number of bathrooms" values="<?php echo $bathrooms; ?>"><br>
                                <div class="furnished-options">
                                    <label><input type="radio" name="furnished" value="Furnished"> Furnished</label>
                                    <label><input type="radio" name="furnished" value="Non-Furnished"> Non-Furnished</label>
                                    <label><input type="radio" name="furnished" value=""> Any</label>
                                </div>
                                <button type="submit">Filter</button>
                            </form>      
                    </section>
                    <section>
                        <table border="1">
                            <thead>
                                <tr>
                                    <th>photo</th>
                                    <th>
                                        <?php echo sortLink('refference_number') ?>
                                       Flat reference number
                                    </th>
                                    <th>
                                        <?php echo sortLink('monthly_rental_cost') ?>
                                       Monthly rental cost
                                    </th>
                                    <th>
                                        <?php echo sortLink('available_date_from') ?>
                                        Availability date
                                    </th>
                                    <th>
                                        <?php echo sortLink('location') ?>
                                        loaction
                                    </th>
                                    <th>
                                        <?php echo sortLink('number_of_bedrooms') ?>
                                        Number of bedrooms
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  
                                    while ($row = $search_statement->fetchObject('Flat')) {
                                       
                                        echo $row->getTableRow();
                                    }
                                ?>
                            </tbody>
                        </table>
                    </section>
            </main>

        </section>

        <?php include('../includes/footer.php') ?>

    </body>
</html>