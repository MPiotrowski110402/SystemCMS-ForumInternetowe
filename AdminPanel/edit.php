<?php
    include('../session.php');
    include('../connect_db.php');
    
    global $conn;
    $id = $_GET["id"];
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];
    $email = $row['email'];
    $role = $row['role'];
    echo $username . ' ' . $email . ' ' .$id;


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['login']) && isset($_POST['role']) && isset($_POST['email'])){
        $new_login = $_POST['login'];
        $new_role = $_POST['role'];
        $new_email = $_POST['email'];
        $sql = "UPDATE users SET username = '$new_login', role = '$new_role', email = '$new_email' WHERE id = $id";
        mysqli_query($conn, $sql);
        header("Location: adminPanel.html");
        exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <p>
            <?php 
                echo "Aktualny login: ".$username; 
                echo '<br/><input type="text" name="login" placeholder="Wpisz nowy login">';
            ?>
        </p>
        <p>
            <?php 
                echo "Aktualna rola: ".$role; 
                echo '<br/><select name="role">
                    <option value="user">Użytkownik</option>
                    <option value="admin">Admin</option>
                </select>';
            ?>
        </p>
        <p>
            <?php 
                echo "Aktualny email: ".$email; 
                echo '<br/><input type="text" name="email" placeholder="Wpisz nowy email">
                <br/>
                <input type="submit" value="Prześlij">';
            ?>
        </p>
    </form>
</body>
</html>
