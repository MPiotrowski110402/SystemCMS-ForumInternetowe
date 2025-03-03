<?php
    include('session.php');
    include('connect_db.php');
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        loginIn();
    }
    function loginIn(){
        global $conn;
        if (empty($_POST['login_input']) || empty($_POST['password_input'])) {
            echo "Proszę wypełnić wszystkie pola!";
            return;
        }
        $username = trim($_POST['login_input']);
        $password = $_POST['password_input'];

        $sql = "SELECT id, username, password,email,created_at, role, status, last_login, profile_picture, bio FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if(!$stmt){
            die("Error: ".mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt, "s", $username);
        $execute = mysqli_stmt_execute($stmt);
        if(!$execute){
            die("Error: ".mysqli_error($conn));
        }
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result)>0){
            if($row = mysqli_fetch_assoc($result)){
                if(password_verify($password, $row['password'])){
                    $_SESSION['logined'] = true;
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['created_at'] = $row['created_at'];
                    $_SESSION['status'] = $row['status'];
                    $_SESSION['last_login'] = $row['last_login'];
                    $_SESSION['profile_picture'] = $row['profile_picture'];
                    $_SESSION['bio'] = $row['bio'];
                    $_SESSION['role'] = $row['role'];
                    
                    header("Location: index.php");
                    exit();
                }else{
                    echo "Podane hasło jest błędne";
                }
            }
        }else{
            echo "Nie znaleziono użytkownika";
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Document</title>
</head>
<body>
<div class="container">
	<div class="screen">
		<div class="screen__content">
			<form class="login" method="POST">
				<div class="login__field">
					<i class="login__icon fas fa-user"></i>
					<input type="text" name="login_input" class="login__input" placeholder="User name / Email">
				</div>
				<div class="login__field">
					<i class="login__icon fas fa-lock"></i>
					<input type="password" name="password_input" class="login__input" placeholder="Password">
				</div>
				<button class="button login__submit">
					<input type="submit" class="button__text" value="Log In Now">
					<i class="button__icon fas fa-chevron-right"></i>
				</button>				
			</form>
			<div class="social-login">
				<h3></h3>
				<div class="social-icons">
					<a href="#" class="social-login__icon fab fa-instagram"></a>
					<a href="#" class="social-login__icon fab fa-facebook"></a>
					<a href="#" class="social-login__icon fab fa-twitter"></a>
				</div>
			</div>
		</div>
		<div class="screen__background">
			<span class="screen__background__shape screen__background__shape4"></span>
			<span class="screen__background__shape screen__background__shape3"></span>		
			<span class="screen__background__shape screen__background__shape2"></span>
			<span class="screen__background__shape screen__background__shape1"></span>
		</div>		
	</div>
</div>
</body>
</html>