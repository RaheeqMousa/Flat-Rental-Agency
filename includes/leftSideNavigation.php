<?php
    $user = isset($_SESSION['user']['account_id']) ? $_SESSION['user']['account_id'] : null;
    $role = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : null;

    $current_page = basename($_SERVER['PHP_SELF']);

    // Base path for links depending on index page or not
    $base_path = isset($index_page) ? 'pages/' : '../pages/';

    // Helper function to render a menu item
    function renderMenuItem($href, $label, $current_page_name, $icon = null) {
        global $current_page, $base_path;
        $active = ($current_page == $current_page_name) ? 'class="active"' : '';
        // Only prepend base_path to href, icon uses its own path
        $icon_html = $icon ? "<img src='{$icon}' width='20' height='20'> " : '';
        echo "<li $active><a href='{$base_path}{$href}'>{$icon_html}{$label}</a></li>";
    }
?>

<nav class="navigation">
    <ul>
        <?php
        if ($user) {
            // Owner menu
            if ($role === 'owner') {
                renderMenuItem('OfferFlat.php', 'Offer Flats', 'OfferFlat.php', isset($index_page) ? 'Images/house-solid-full.svg' : '../Images/house-solid-full.svg');
            } 
            // Customer menu
            elseif ($role === 'customer') {
                renderMenuItem('search.php', 'Flat Search', 'search.php', isset($index_page) ? 'Images/magnifying-glass-solid.svg' : '../Images/magnifying-glass-solid.svg');
                renderMenuItem('ViewRentedFlats.php', 'View Rented Flats', 'ViewRentedFlats.php', isset($index_page) ? 'Images/house-solid-full.svg' : '../Images/house-solid-full.svg');
            } 
            // Manager menu
            elseif ($role === 'manager') {
                renderMenuItem('InquireFlats.php', 'Inquire Flats', 'InquireFlats.php');
            }

            // Include messages
            // include($base_path . 'message.php');

        } else {
            // Guest menu
            renderMenuItem('search.php', 'Flat Search', 'search.php', isset($index_page) ? 'Images/magnifying-glass-solid.svg' : '../Images/magnifying-glass-solid.svg');
        }
        ?>
    </ul>
</nav>