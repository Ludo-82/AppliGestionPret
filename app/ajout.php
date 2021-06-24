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

if(isset($_POST['submit'])){
  if(isset($_POST['identifiant'], $_POST['anneeA'])){
    if($_POST['identifiant']!='' && $_POST['anneeA']!=''){
      $identifiant=$_POST['identifiant'];
      $anneeA=$_POST['anneeA'];
      $modele=$bdd->query('SELECT CodeCateMat FROM categorie_mat WHERE Modele='.$_POST['modele'].'');
      $modele=$modele->fetch();
      //Préparation de la requête d'insertion
      $insertion = "INSERT INTO materiel (NumMat, DateReception, EtatMat, CodeCateMat) VALUES ('$identifiant','$anneeA','Disponible','','$code')";
      $execute=$bdd->query($insertion);
      if($execute==true){
        $msgSucess="Le matériel a été enregistrée !";
      }else{
        $msgError="Le matériel n'as pas pu être enregistrer";
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
<title>Ajouter materiel</title>
</head>
<body>
<div id="header">
    <img class="logo" src="images/logo.gif">
    <img class="logo" src="images/titre.png">
    <div>
    <?php
    /*  $users=$bdd->query('SELECT NomGes, PrenomGes From gestionnaire' );
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
        <a href='ajout.php'><li style="background-color:#00FFFF;">Ajout matériel</li></a>
        <a href='ajoutmodele.php'><li>Ajout modèle</li></a>

    </ul>
</div>
<div id="corps">
    <div id="nouveaumodele">
      <form action='ajout.php' method='POST' onsubmit='result()'>
        <label for="typemat">Type</label>
        <select name="typemat" class="info-select" id="type-select" required>
          <option value="">--Sélectionnez un matériel--</option>
          <option value="PC">Ordinateur portable</option>
          <option value="tablette">Tablette</option>
          <option value="cle">Clé 4G</option>
        </select>



        <label for="modele">Modèle</label>
        <select name="modele" class="info-select" id="modele" required>
          <option value="">--Sélectionnez un modele--</option>
          <?php
          //requete

            $modele=$bdd->query('SELECT * FROM categorie_mat');
            $table_bdd=$modele->fetchall();

          //on ajoute touts les modèles exsistant de la base de donées
          foreach($table_bdd as $row){

            echo '<option value="'.$row["Modele"].'">'.$row["Marque"].' '.$row["Modele"].'</option>';

          }

          ?>
        </select>

        <label for="quantite">Quantité</label>
        <input type="number" class="info-input" name="quantite" id="quantite" required>

        <label for="bondecommande">Bon de commande</label>
        <input type="file" class="info-input" name="bondecommande">

        <label for="annee">Date de réception</label>
        <input type="date" class="info-input" name="annee" id="annee" required>

        <input type='button' class='add-button' value='VALIDER' name='valider_modele' onclick="compteur()">
        <input type="button" value="NOUVEAU MODELE" class='add-button' onClick="window.location.href='ajoutmodele.php'">
      </form>
    </div>
    <div id="nouveaumateriel">
      <div>
      </div>
      <input type="number" class="compt-input" name="compteur1" id="compteur1" readonly>/
      <input type="number" class="compt-input" name="compteur" id="compteur" readonly></br>

      <form action='ajout.php' method='POST'>

        <label for="identifiant">Identifiant du matériel</label>
        <input type="text" class="info-input" name="identifiant" id="identifiant">

        <label for="anneeA">Date de réception</label>
        <input type="year" class="info-input" name="anneeA" id="anneeA">

        <input type='button' class='info-button' value='AJOUTER' name='addmat' onclick='addl()' >
        <input type="button" value="ANNULER" class='info-button' onclick='reset();'>
        </form>
    </div>
    <div id='resumeMateriel'>
      <form>
        <table id='resume' class='demande'>
          <tr>
            <th>Identifiant matériel</th>
            <th>Date de réception</th>
            <th>Modèle</th>
          </tr>

        </table>
        <input type='submit' class='info-button' value='VALIDER' name='addallmat' id='addallmat'>
      </form>
    </div>
</div>
<script src="js/fonction.js"></script>
</body>
</html>
