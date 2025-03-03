<?php
    include('../session.php');
    include('../connect_db.php');
    
    global $conn;
    $id = isset($_GET["id"]) ? (int)$_GET['id'] : 0;
    if($id <= 0){
        die("nieprawidłowy identyfikator");
    }
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Błąd zapytania: ". mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    if(!$row){
        die("Nie znaleziono użytkownika o podanym identyfikatorze");
    }
    $username = $row['username'];
    $email = $row['email'];
    $role = $row['role'];
    
    echo $username . ' ' . $email . ' ' .$id;


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['login']) && isset($_POST['role']) && isset($_POST['email'])){


        $new_login = htmlspecialchars(trim($_POST['login']));
        $new_role = htmlspecialchars(trim($_POST['role']));
        $new_email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        if(!filter_var($new_email, FILTER_VALIDATE_EMAIL)){
            die("Nieprawidłowy email");
        }
        $sql = "UPDATE users SET username = ?, role = ?, email = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if(!$stmt) {
            die("Błąd zapytania: ". mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "sssi", $new_login, $new_role, $new_email, $id);
        mysqli_stmt_execute($stmt);

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
