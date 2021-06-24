<?php
try
{
  // On se connecte à MySQL
  $bdd = new PDO('mysql:host=localhost;dbname=pret;charset=utf8', 'root', '');
}
catch(Exception $e)
{
  // En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}  
 ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="header">
    <img class="logo" src="images/logo.gif">
    <img class="logo" src="images/titre.png">
    <ul id="nav">
      <a href='retour.php'><li>Retour de matériel</li></a>
      <a href=''><li>MAJ plage horaire</li></a>
      <a href=''><li>Réception Matériel</li></a>
      <a href='validation.php'><li>Validation prêt</li></a>
      <a href='ajout.php'><li>Ajout matériel</li></a>
      <a href='ajoutmodele.php'><li>Ajout modèle</li></a>
      <a href=''><li>Validation reception</li></a>
      <a href=''><li>Intervention</li></a>
    </ul>
</div>
<div id="corps">


</div>
</body>
</html>