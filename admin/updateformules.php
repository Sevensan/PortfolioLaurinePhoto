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
        var_dump($id);
    }

    $nameError = $imageError = $descriptionError = $priceError = $name = $price = $description = $image = "";

    if(!empty($_POST))
    {
        var_dump($_POST['name']);
        $name = checkInput($_POST["name"]);
        $description = checkInput($_POST['description']);
        $price = checkInput($_POST['price']);
        $image = checkInput($_FILES["image"]['name']);
        $imagePath = '../img/' . basename($image);
        $imageExtension = pathInfo($imagePath, PATHINFO_EXTENSION);
                // on récupère le nom de l'image, son chemin, et son extension

        $isSuccess = true;
        $isUploadSUccess = false;

        if(empty($name))
        {
            $nameError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($description))
        {
            $descriptionError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($price))
        {
            $priceError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($image))
        {
            $isImageUpdated = false;
        }
        else{
            $isImageUpdated = true;
            $isUploadSuccess = true;
            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif")
            {
                $isUploadSuccess = false;
                $imageError = "Les fichiers autorisés sont .jpg, .jpeg, .png et .gif";
            }

            if(file_exists($imagePath))
            {
                $imageError = "le fichier existe déjà";
                $isUploadSuccess = false;
            }

            // if($_FILES['image']['size'] > 500000)
            // {
            //     $isUploadSuccess = false;
            //     $imageError = "Le fichier de noit pas dépasser les 500KB";
            // }

            if($isUploadSuccess)
            {
                if(!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath))
                {
                    $imageError = "Il y a eu une erreur lors de l'upload";
                    $isUploadSuccess = false;
                }
            }
        }

        if($isSuccess && $isImageUpdated && $isUploadSuccess || $isSuccess && !$isImageUpdated)
        {
            $db = Database::connect();
            $db->exec("SET CHARACTER SET utf8");
            if($isImageUpdated)
            {
                var_dump("envoi du formulaire avec l'image updaté");
                $statement = $db->prepare("UPDATE formules SET name=?,description=?, price=?, image=? WHERE id=?");
                $statement->execute(array($name,$description, $price, $image,$id));
            }
            else{
                var_dump('envoi du formulaire sans image');
                $statement = $db->prepare("UPDATE formules SET name=?,description=?, price=? WHERE id=?");
                $statement->execute(array($name,$description, $price, $id));
                var_dump("Le nom : " . $name . ", l'id de la catégorie : " . $description . ", le prix :" . $price .  " et l'id : " . $id );

            }
            Database::disconnect();
            header("location: admin.php");
        }
        else if($isImageUpdated && !$isUploadSuccess)
        {
            $db = Database::connect();
            $db->exec("SET CHARACTER SET utf8");
            $statement = $db->prepare("SELECT image FROM formules WHERE id = ?");
            $statement->execute(array($id));
            $formule = $statement->fetch();
    
            // on recupere toutes les infos de nos éléments en BDD pour pouvoir les afficher
            $image = $formule["image"];
            var_dump($image);
            
    
            Database::disconnect();
        }

    }

    else{
        $db = Database::connect();
        $db->exec("SET CHARACTER SET utf8");
        $statement = $db->prepare("SELECT * FROM formules WHERE id = ?");
        $statement->execute(array($id));
        $formule = $statement->fetch();

        // on recupere toutes les infos de nos éléments en BDD pour pouvoir les afficher
        $name = $formule["name"];
        $description = $formule["description"];
        $price = $formule['price'];
        $image = $formule["image"];
        

        Database::disconnect();
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
    <title>Administration formules</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/fontawesome-free-5.14.0-web/css/all.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="text-logo"><span class="fas fa-utensils"></span>Administration des formules<span class="fas fa-utensils"></span></h1>
    <div class="container admin">
        <div class="row">
            <div class="col-6">
                <h1><strong>Modifier un item</strong></h1>
                <br>
                <form class="form" action="<?php echo 'Updateformules.php?id=' . $id; ?>" method="POST" role="form" enctype="multipart/form-data">    <!-- permet de joindre des fichiers -->
                    <div class="form-group">
                        <label for="name">Nom : </label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="<?php $name?>" value="<?php echo $name; ?>">
                        <span class="help-inline"><?php $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="">Description : </label>
                        <p><?php echo $description ?></p>
                            <label for="description">Écrire une description </label>
                            <input type="text" id="description" name="description">
                            <span class="help-inline"><?php $descriptionError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="">Prix : </label>
                        <p><?php echo $price ?></p>
                            <label for="price">Donner un prix </label>
                            <input type="number" id="price" name="price">
                            <span class="help-inline"><?php $priceError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="">Image : </label>
                        <p><?php echo $image ?></p>
                            <label for="image">Sélectionner une image </label>
                            <input type="file" id="image" name="image">
                            <span class="help-inline"><?php $imageError; ?></span>
                    </div>
                    <div class="form-actions">
                    <button type="submit" class="btn btn-success
                    "><span class="fas fa-pencil"> Modifier</span></button>
                    <a href="admin.php" class="btn btn-primary"><span class="fas fa-arrow-left"> Retour</span></a>
                    </div>
                </form>
            </div>
            <div class="col-6">
            <div class="card">
                            <img src="<?php echo '../img/' . $image ?>" class="card-img-top" alt="<?php echo $name ?>">
                            <div class="card-body text-left">
                                <h4 class="card-title"><?php echo $name; ?></h4>
                                <p class="card-text"><?php echo $description ?></p>
                                <p class="card-text"><?php echo $price ?>€</p>
                                <a href="#" class="button btn-order" role="button"><span class="fas fa-shopping-cart"></span>Réserver</a>
                            </div>
                        </div>
            </div>
        </div>
    </div>

</body>
</html>