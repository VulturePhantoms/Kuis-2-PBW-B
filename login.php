<?php
session_start();

$server = "localhost";
$user = "root";
$pass = "";
$database = "login";

$koneksi = mysqli_connect($server, $user, $pass, $database)or die(mysqli_error($koneksi));

$tampil = mysqli_query($koneksi, "SELECT * from user limit 0, 10");
while($data = mysqli_fetch_array($tampil)) {
    echo $data['id']. ' '. $data['username']. ' '. $data['password']. '<br/>';
}

if( isset($_COOKIE['id']) && isset($_COOKIE['user']) ) {
    $id = $_COOKIE['id'];
    $user = $_COOKIE['user'];

    $check = mysqli_query($koneksi, "SELECT username from user where id = $id");
    $row = mysqli_fetch_assoc($check);

    if( $user === hash('sha256', $row['username']) ) {
        $_SESSION['login'] = true;
    }

}

if( isset($_SESSION["login"]) ){
    header("Location: masuk.php");
    exit;
}



/*try {
    $db = new PDO ('mysql:host=localhost;dbname=login', 'root', '');
} catch (\Exception $e) {
    echo $e->getMessage();
}

$data = $db->query('select * from user limit 0, 10');
while ($row = $data->fetch(PDO::FETCH_OBJ)) {
    echo $row->id . ' ' . $row->username . ' ' . $row->password . '<br />';
}*/

if( isset($_POST["login"]) ) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $check = mysqli_query($koneksi, "SELECT * from user where username = '$username'");

    if( mysqli_num_rows($check) === 1) {

        $row = mysqli_fetch_assoc($check);
        if( $password == $row["password"]) {
            $_SESSION["login"] = true;
            
            if( isset($_POST['remember']) ) {

                setcookie('id', $row['id'], time() + (86400 * 30));
                setcookie('user', hash('sha256', $row['username']), time() + (86400 * 30));
            }

            header("Location: masuk.php");
            exit;

        }
        else {
            echo "<script> alert('Mohon Masukkan Password Dengan Benar!.'); document.location='login.php'; </script>";
        }
    }
    else {
        echo "<script> alert('Mohon Masukkan Username dan Password Dengan Benar!.'); document.location='login.php'; </script>";
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<form action="" method="post">
    <h1>LOGIN</h1>
    <div class="input">
        <label for="username">Username</label>
        <input type="text" name="username"id = "username" placeholder="Masukkan Username">
    </div>
    <div class="input">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Masukkan Password">
    </div>
    <div class="checkbox">
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Remember Me</label>
    </div>
    <button type="submit" name="login">LOGIN</button>
</form>
</body>
</html>