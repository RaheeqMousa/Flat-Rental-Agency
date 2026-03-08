<?php
session_start();

require_once('../database.inc.php');
include_once('../classes/Flat.php');

/* ---------- DEFINE VARIABLES (PREVENT WARNINGS) ---------- */

$price = $_POST['monthly_rental_cost'] ?? "";
$location = $_POST['location'] ?? "";
$bedrooms = $_POST['bedrooms'] ?? "";
$bathrooms = $_POST['bathrooms'] ?? "";
$furnished = $_POST['furnished'] ?? "";

/* ---------- SORTING ---------- */

$sort  = $_GET['sort'] ?? ($_COOKIE['sort'] ?? 'monthly_rental_cost');
$order = $_GET['order'] ?? ($_COOKIE['order'] ?? 'asc');

setcookie('sort', $sort, time() + (24 * 60 * 60));
setcookie('order', $order, time() + (24 * 60 * 60));

function sortLink($column_name) {

    $sorting = $_GET['sort'] ?? ($_COOKIE['sort'] ?? 'monthly_rental_cost');
    $ord = $_GET['order'] ?? ($_COOKIE['order'] ?? 'asc');

    $sorting_image = 'sort-up-solid.svg';
    $next = 'desc';

    if ($column_name === $sorting) {
        if ($ord === 'asc') {
            $sorting_image = 'sort-up-solid.svg';
            $next = 'desc';
        } else {
            $sorting_image = 'sort-down-solid.svg';
            $next = 'asc';
        }
    }

    $url = "?sort=$column_name&order=$next";

    return "<a href='$url'>
                <img width='20' height='20' src='../Images/$sorting_image'>
            </a>";
}

/*.......DATABASE.....*/

$pdo = connect_db();

$query = "SELECT * FROM flats WHERE is_rented = 0 AND is_approved = 1";

/*................FILTERS..........*/

if (!empty($price)) {
    $query .= " AND monthly_rental_cost <= :price";
}

if (!empty($location)) {
    $query .= " AND LOWER(location) LIKE LOWER(:location)";
}

if (!empty($bedrooms)) {
    $query .= " AND bedrooms = :bedrooms";
}

if (!empty($bathrooms)) {
    $query .= " AND bathrooms = :bathrooms";
}

if (!empty($furnished)) {
    $query .= " AND furnished = :furnished";
}

/*......SORTING.....*/

$allowed_columns = ['monthly_rental_cost','bedrooms','bathrooms','location'];
$allowed_orders  = ['asc','desc'];

if (!in_array($sort, $allowed_columns)) {
    $sort = 'monthly_rental_cost';
}

if (!in_array($order, $allowed_orders)) {
    $order = 'asc';
}

$query .= " ORDER BY $sort $order";

$search_statement = $pdo->prepare($query);

if (!empty($price)) {
    $search_statement->bindValue(':price', floatval($price));
}

if (!empty($location)) {
    $search_statement->bindValue(':location', "%$location%");
}

if (!empty($bedrooms)) {
    $search_statement->bindValue(':bedrooms', $bedrooms);
}

if (!empty($bathrooms)) {
    $search_statement->bindValue(':bathrooms', $bathrooms);
}

if (!empty($furnished)) {
    $search_statement->bindValue(':furnished', $furnished);
}

/*.......EXECUTE....*/

$search_statement->execute();

$flats = $search_statement->fetchAll(PDO::FETCH_ASSOC);

$pdo = null;

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
                                <input id="bathrooms" type="number" min="0" step="1" name="bathrooms" placeholder="Number of bathrooms" values="<?php echo $bathrooms; ?>">

                                <div class="furnished-options">
                                    <label><input type="radio" name="furnished" value="Furnished"> Furnished</label>
                                    <label><input type="radio" name="furnished" value="Non-Furnished"> Non-Furnished</label>
                                    <label><input type="radio" name="furnished" value=""> Any</label>
                                </div>

                                <button class="filter-btn mt-8" type="submit">Filter</button>
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