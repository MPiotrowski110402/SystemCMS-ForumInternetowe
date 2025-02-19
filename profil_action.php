<?php
    include('session.php');
    include('connect_db.php');
    global $conn;
$action = $_GET['action'] ?? 'default';

switch ($action) {
    case 'edit':
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $oLogin = $_POST['current_username'];
            $nLogin = $_POST['new_username'];
            $oEmail = $_POST['current_email'];
            $nEmail = $_POST['new_email'];
            if(!empty($oLogin) &&!empty($nLogin) &&!empty($oEmail) &&!empty($nEmail)){
                if($oLogin == $_SESSION['username'] && $oEmail == $_SESSION['email']){
                    $sql = "UPDATE users SET username = '$nLogin', email = '$nEmail' WHERE username = '$oLogin' AND email = '$oEmail'";
                    mysqli_query($conn, $sql);
                    $_SESSION['username'] = $nLogin;
                    $_SESSION['email'] = $nEmail;
                    header("Location:index.php");
                    exit();
                }
            }elseif (!empty($oLogin) && !empty($nLogin) &&  empty($oEmail) && empty($nEmail)){ 
                if($oLogin == $_SESSION['username']){
                    $sql = "UPDATE users SET username = '$nLogin' WHERE username = '$oLogin'";
                    mysqli_query($conn, $sql);
                    $_SESSION['username'] = $nLogin;
                    header("Location:index.php");
                    exit();
                }
            }elseif (empty($oLogin) && empty($nLogin) &&!empty($oEmail) && !empty($nEmail)){
                if($oEmail == $_SESSION['email']){
                    $sql = "UPDATE users SET email = '$nEmail' WHERE email = '$oEmail'";
                    mysqli_query($conn, $sql);
                    $_SESSION['email'] = $nEmail;
                    header("Location:index.php");
                    exit();
                }
            }else{
                echo "Proszę wypełnić pola.";
            }
        }else{
        echo '<div class="form-container" id="edit-form">
                <h3>Edytuj Profil</h3>
                <form action="profil_action.php?action=edit" method="POST">
                    <label>Aktualny Login:</label>
                    <input type="text" name="current_username">
                    
                    <label>Nowy Login:</label>
                    <input type="text" name="new_username">
                    
                    <label>Aktualny E-mail:</label>
                    <input type="email" name="current_email">
                    
                    <label>Nowy E-mail:</label>
                    <input type="email" name="new_email">
                    
                    <input type="submit" value="Zapisz zmiany" class="btn">
                </form>
            </div>';
        }
        break;
            
        case 'password':
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $oldPassword = $_POST['old_password'];
                $newPassword = $_POST['new_password'];
                $confirmPassword = $_POST['confirm_password'];
                if(!empty($oldPassword) &&!empty($newPassword) &&!empty($confirmPassword)){
                    if($oldPassword == $_SESSION['password']){
                        if($newPassword == $confirmPassword){
                            $sql = "UPDATE users SET password = '$newPassword' WHERE username = '$_SESSION[username]' AND email = '$_SESSION[email]'";
                            mysqli_query($conn, $sql);
                            $_SESSION['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                            header("Location:index.php");
                            exit();
                        }else{
                            echo "Nowe hasło i potwierdzenie hasła nie są identyczne.";
                        }
                    }else{
                        echo "Stare hasło jest nieprawidłowe.";
                    }
                }
            }else{
            echo '<div class="form-container" id="password-form">
                    <h3>Zmień Hasło</h3>
                    <form action="profil_action.php?action=password" method="POST">
                        <label>Stare Hasło:</label>
                        <input type="password" name="old_password" required>
                        
                        <label>Nowe Hasło:</label>
                        <input type="password" name="new_password" required>
                        
                        <label>Potwierdź Hasło:</label>
                        <input type="password" name="confirm_password" required>
                        
                        <input type="submit" value="Zmień Hasło" class="btn">
                    </form>
                </div>';
            }
        break;
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                if(!empty($username) &&!empty($email) &&!empty($password)){
                    if($username == $_SESSION['username'] && $email == $_SESSION['email'] && $password == $_SESSION['password']){
                        $sql = "DELETE FROM users WHERE username = '$username' AND email = '$email'";
                        mysqli_query($conn, $sql);
                        session_destroy();
                        session_unset();
                        header('Location: index.php');
                        exit();
                    }else{
                        echo "Podane dane nie są prawidłowe.";
                }
                }
            }else{
                echo '<div class="form-container" id="delete-form">
                <h3>Usuń Konto</h3>
                <form action="profil_action.php?action=delete" method="POST">
                    <label>Twój Login:</label>
                    <input type="text" name="username" required>
        
                    <label>Twój E-mail:</label>
                    <input type="email" name="email" required>
        
                    <label>Hasło:</label>
                    <input type="password" name="password" required>
        
                    <input type="submit" value="Usuń Konto" class="btn delete-account">
                </form>
            </div>';
            }
        break;
        
    case 'picture':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $zdjecie = $_POST['profile_picture'];
            if (!empty($zdjecie)) {
                $sql = "UPDATE users SET profile_picture = '$zdjecie' WHERE username = '$_SESSION[username]' AND email = '$_SESSION[email]'";
                mysqli_query($conn, $sql);
                $_SESSION['profile_picture'] = $zdjecie;
                header("Location: index.php");
                exit();
            } else {
                echo "Proszę podać link do zdjęcia.";
            }
        } else {
            echo '<div class="form-container" id="picture-form">
                    <h3>Zmień Zdjęcie Profilowe</h3>
                    <form action="profil_action.php?action=picture" method="POST" enctype="multipart/form-data">
                        <label>Wpisz link do zdjęcia:</label>
                        <input type="text" name="profile_picture" required>
                        <input type="submit" value="Zmień Zdjęcie" class="btn">
                    </form>
                </div>';
        }
        break;
    default:
        echo "Nieznana akcja!";
        header("Location: index.php");
        exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style_action.css">
</head>
<body>
    
</body>
</html>