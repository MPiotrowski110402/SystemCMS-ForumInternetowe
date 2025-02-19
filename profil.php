<?php 
    include('session.php');
    include('connect_db.php');
    include('modules.php');
    include('modules_profil.php');
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
    <link rel="stylesheet" href="profil.css"> <!-- Wewnętrzny plik CSS -->
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
    <main class="post-container">
        <div class="container">
            <?php
                profileDescription();


            ?>
            
    </main>
    <footer class="footer">
        <p>&copy; 2025 Forum. Wszelkie prawa zastrzeżone.</p>
    </footer>

</body>
</html>
