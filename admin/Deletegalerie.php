<?php
    require 'Database.php';
    $db = Database::connect();
    $db->exec("SET CHARACTER SET utf8");
    session_start();
    
    foreach($db->query("SELECT * FROM utilisateurs") as $row)
    {
       var_dump($row['email'] . " + " . $row['password'] . " et maintenant les données de session, le login : " . $_SESSION['login'] . ", et le mot de passe : " . $_SESSION['password']);
       if($_SESSION['login'] == $row['email'] && $_SESSION['password'] == $row['password'])
       {
           var_dump("OK");
       }
       else{
        header("location: login.php");
       }
    }

    if(!empty($_GET['id']))
    {
        $id = checkInput($_GET['id']);
    }

    if(!empty($_POST['id']))
    {
        $id = checkInput($_POST['id']);
        $db = Database::connect();
        $statement = $db->prepare("DELETE FROM publication WHERE id = ?");
        $statement->execute(array($id));
        Database::disconnect();
        header("Location: admin.php");
    }

    function checkInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer une publication</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/fontawesome-free-5.14.0-web/css/all.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="text-logo"><span class="fas fa-utensils"></span>Administration <span class="fas fa-utensils"></span></h1>
    <div class="container admin">
        <div class="row">
            <div class="col-12">
                <h1><strong>Supprimer une publication</strong></h1>
                <br>
                <form class="form" action="deletegalerie.php" method="post" role="form">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <p class="alert alert-warning">Êtes-vous sûr de vouloir continuer ?</p>
                    <div class="form-actions">
                    <button type="submit" class="btn btn-warning">Oui</button>
                    <a href="admin.php" class="btn btn-dark">Non</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>