<?php
    include('session.php');
    include('modules.php');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Internetowe</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
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
    
        <div class="form-container">
            <?php
                newPost();
                searchInput();
            ?>
            
        </div>



    
    <main>
        <div class="container">
        <div class="category-container">
    <h2>Kategorie Forum</h2>
    <ul class="category-list">
        <li><a href="index.php">Wszystkie</a></li>
        <?php categoryList();?>
    </ul>
</div>
            <section class="threads">
                <h2>Wątki Forum</h2>
                <div class="thread-list">
                    <?php 
                    listPost();
                    ?>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Forum Internetowe</p>
    </footer>
</body>
</html>
