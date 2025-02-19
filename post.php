<?php 
    include('session.php');
    include('connect_db.php');
    include('modules.php');
    // $id_post = $_GET['id'];
    // echo $id_post;
    // showPost();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post - Forum</title>
    <link rel="stylesheet" href="styles.css"> <!-- Zewnętrzny plik CSS -->
</head>
<body>

    <!-- Górna nawigacja -->
    <header class="navbar">
        <div class="logo">
            <a href="index.php">Forum</a>
        </div>
        <nav>
        <ul>
                <li><a href="index.php">Strona Główna</a></li>
                <?php logInLogOut();?>
                <?php Registered();?>
                <?php adminPanel();?>
            </ul>
        </nav>
    </header>

    <!-- Główna sekcja posta -->
    <main class="post-container">
        <?php showPost();?>
    </main>

    <!-- Dolny pasek -->
    <footer class="footer">
        <p>&copy; 2025 Forum. Wszelkie prawa zastrzeżone.</p>
    </footer>

</body>
</html>
