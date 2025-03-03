<?php
    include('session.php');
    include('connect_db.php');
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        addCommants();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_btn'])) {
        likesPost();
    }
    function showPost(){
        global $conn;
        $id_post = isset($_GET['id']) ? (int)$_GET['id']:0;
        likesCount($id_post);
        $sql = "SELECT
                LENGTH(posts.content) AS content_length, 
                posts.id,
                 posts.title,
                 posts.content, 
                 posts.created_at, 
                 posts.views_count, 
                 posts.likes_count, 
                 users.username, 
                 users.profile_picture 
                FROM posts 
                INNER JOIN users 
                ON posts.user_id = users.id
                WHERE posts.id = ? ";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_post);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
            $id = $row['id'];
            $title = htmlspecialchars($row['title']);
            $content = htmlspecialchars(substr($row['content'], 0, 1000));
            $created_at = htmlspecialchars($row['created_at']);
            $views_count = (int)$row['views_count'];
            $likes_count = (int)$row['likes_count'];
            $username = htmlspecialchars($row['username']);
            $profile_picture = $row['profile_picture'];
            if(true){
                mysqli_query($conn,'UPDATE posts SET views_count = views_count + 1 WHERE posts.id = '.$id_post.';');
            }
            
            if($views_count > 5000){
                $views_display = '5000+';
            }elseif($views_count > 2000){
                $views_display = '2000+';
            }elseif($views_count > 1000){
                $views_display = '1000+';
            }else{
                $views_display = $views_count;
            }
            echo '<div class="post-container" id="post-'.$id.'">
                    <div class="post-header">
                        <div class="user-info">
                            <img src="'.$profile_picture.'" alt="Zdjęcie użytkownika" class="user-avatar">
                                <div class="user-details">
                                    <p class="username">'.$username.'</p>
                                    <p class="post-date">📅 Opublikowano: '.$created_at.'</p>
                                </div>
                            </div>
                            <h1 class="post-title">'.$title.'</h1>
                        </div>
                        <div class="post-body">
                            <p class="post-content">'.$content.'</p>
                        </div>
                        <div class="post-footer">
                            <div class="post-stats">
                            <form method="post" action="">
                                <span class="post-views">👀 '.$views_display.' wyświetleń</span>
                                <button type="submit" name="like_btn" class="post-likes">❤️ '.$likes_count.' polubień</button>
                            </form>
                            </div>
                        </div>
                    </div>
                        <!-- Sekcja komentarzy -->
                    <div class="comments-section">
                        <h2>Komentarze</h2>
                        <div class="comments-list" id="comments-list-'.$id.'">
                        <!-- Komentarze będą ładowane tutaj -->
                        '.commandList().'
                        </div>

                        <!-- Formularz dodawania komentarza -->
                        <div class="comment-form">
                        <form method="POST">
                            <textarea name="commants_content" id="comment-text-'.$id.'" class="comment-text" placeholder="Dodaj komentarz..."></textarea>
                            <input id="submit-comment-'.$id.'" class="submit-comment-btn" type="submit" value="Dodaj komentarz">
                        </form>
                        </div>
                    </div>
                </div>';

            
            }      
    }
    function listPost(){
        global $conn;
        $category = isset($_GET['category'])? $_GET['category'] : '';
        if(!$category){
            $sql = "SELECT
            LENGTH(posts.content) AS content_length,
            posts.id,
            posts.title,
            posts.content, 
            category.category,  
            posts.created_at, 
            posts.views_count, 
            posts.likes_count,
            users.username
            FROM posts
            INNER JOIN users ON posts.user_id = users.id
            INNER JOIN category ON posts.category_id = category.id
            ORDER BY posts.created_at DESC";
        $stmt = mysqli_prepare($conn, $sql);

        } else {
            $sql = "SELECT
                    LENGTH(posts.content) AS content_length,
                    posts.id,
                    posts.title,
                    posts.content, 
                    category.category,  
                    posts.created_at, 
                    posts.views_count, 
                    posts.likes_count,
                    users.username
                    FROM posts
                    INNER JOIN users ON posts.user_id = users.id
                    INNER JOIN category ON posts.category_id = category.id  
                    WHERE posts.category_id = ?
                    ORDER BY posts.created_at DESC";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $category);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['id'];
                $title = $row['title'];
                $created_at = $row['created_at'];
                $likes = $row['likes_count'];
                $views = $row['views_count'];
                $categoryName = $row['category'];
                $description = htmlspecialchars(substr($row['content'], 0, 75). "...");
                $author = $row['username'];
                
                echo '<div class="thread-item" id="thread-'.$id.'">
                        <div class="thread-header">
                            <h2 class="thread-title"><a href="post.php?id='.$id.'">'.$title.'</a></h2>
                            <p class="thread-meta">Autor: <span class="thread-author">'.$author.'</span> | 
                            Data: <span class="thread-date">'.$created_at.'</span>
                            <span class="thread-date">Kategoria: '.$categoryName.'</span></p>
                        </div>
                        <div class="thread-body">
                            <p class="thread-description">'.$description.'</p>
                        </div>
                        <div class="thread-footer">
                            <span class="thread-views">👀 '.$views.' wyświetleń</span>
                            <span class="thread-likes">❤️ '.$likes.' polubień</span>
                        </div>
                    </div>';

            }
        }
    }

    function logInLogOut(){
        if(isset($_SESSION['logined']) && $_SESSION['logined'] === true){
           echo '<li><a href="logout.php">Wyloguj się</a></li>';
        }else{
            echo '<li><a href="login.php">Zaloguj się</a></li>';
        }
    }
    function Registered(){
        if(isset($_SESSION['logined']) && $_SESSION['logined'] === true){
           echo '<li><a href="profil.php?id='.$_SESSION['user_id'].'">Pokaż profil</a></li>';
        }else{
            echo '<li><a href="register.php">Zarejestruj się</a></li>';
        }
    }
    function adminPanel(){
        if(isset($_SESSION['logined']) && $_SESSION['logined'] === true && $_SESSION['role'] === 'admin'){
            echo '<li><a href="AdminPanel/adminPanel.html">Panel Administracyjny</a></li>';
         }
    }
    function commandList(){
        global $conn;
        $id_post = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $sql = 
        "SELECT 
        comments.content,
        comments.created_at,
        users.username,
        users.profile_picture
        FROM comments
        INNER JOIN users ON comments.user_id = users.id
        WHERE comments.post_id = ?
        ORDER BY comments.created_at DESC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_post);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
            $CommantsContent = $row['content'];
            $CommantsCreated_at = $row['created_at'];
            $CommantsUsername = $row['username'];
            $commantsPicture = $row['profile_picture']; 
            echo ' 
                <div class="comment">
                    <div class="comment-header">
                        <img src="' . $commantsPicture . '" alt="Zdjęcie użytkownika" class="comment-avatar">
                        <span class="comment-username">' . $CommantsUsername . '</span>
                    </div>
                    <div class="comment-body">
             <p class="comment-content">' . $CommantsContent . '</p>
             </div>
             <div class="comment-footer">
             <span class="comment-date">' . $CommantsCreated_at . '</span>
             </div>
             </div>';
             
            }
        }
    }    
    
    function addCommants(){
        global $conn;
        if(isset($_SESSION['logined']) && $_SESSION['logined'] === true){
            $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] :0;
            $id_post = isset($_GET['id']) ? (int)$_GET['id'] :0;
            $commands_content = isset($_POST['commants_content']) ? htmlspecialchars(trim($_POST['commants_content'])) : '';
            if (empty($commands_content)) {
                echo "Treść komentarza nie może być pusta";
                return; 
            }

            $command = mysqli_real_escape_string($conn, $commands_content);
            $sql = "INSERT INTO comments (post_id, user_id, content, created_at) 
            VALUES (? , ? , ? , NOW())";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iis", $id_post, $user_id, $command);
            $result = mysqli_stmt_execute($stmt);
            if($result){
                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id_post);
                exit(); 
            }else{
                echo "Treść komentarza nie może być pusta";
            }
        }else{
            header('Location: login.php');
            exit();
        }
    }


    function likesPost(){
        global $conn;
        if(isset($_SESSION['logined']) && $_SESSION['logined'] === true){
            if(isset($_POST['like_btn'])){
                $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] :0;
                $post_id = isset($_GET['id']) ? (int)$_GET['id'] :0;
                $sql = "SELECT id
                    FROM likes WHERE likes.post_id = ? AND likes.user_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result)>0){
                    $row = mysqli_fetch_assoc($result);
                    $sql = "DELETE FROM likes WHERE  likes.post_id = ? AND likes.user_id = ?";
                    $stmt = mysqli_prepare($conn,$sql);
                    mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
                    mysqli_stmt_execute($stmt);
                }else{
                    $sql = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
                    mysqli_stmt_execute($stmt);
                }
                likesCount($post_id);
                header("Location: ". $_SERVER['PHP_SELF']. "?id=". $post_id);
            };
        }else{
            header('Location: login.php');
            exit();
        }
    }
    function likesCount($post_id){
        global $conn;
        $sql = "SELECT COUNT(*) as likes_count FROM likes WHERE post_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $post_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $likes_count = $row['likes_count'];
        $update_sql = "UPDATE posts SET likes_count = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($stmt, "ii", $likes_count, $post_id);
        mysqli_stmt_execute($stmt);
        return $likes_count;
    }
        
    function categoryList(){
        global $conn;
        $sql = "SELECT * FROM category";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['id'];
                $name = $row['category'];
                echo '<li><a href="index.php?category='.$id.'">'.$name.'</a></li>';
            }
        }
    }
    function newPost(){
            echo '<form action="addPost.php" method="post" class="post-form">
            <input type="submit" class="submit-btn left-btn"value="Dodaj nowy post">
            </form>';

    }
    function searchInput(){
        echo ' 
                    <form action="#" method="get" class="search-form">
            <input type="text" name="search" placeholder="Szukaj..." class="search-input">
            <input type="submit" class="submit-btn right-btn" value="szukaj">
            </form>';
    }
?>






