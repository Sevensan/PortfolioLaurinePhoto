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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Burger Code</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../fontawesome-free-5.15.0-web/css/all.css">
    <link rel="icon" type="image/x-icon" href="../img/logolaurine.png"/>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="text-logo"><span class="fas fa-tools"></span>Administration Galerie<span class="fas fa-tools"></span></h1>
    <div class="container admin">
        <div class="row">
            <h2><strong>Liste des publications</strong></h2>
            <a href="Insertgalerie.php" class="btn btn-success btn-lg"><span class="fas fa-plus"></span>Ajouter</a>
        </div>
        <div class="row">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>image</th>
                        <th>date de publication</th>
                        <th>Catégorie</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $statement = $db->query('SELECT publication.id, publication.titre, publication.image, publication.datepublication, categorie.name AS category from publication LEFT JOIN categorie ON publication.categorie = categorie.id ORDER BY publication.id DESC ');
                        while($publication = $statement->fetch())
                        {
                            echo '<tr>';
                                echo '<td>' . $publication['titre'] . '</td>';
                                echo '<td>' . $publication['image'] . '</td>';
                                echo '<td>' . $publication['datepublication'] . '</td>';
                                echo '<td>' . $publication['category'] . '</td>';
                                echo '<td class="rem30">';
                                    echo '<a href="viewgalerie.php?id=' . $publication['id'] . '"class="btn btn-info"><span class="fas fa-eye"></span> Voir</a>';
                                    echo '<a href="updategalerie.php?id=' . $publication['id'] . '" class="btn btn-primary"><span class="fas fa-edit"></span> Modifier</a>';
                                    echo '<a href="deletegalerie.php?id=' . $publication['id'] . '" class="btn btn-danger"><span class="fas fa-trash"></span> Supprimer</a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="fas fa-grip-lines"></div>
        <div class="row">
            <h2><strong>Liste des formules</strong></h2>
            <a href="Insertformules.php" class="btn btn-success btn-lg"><span class="fas fa-plus"></span>Ajouter</a>
        </div>

        <div class="row">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th>Nom de la formule</th>
                        <th>image</th>
                        <th>prix</th>
                        <th>description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $statement = $db->query('SELECT formules.id, formules.name, formules.image, formules.description, formules.price from formules ORDER BY formules.id DESC ');
                         while($formule = $statement->fetch())
                         {
                            echo '<tr>';
                                echo '<td>' . $formule['name'] . '</td>';
                                echo '<td>' . $formule['image'] . '</td>';
                                echo '<td>' . $formule['price'] . '€</td>';
                                echo '<td>' . $formule['description'] . '</td>';
                                echo '<td class="rem30">';
                                    echo '<a href="viewformules.php?id=' . $formule['id'] . '"class="btn btn-info"><span class="fas fa-eye"></span> Voir</a>';
                                    echo '<a href="updateformules.php?id=' . $formule['id'] . '" class="btn btn-primary"><span class="fas fa-edit"></span> Modifier</a>';
                                    echo '<a href="deleteformules.php?id=' . $formule['id'] . '" class="btn btn-danger"><span class="fas fa-trash"></span> Supprimer</a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="fas fa-grip-lines"></div>
        <div class="row">
            <h2><strong>Liste des messages</strong></h2>
            <a href="Insertformules.php" class="btn btn-success btn-lg"><span class="fas fa-plus"></span>Ajouter</a>
        </div>

        <div class="row">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th>Nom</th>
                        <th>Prenom</th>
                        <th>Email</th>
                        <th>téléphone</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $statement = $db->query('SELECT contact.id, contact.nom, contact.prenom, contact.email, contact.telephone, contact.message, contact.datemessage from contact ORDER BY contact.id DESC ');
                         while($contact = $statement->fetch())
                         {
                            echo '<tr>';
                                echo '<td>' . $contact['prenom'] . '</td>';
                                echo '<td>' . $contact['nom'] . '</td>';
                                echo '<td>' . $contact['email'] . '</td>';
                                echo '<td>' . $contact['telephone'] . '</td>';
                                echo '<td>' . $contact['message'] . '</td>';
                                echo '<td class="rem30">';
                                    echo '<a href="viewformules.php?id=' . $contact['id'] . '"class="btn btn-info"><span class="fas fa-eye"></span> Voir</a>';
                                    echo '<a href="updateformules.php?id=' . $contact['id'] . '" class="btn btn-primary"><span class="fas fa-edit"></span> Modifier</a>';
                                    echo '<a href="deleteformules.php?id=' . $contact['id'] . '" class="btn btn-danger"><span class="fas fa-trash"></span> Supprimer</a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="fas fa-grip-lines"></div>
        <div class="row">
            <h2><strong>Liste des réservations</strong></h2>
            <a href="Insertformules.php" class="btn btn-success btn-lg"><span class="fas fa-plus"></span>Ajouter</a>
        </div>

        <div class="row">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th>Nom</th>
                        <th>Prenom</th>
                        <th>Email</th>
                        <th>téléphone</th>
                        <th>Message</th>
                        <th>formule</th>
                        <th>date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // $statement = $db->query('SELECT reservations.id, reservations.nom, reservations.prenom, reservations.email, reservations.telephone, reservations.message, reservations.datereservation, reservations.formule from reservations ORDER BY reservations.id DESC ');
                        $statement = $db->query('SELECT reservations.id, reservations.nom, reservations.prenom, reservations.email, reservations.telephone, reservations.message, reservations.datereservation from reservations INNER JOIN formules on reservations.formule = formules.name ORDER BY reservations.id DESC ');
                        // $formuleName = $db->query('SELECT formules.name from formules INNER JOIN reservations on formules.id = reservations.formule');
                         while($reservation = $statement->fetch())
                         {
                            echo '<tr>';
                                echo '<td>' . $reservation['nom'] . '</td>';
                                echo '<td>' . $reservation['prenom'] . '</td>';
                                echo '<td>' . $reservation['email'] . '</td>';
                                echo '<td>' . $reservation['telephone'] . '</td>';
                                echo '<td>' . $reservation['message'] . '</td>';
                                echo '<td>' . $reservation['formule'] . '</td>';
                                echo '<td>' . $reservation['datereservation'] . '</td>';
                                echo '<td class="rem30">';
                                    echo '<a href="viewformules.php?id=' . $reservation['id'] . '"class="btn btn-info"><span class="fas fa-eye"></span> Voir</a>';
                                    echo '<a href="updateformules.php?id=' . $reservation['id'] . '" class="btn btn-primary"><span class="fas fa-edit"></span> Modifier</a>';
                                    echo '<a href="deleteformules.php?id=' . $reservation['id'] . '" class="btn btn-danger"><span class="fas fa-trash"></span> Supprimer</a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                        Database::disconnect();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>