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

    $nameError = $descriptionError = $price = $imageError = $name = $description = $price = $image = "";
    if(!empty($_POST))
    {
        $name = checkInput($_POST["name"]);;
        $description = checkInput($_POST["description"]);
        $price = checkInput($_POST["price"]);
        $image = checkInput($_FILES["image"]['name']);
        $imagePath = '../img/' . basename($image);
        $imageExtension = pathInfo($imagePath, PATHINFO_EXTENSION);
                // on récupère le nom de l'image, son chemin, et son extension
        var_dump("le nom : " . $name . ", la description : " . $description . ", le prix: " . $price . ", l'image : " . $image . ", son chemin : " . $imagePath . " et son extension : " . $imageExtension);

        $isSuccess = true;
        $isUploadSuccess = false;

        if(empty($name))
        {
            var_dump("PAS OK");
            die();
            $nameError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($description))
        {
            var_dump("PAS OK");
            die();
            $descriptionError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($price))
        {
            var_dump("PAS OK");
            die();
            $priceError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($image))
        {
            var_dump("PAS OK");
            die();
            $imageError = 'Vous devez choisir une image';
            $isSuccess = false;
        }
        else{
            $isUploadSuccess = true;
            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif" && $imageExtension != "JPG")
            {
                var_dump("PAS OK");
                die();
                $isUploadSuccess = false;
                $imageError = "Les fichiers autorisés sont .jpg, .jpeg, .png et .gif";
            }

            // if(file_exists($imagePath))
            // {
            //     var_dump("PAS OK, le fichier existe deja");
            //     die();
            //     $imageError = "le fichier existe déjà";
            //     $isUploadSuccess = false;
            // }

            // if($_FILES['image']['size'] > 500000)
            // {
            //     var_dump("PAS OK, le fichier est trop lourd");
            //     die();
            //     $isUploadSuccess = false;
            //     $imageError = "Le fichier de noit pas dépasser les 500KB";
            // }

            if($isUploadSuccess)
            {
                if(!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath))
                {
                    var_dump("PAS OK, erreur lors de l'upload");
                    die();
                    $imageError = "Il y a eu une erreur lors de l'upload";
                    $isUploadSuccess = false;
                }
            }
        }

        if($isSuccess && $isUploadSuccess)
        {
            var_dump("apparemment tout est OK");
            $db = Database::connect();
            $db->exec("SET CHARACTER SET utf8");
            $statement = $db->prepare("INSERT INTO formules (name, image, description, price) VALUES (?, ?, ?, ?)");
            $statement->execute(array($name, $image, $description, $price));
            Database::disconnect();
            header("location: admin.php");
        }

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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Laurine portfolio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/fontawesome-free-5.14.0-web/css/all.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="text-logo"><span class="fas fa-utensils"></span>Administration <span class="fas fa-utensils"></span></h1>
    <div class="container admin">
        <div class="row">
            <div class="col-12">
                <h1><strong>Ajouter une formule</strong></h1>
                <br>
                <form class="form" action="Insertformules.php" method="post" role="form" enctype="multipart/form-data">    <!-- permet de joindre des fichiers -->
                    <div class="form-group">
                        <label for="name">Nom : </label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="nom" value="<?php echo $name; ?>">
                        <span class="help-inline"><?php $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description : </label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="description" value="<?php echo $description; ?>">
                        <span class="help-inline"><?php $descriptionError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="price">Prix : </label>
                        <input type="number" class="form-control" id="price" name="price" placeholder="prix" value="<?php echo $price; ?>">
                        <span class="help-inline"><?php $priceError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="image">Sélectionner une image </label>
                        <input type="file" id="image" name="image">
                        <span class="help-inline"><?php $imageError; ?></span>
                    </div>
                    <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        <span class="fas fa-pencil"> Ajouter</span>
                    </button>
                    <a href="admin.php" class="btn btn-primary"><span class="fas fa-arrow-left"> Retour</span></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>