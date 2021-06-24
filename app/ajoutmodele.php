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
//Ajour du nouveau modele
if(isset($_POST['submit'])){//bouton valider enclenché
  if(isset($_POST['materiel'], $_POST['marque'], $_POST['modele'], $_POST['stockage'], $_POST['ram'], $_POST['cg'])){
    if($_POST['materiel']!='' && $_POST['marque']!='' && $_POST['modele']!=''){
      $type_materiel=$_POST['materiel'];
      $marque=$_POST['marque'];
      $modele=$_POST['modele'];
      $stockage=$_POST['stockage'];
      $ram=$_POST['ram'];
      $cg=$_POST['cg'];


        //Préparation de la requête d'insertion
        $insertion = "INSERT INTO categorie_mat (Type, Marque, Modele, Stockage, RAM, CarteGraphique) VALUES ('$type_materiel','$marque','$modele','$stockage','$ram','$cg')";
        $execute=$bdd->query($insertion);
        if($execute==true){
          $msgSucess="Le modèle a été enregistrée !";
        }else{
          $msgError="Le modèle n'as pas pu être enregistrer";
        }

    }else{
      $msgError="Veuillez renseigner tous les champs obligatoires (*)";
    }
  }
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
    <div>
    <?php
      /*$users=$bdd->query('SELECT NomGes, PrenomGes From gestionnaire' );
      $users->execute();
      $users=$users->fetchall();
      foreach($users as $row){
        echo"<p class='head-nav'> | <a href='acceuil.php'>Accueil</a> | Plan | Déconnexion </p>";
      }*/
    ?>
    </div>
    <ul id="nav">

      <a href='horaires.php'><li>MAJ plage horaire</li></a>
      <a href='receptionmatnormal.php'><li>Réception Matériel</li></a>
      <a href='validationpret.php'><li>Validation prêt</li></a>
      <a href='ajout.php'><li>Ajout matériel</li></a>
      <a href='ajoutmodele.php'><li style="background-color:#00FFFF;">Ajout modèle</li></a>

    </ul>
</div>
<div id="corps">




<form class="body_ajoutmodele" action="ajoutmodele.php" method="POST">

<h1>Ajouter un modèle</h1>

<div id='msg'>
      <?php
      if(isset($msgError)){
        echo $msgError;
      }elseif(isset($msgSucess)){
        echo $msgSucess;
      }else{
        echo'';
      }
      ?>
  </div>

  <label for="materiel">Type de matériel</label><br>
  <select name="materiel" id="mat-select" class="info-select">
    <option value="">--Sélectionnez un matériel--</option>
    <option value="PC">Ordinateur Portable</option>
    <option value="Tablette">Tablette</option>
    <option value="Cle3G">Clé 3G</option>

  </select>

  <label for="marque">Marque (*)</label>
  <input type="text" class="info-input" name="marque" id="marque">

  <label for="modele">Modèle (*)</label>
  <input type="text" class="info-input" name="modele" id="modele">

  <label for="stockage">Stockage</label>
  <input type="text" class="info-input" name="stockage" id="stockage">

  <label for="ram">RAM</label>
  <input type="text" class="info-input" name="ram" id="ram">

  <label for="cg">Carte Graphique</label>
  <input type="text" class="info-input" name="cg" id="cg">


  <input type="submit" class='info-button' name="submit" value="AJOUTER">
  <input type="reset" class='info-button' name="reset" value="ANNULER">
</form>


</div>
</body>
</html>
