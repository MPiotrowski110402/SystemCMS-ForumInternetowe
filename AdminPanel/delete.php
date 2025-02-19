<?php
    include('../session.php');
    include('../connect_db.php');
    $id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id = $id";
    mysqli_query($conn, $sql);
    header('Location:../AdminPanel/adminPanel.html');
    exit();
    

?>