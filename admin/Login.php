<?php
require "Database.php";

if(!empty($_POST))
{
    $login = $_POST['login'];
    $password = $_POST['password'];
    var_dump("login : " . $login . ", mot de passe : " . $password);

    $db = Database::connect();
     foreach($db->query("SELECT * FROM utilisateurs") as $row)
     {
        var_dump($row['email'] . " + " . $row['password']);
        if($login == $row['email'] && $password == $row['password'])
        {
            session_start();
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;
            header("location: admin.php");
        }
     }



    Database::disconnect();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/fontawesome-free-5.14.0-web/css/all.css">
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <h1 class="text-logo"><span class="fas fa-utensils"></span>Administration <span class="fas fa-utensils"></span></h1>
    <div class="container admin">
        <div class="row">
            <h1><strong>Connexion</strong></h1>
        </div>
        <div class="row">
            <form class="form-group" action="#" role="form" method="POST">
                <label for="name">email</label>
                <input class="form-control" type="text" name="login" id="login">

                <label for="password">Mot de passe</label>
                <input class="form-control" type="password" name="password" id="password">
                <button type="submit" class="btn btn-success">Se connecter</button>
            </form>
        </div>
    </div>

</body>
</html>