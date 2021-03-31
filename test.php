<!DOCTYPE html>
<head>
<title>test $_POST </title>
<meta charset="UTF-8">
</head>
<body>

<form action="" method="post">
    entrez un texte : <input type="text" name="texte" value="txt ici" />
    <input type="submit" name="submit" value="ok" />
</form>

<?php
if ( !empty($_POST) ) {	// évite d'afficher un tableau vide avec isset
	var_dump( $_POST );
} else {
	echo "remplissez le formulaire <br>\n";
}
?>

</html>
