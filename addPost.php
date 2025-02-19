<?php
    include('session.php');
    include('connect_db.php');
    global $conn;


    if (!isset($_SESSION['logined']) || $_SESSION['logined'] !== true) {
        header('Location: login.php');
        exit();
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['dodajPost'])) {

            if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['category']) && !empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['category'])) {
                $title = $_POST['title'];
                $content = $_POST['content'];
                $user_id = $_SESSION['user_id'];
                $category = $_POST['category'];


                $sql = "INSERT INTO posts (title, content, user_id, category_id) VALUES ('$title', '$content', '$user_id', '$category')";
                if (mysqli_query($conn, $sql)) {
                    $post_id = mysqli_insert_id($conn);

                    header("Location: post.php?id=" . $post_id);
                    exit(); 
                } else {
                    echo "Błąd podczas dodawania posta: " . mysqli_error($conn);
                }
            } else {
                echo "Wszystkie pola są wymagane.";
            }
        }
    }


    function category() {
        global $conn;
        $sql = "SELECT * FROM category";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row['id'].'">'.$row['category'].'</option>';
        }
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Post</title>
    <link rel="stylesheet" href="style_addPosts.css">
</head>
<body>
    <div class="container">
        <div class="post-form-container">
            <h3>Dodaj Nowy Post</h3>
            <form action="addPost.php" method="POST" enctype="multipart/form-data">
                <label for="title">Tytuł:</label>
                <input type="text" id="title" name="title" required placeholder="Tytuł Twojego posta">

                <label for="content">Treść Posta:</label>
                <textarea id="content" name="content" rows="6" required placeholder="Wpisz treść swojego posta"></textarea>
                <label for="category">Wybierz kategorię:</label>
                <select name="category" id="category" required>
                    <option value="" disabled selected>Wybierz kategorię</option>
                    <?php category(); ?>
                </select>
                <input type="submit" name="dodajPost" value="Dodaj Post" class="btn-submit">

            </form>
        </div>
    </div>
</body>
</html>
