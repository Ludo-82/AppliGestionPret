<?php
     header('Location: horaires.php');
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
     //Tu recuperes l'id du contact
     //Requete SQL pour supprimer le contact dans la base
     $vider=$bdd->query("TRUNCATE TABLE etre_dispo");
     //Et la tu rediriges vers ta page contacts.php pour rafraichir la liste

?>