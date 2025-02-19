<?php
    include('../session.php');
    include('../connect_db.php');

if (isset($_GET['page'])) {
    $page = $_GET['page'];

    switch ($page) {
        case 'dashboard':
            echo '
            <!-- dashboard.html -->
            <div class="dashboard">
                <h2>Dashboard</h2>
                <p>Witaj w panelu administratora! Możesz tutaj zarządzać użytkownikami, postami, raportami i ustawieniami. Wybierz jedną z opcji z menu po lewej stronie, aby rozpocząć zarządzanie.</p>
                
                <div class="stats">
                    <div class="stat-box">
                        <h3>Użytkownicy</h3>
                         <p>Liczba użytkowników:'.dashboardUserCount().'</p>
                    </div>
                    <div class="stat-box">
                        <h3>Posty</h3>
                        <p>Liczba postów: '.dashboardPostCount().'</p>
                    </div>
                    <div class="stat-box">
                        <h3>Raporty</h3>
                        <p>Oczekujące raporty: Brak Raportów</p>
                    </div>
                </div>
            </div>

            ';
            break;
        case 'users':
            echo '<!-- users.html -->
                <div class="users-management">
                    <h2>Zarządzanie Użytkownikami</h2>
                    <p>Możesz edytować dane użytkowników, zarządzać ich rolami, oraz usuwać konta.</p>
                    
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imię</th>
                                <th>Email</th>
                                <th>Rola</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            '.userList().'
                        </tbody>
                    </table>
                </div>
                ';
            break;
        case 'posts':
            echo '<!-- posts.html -->
                <div class="posts-management">
                    <h2>Zarządzanie Postami</h2>
                    <p>W tej sekcji możesz edytować lub usuwać posty użytkowników. Zmiany będą natychmiastowo widoczne na platformie.</p>
                    
                    <table class="posts-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tytuł</th>
                                <th>Autor</th>
                                <th>Data publikacji</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            '.postsList().'
                        </tbody>
                    </table>
                </div>
                ';
            break;
        case 'reports':
            echo '<!-- reports.html -->
                <div class="reports">
                    <h2>Raporty</h2>
                    <p>Sprawdź zgłoszone raporty, które wymagają Twojej uwagi.</p>
                    
                    <table class="reports-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Użytkownik</th>
                                <th>Powód</th>
                                <th>Status</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Jan Kowalski</td>
                                <td>Spam</td>
                                <td>Oczekuje</td>
                                <td><button class="btn resolve-btn">Rozwiąż</button></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Anna Nowak</td>
                                <td>Nieodpowiednie treści</td>
                                <td>Rozwiązany</td>
                                <td><button class="btn resolved-btn" disabled>Rozwiązany</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                ';
            break;
        case 'settings':
            echo '<!-- settings.html -->
                <div class="settings">
                    <h2>Ustawienia</h2>
                    <p>Zmień ustawienia swojej platformy, takie jak nazwa, logo, i inne preferencje.</p>
                    
                    <form action="" method="POST">
                        <label for="platform-name">Nazwa platformy:</label>
                        <input type="text" id="platform-name" name="platform-name" value="Platforma 1.0">
                        
                        <label for="platform-logo">Logo (URL):</label>
                        <input type="text" id="platform-logo" name="platform-logo" value="logo.png">
                        
                        <button type="submit" class="btn save-btn">Zapisz ustawienia</button>
                    </form>
                </div>
                ';
            break;
        case 'logout':
            header('Location:logout.php');
            break;
        default:
            echo 'Nieznana strona';
            break;
            }
};

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])){
        editUser();
    }



    function dashboardUserCount(){
        global $conn;
        $sql = "SELECT COUNT(*) as user_count FROM users";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['user_count'];
    }
    function dashboardPostCount(){
        global $conn;
        $sql = "SELECT COUNT(*) as post_count FROM posts";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['post_count'];
    }

    function userList(){
        global $conn;
        $sql = "SELECT id, username, email,role FROM users";
        $result = mysqli_query($conn, $sql);
        $output = '';
        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['id'];
                $username = $row['username'];
                $email = $row['email'];
                $role = $row['role'];
                $output .= '<tr>
                            <td>'.$id.'</td>
                            <td>'.$username.'</td>
                            <td>'.$email.'</td>
                            <td>'.$role.'</td>
                            <td>
                            <form method="post" action="edit.php?id='.$id.'">
                            <input type="submit" value="edytuj" class="btn edit-btn" data-id="'.$id.'">
                            </form>
                            <form method="post" action="delete.php?id='.$id.'">
                            <input type="submit" value="usuń" class="btn delete-btn" data-id="'.$id.'">
                            </form>
                            </td>
                        </tr>';
            }
        } else {
            return '<tr><td colspan="5">Brak danych</td></tr>';
        }
        return $output;
    }

    function postsList() {
        global $conn;
        $sql = "SELECT posts.id, posts.title, users.username, posts.created_at FROM posts INNER JOIN users ON posts.user_id = users.id";
        $result = mysqli_query($conn, $sql);
        $output = '';
        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['id'];
                $title = $row['title'];
                $username = $row['username'];
                $created_at = $row['created_at'];
                $output.= '<tr>
                            <td>'.$id.'</td>
                            <td>'.$title.'</td>
                            <td>'.$username.'</td>
                            <td>'.$created_at.'</td>
                            <td>
                            <button class="btn edit-btn">Edytuj</button>
                            <button class="btn delete-btn">Usuń</button>
                            </td>
                        </tr>';
            }
        } else {
            return '<tr><td colspan="5">Brak danych</td></tr>';
        }
        return $output;

    }

    function editUser(){
        global $conn;

    }


?>