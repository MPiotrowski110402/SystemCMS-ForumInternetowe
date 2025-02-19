<?php
    include('session.php');
    include('connect_db.php');



    function profileDescription(){
        global $conn;
        if(isset($_SESSION['logined']) && $_SESSION['logined'] === true){
            if($_SESSION['user_id'] === $_GET['id']){
                profilSelfDescription();
            }else{
                profilOtherDescription();
            }
        }else{
            echo '<p>Aby zobaczyć opis profilu, zaloguj się lub zarejestruj się.</p>';
        }
    }
    

    function profilSelfDescription(){
        if($_SESSION['logined'] === true && $_SESSION['user_id'] === $_GET['id']){
            $username = $_SESSION['username'];
            $profilePicture = $_SESSION['profile_picture'];
            $bio = $_SESSION['bio'];
            $status = $_SESSION['status'];
            $createdAtFormatted = date("d-m-Y", strtotime($_SESSION['created_at']));
            $edit = 'edit';
            $password = 'password';
            $delete = 'delete';
            $picture = 'picture';
        echo
            '<div class="profile-section">
                <img src="'.$profilePicture.'" alt="Zdjęcie profilowe" class="profile-picture">
                <h2 class="username">'.$username.'</h2>
                <div class="bio-container">
                    <p class="user-bio">
                        '.$bio.'
                    </p>
                </div>
                <p class="user-status">Status: '.$status.'</p>
                <button onclick="handleAction(\''.$edit.'\')" class="btn edit-profile">Edytuj profil</button>
                <button onclick="handleAction(\''.$password.'\')" class="btn change-password">Zmień hasło</button>
                <button onclick="handleAction(\''.$picture.'\')" class="btn change-avatar">Zmień zdjęcie profilowe</button>
                <button onclick="handleAction(\''.$delete.'\')" class="btn delete-account">Usuń konto</button>
            </div>
            <div class="stats-section">
                <h3>Statystyki konta</h3>
                '.postCount().
                likesProfileCount().
                commandProfileCount().'
                <p><strong>Data dołączenia:</strong> '.$createdAtFormatted.'</p>
            </div>
            <div class="posts-section">
                <h3>Twoje posty</h3>
                    <ul class="posts-list">';
                postProfileList();
                echo'</ul>
            </div>
            <div class="achievements-section">
                <h3>Osiągnięcia</h3>
                '.osiagniecia().'
            </div>';
            
        }
    }
    function profilOtherDescription(){
        if($_SESSION['logined'] === true && isset($_GET['id'])){
            global $conn;
            $id = $_GET['id'];
            $sql = "SELECT * FROM users WHERE id = $id";
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($result);
            $username = $row['username'];
            $profilePicture = $row['profile_picture'];
            $bio = $row['bio'];
            $status = $row['status'];
            $createdAtFormatted = date("d-m-Y", strtotime($row['created_at']));
        echo
            '<div class="profile-section">
                <img src="'.$profilePicture.'" alt="Zdjęcie profilowe" class="profile-picture">
                <h2 class="username">'.$username.'</h2>
                <div class="bio-container">
                    <p class="user-bio">
                        '.$bio.'
                    </p>
                </div>
                <p class="user-status">Status: '.$status.'</p>
            </div>
            <div class="stats-section">
                <h3>Statystyki konta</h3>
                '.postCount().
                likesProfileCount().
                commandProfileCount().'
                <p><strong>Data dołączenia:</strong> '.$createdAtFormatted.'</p>
            </div>
            <div class="posts-section">
                <h3>Twoje posty</h3>
                <ul class="posts-list">';
                    postProfileList();
        echo'   </ul>
            </div>
            <div class="achievements-section">
                <h3>Osiągnięcia</h3>
                '.osiagniecia().'
            </div>';
        }
    }
    //gdy lepiej poznam metodę AJAX
    function editProfile(){};
    function changePassword(){};
    function changeAvatar(){};
    function deleteAccount(){};

    //mogłem to wszystko zrobić prościej i ominąć robienie na sesjach i zrobić wszystko na pobieraniu id od linku
    // a tylko niektóre funkcję które są niedostępne do użytku publicznego zablokować sesją id  ale no trudno :D 
    function postCount(){
        global $conn;
        if(isset($_SESSION['logined']) && $_SESSION['logined'] === true && $_SESSION['user_id'] === $_GET['id']){
            $id = $_SESSION['user_id'];
            $sql = "SELECT COUNT(*) as posts_count FROM posts WHERE user_id = $id";
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($result);
            $postsCount = $row['posts_count'];
            return '<p><strong>Posty:</strong> '.$postsCount.'</p>';
        }else{
            if($_SESSION['logined'] === true && isset($_GET['id'])){
                $id = $_GET['id'];
                $sql = "SELECT COUNT(*) as posts_count FROM posts WHERE user_id = $id";
                $result = mysqli_query($conn,$sql);
                $row = mysqli_fetch_assoc($result);
                $postsCount = $row['posts_count'];
                return '<p><strong>Posty:</strong> '.$postsCount.'</p>';
            }
        }
    };
    function likesProfileCount(){
        global $conn;
        if(isset($_SESSION['logined']) && $_SESSION['logined'] === true && $_SESSION['user_id'] === $_GET['id']){
            $id = $_SESSION['user_id'];
            $sql = "SELECT COUNT(*) as likes_count FROM likes WHERE user_id = $id";
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($result);
            $likesCount = $row['likes_count'];
            return '<p><strong>Polubienia:</strong> '.$likesCount.'</p>';
        }else{
            if($_SESSION['logined'] === true && isset($_GET['id'])){
                $id = $_GET['id'];
                $sql = "SELECT COUNT(*) as likes_count FROM likes WHERE user_id = $id";
                $result = mysqli_query($conn,$sql);
                $row = mysqli_fetch_assoc($result);
                $likesCount = $row['likes_count'];
                return '<p><strong>Polubienia:</strong> '.$likesCount.'</p>';
            }
        }
    };
    function commandProfileCount(){
        global $conn;
        if(isset($_SESSION['logined']) && $_SESSION['logined'] === true && $_SESSION['user_id'] === $_GET['id']){
            $id = $_SESSION['user_id'];
            $sql = "SELECT COUNT(*) as comments_count FROM comments WHERE user_id = $id";
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($result);
            $commentsCount = $row['comments_count'];
            return '<p><strong>Komentarze:</strong> '.$commentsCount.'</p>';
        }else{
            if($_SESSION['logined'] === true && isset($_GET['id'])){
                $id = $_GET['id'];
                $sql = "SELECT COUNT(*) as likes_count FROM likes WHERE user_id = $id";
                $result = mysqli_query($conn,$sql);
                $row = mysqli_fetch_assoc($result);
                $likesCount = $row['likes_count'];
                return '<p><strong>Polubienia:</strong> '.$likesCount.'</p>';
            }
        }
    };
    function postProfileList(){
        global $conn;
        if(isset($_SESSION['logined']) && $_SESSION['logined'] === true ){
            $id = $_GET['id'];
            $sql = "SELECT * FROM posts WHERE user_id = $id ORDER BY created_at DESC";
            $result = mysqli_query($conn,$sql);
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $postId = $row['id'];
                    $title = substr($row['title'],0,20);
                    if(strlen($row['content']) > 20){
                        $title .= '...';
                    }
                    $content = $row['content'];
                    $createdAtFormatted = date("d-m-Y", strtotime($row['created_at']));
                    echo '
                    <form method="POST"><li>
                            <a href="post.php?id='.$postId.'" class="post-title">'.$title.'</a>
                            <span class="post-date">'.$createdAtFormatted.'</span>';
                            if(isset($_SESSION['logined']) && $_SESSION['logined'] === true && $_SESSION['user_id'] === $id){         
                            echo '<input type="hidden" name="post_id" value="'.$postId.'">
                            <input type="submit" value="Usuń"class="btn delete-post" name="btn_post_del">';
                            }
                    echo '</li></form>';
                }
            }
        }
    }
    function osiagniecia(){
        global $conn;
        if(isset($_SESSION['logined']) && $_SESSION['logined'] === true ){
            $id = $_GET['id'];
            $dateFiveYearsAgo = date("Y-m-d", strtotime("-5 years"));
            $sql = "SELECT created_at FROM users WHERE id = $id";
            $result = mysqli_query($conn, $sql);
            if($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $createdAtFormatted = date("Y-m-d", strtotime($row['created_at']));
                if($createdAtFormatted < $dateFiveYearsAgo){
                    $dataResult = '<p>🏆 5 lat na platformie</p>';
                } else {
                    $dataResult = '<s>🏆 5 lat na platformie</s>';
                }
            }

            $sql = "SELECT likes_count FROM posts WHERE user_id = $id";
            $result = mysqli_query($conn, $sql);
            if($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if($row['likes_count'] >= 100){
                    $likesResult = '<p>🔥 100 polubień w jednym poście</p>';
                } else {
                    $likesResult = '<p><s>🔥 100 polubień w jednym poście</s></p>';
                }
            } else {
                $likesResult = '<p><s>🔥 100 polubień w jednym poście</s></p>';
            }

            $sql = "SELECT COUNT(*) as comments_count FROM comments WHERE user_id = $id";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if($row['comments_count'] >= 50){
                    $commentsResult = '<p>💬 50 komentarzy na profilu</p>';
                } else {
                    $commentsResult = '<s>💬 50 komentarzy na profilu</s>';
                }
            }else{
                $commentsResult = '<p><s>💬 50 komentarzy na profilu</s></p>';
            }
            return $dataResult . $likesResult. $commentsResult;
        }
    }
    if(isset($_POST['btn_post_del'])){
        $id = $_POST['post_id'];
        $sql = "DELETE FROM posts WHERE id = '$id'";
        if (mysqli_query($conn, $sql)) {
            header('Location: profil.php?id='.$_SESSION['user_id']);
            exit();
        } else {
            echo "Error deleting record: ". mysqli_error($conn);
        }
    }



?>

<script defer>
        function handleAction(action) {
            switch (action) {
                case 'edit':
                    window.location.href = 'profil_action.php?action=edit';
                    break;
                case 'password':
                    window.location.href = 'profil_action.php?action=password';
                    break;
                case 'delete':
                    window.location.href = 'profil_action.php?action=delete';
                    break;
                case 'picture':
                    window.location.href = 'profil_action.php?action=picture';
                    break;
                default:
                    alert('Nieznana akcja!');
            }
        }
    </script>