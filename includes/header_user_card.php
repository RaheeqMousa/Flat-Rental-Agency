<?php
if(!isset($index_page)){
    require_once('../database.inc.php');
} else {
    require_once('database.inc.php');
}

$user_id = $_SESSION['user']['user_id'] ?? null;
$role = $_SESSION['user']['role'] ?? '';

if($user_id === null) return;

$pdo = connect_db();
$query = "SELECT name, image FROM users WHERE user_id = :user_id;";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch();
$pdo = null;

$img_path = isset($index_page) ? 'Images/' : '../Images/';
$profile_link = isset($index_page) ? 'pages/profile.php' : 'profile.php';
$img_file = $user['image'] ? $user['image'] : 'profiles.jpg';
$title = htmlspecialchars($user['name'] . "'s image");
?>

<section class='header-card <?php echo htmlspecialchars($role); ?>'>
    <a href='<?php echo $profile_link; ?>'>
        <img src='<?php echo $img_path . $img_file; ?>' width='20' height='20' alt='User Photo' title='<?php echo $title; ?>'>
    </a>
    <p><?php echo htmlspecialchars($user['name']); ?></p>
    <a href='<?php echo $profile_link; ?>'>Profile</a>
</section>