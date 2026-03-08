<?php
$user = $_SESSION['user']['account_id'] ?? null;
$role = $_SESSION['user']['role'] ?? null;
$current_page = basename($_SERVER['PHP_SELF']);

// Base paths for links depending on index page
$base_path = isset($index_page) ? '' : '../';

// Helper function to render header menu items
function renderHeaderMenuItem($href, $label, $current_page_name, $icon = null) {
    global $current_page, $base_path;
    $active = ($current_page === $current_page_name) ? 'class="active"' : '';
    
    // Icon path should be independent of base_path
    $icon_html = $icon ? "<img src='{$icon}' width='24' height='24' style='margin-right:5px;' />" : '';
    
    echo "<a href='{$base_path}{$href}' {$active}>{$icon_html}{$label}</a>";
}

// Function to get correct image path
function getImagePath($filename) {
    global $index_page;
    return isset($index_page) ? "Images/{$filename}" : "../Images/{$filename}";
}
?>

<header class="row justify-content-between align-items-center">
    <!-- Logo -->
    <section class="row align-items-center gap-8">
        <img src="<?php echo getImagePath('logo.svg'); ?>" alt="3lRaheeq agency logo" title="3lRaheeq agency" width="150" height="50" />
        <h1>3lRaheeq agency</h1>
    </section>

    <!-- Main navigation -->
    <nav class="row gap-16 justify-content-center">
        <?php
        renderHeaderMenuItem('index.php', 'Home', 'index.php');
        renderHeaderMenuItem('pages/AboutUs.php', 'About Us', 'AboutUs.php');
        ?>
    </nav>

    <!-- User navigation -->
    <nav class="row gap-16 justify-content-center">
        <?php
        if ($user) {
            // Include user card
            include($base_path . 'includes/header_user_card.php');
            renderHeaderMenuItem('pages/message.php', '', 'message.php', getImagePath('notification.svg'));
            if ($role === 'customer') {
                renderHeaderMenuItem('pages/cart.php', '', 'cart.php', getImagePath('cart.svg')); // icon only
                
            }
            renderHeaderMenuItem('auth/logout.php', '', 'logout.php', getImagePath('logout.svg'));

        } else {
            renderHeaderMenuItem('auth/register.php', 'Sign Up', 'register.php');
            renderHeaderMenuItem('auth/login.php', 'Log In', 'login.php');
        }
        ?>
    </nav>
</header>