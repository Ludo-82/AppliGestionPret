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
function changedateusfr($dateus)
  {
    $datefr=$dateus{8}.$dateus{9}."/".$dateus{5}.$dateus{6}."/".$dateus{0}.$dateus{1}.$dateus{2}.$dateus{3};
    return $datefr;
  }
function changedatefrus($datefr)
{
  $dateus=$datefr{6}.$datefr{7}.$datefr{8}.$datefr{9}."-".$datefr{3}.$datefr{4}."-".$datefr{0}.$datefr{1};
  return $dateus;
}
//Ajout des nouvelles disponibilités
if(isset($_POST['valider'])){//bouton valider enclenché
    if(isset($_POST['dispo'], $_POST['heure'])){
      if($_POST['dispo']!='' && $_POST['heure']!='' ){
        $dispo=$_POST['dispo'];
        $heure=$_POST['heure'];

        $users=$bdd->query('SELECT EmailGes From gestionnaire' );
        $users->execute();
        $users=$users->fetchall();
        foreach($users as $row){
          $email=$row['EmailGes'];
        }

        //Préparation de la requête d'insertion
        $insertion = "INSERT INTO calendrier (DateDispo) VALUES ('$dispo')";
        $execute=$bdd->query($insertion);
        $insertion2 = "INSERT INTO etre_dispo (EmailGes, DateDispo, HeureDispo) VALUES ('$email','$dispo','$heure')";
        $execute2=$bdd->query($insertion2);
        if($execute==true && $execute2==true){
          $msgSucess="Nouvelle disponibilité enregistrée !";
        }else{
          if($execute2==false)
          $msgError="Disponnibilité déjà enregistrer";
        }
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
    /*  $users=$bdd->query('SELECT NomGes, PrenomGes From gestionnaire' );
      $users->execute();
      $users=$users->fetchall();
      foreach($users as $row){
        echo"<p class='head-nav'> | <a href='acceuil.php'>Accueil</a> | Plan | Déconnexion </p>";
      } */
    ?>
    </div>
    <ul id="nav">
        <a href='horaires.php'><li style="background-color:#00FFFF;">MAJ plage horaire</li></a>
        <a href='receptionmatnormal.php'><li>Réception Matériel</li></a>
        <a href='validationpret.php'><li>Validation prêt</li></a>
        <a href='ajout.php'><li>Ajout matériel</li></a>
        <a href='ajoutmodele.php'><li>Ajout modèle</li></a>
    </ul>
</div>
<div id="corps">

    <form class='horaires' action='horaires.php' method='POST'>
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

      <label for="dispo">Date</label></br>
      <?php
        $today=date('Y-m-d');
        echo'<input type="date" class="horaire-input" name="dispo" placeholder="JJ/MM/AAAA"  min="'.$today.'" required>(*)</br>';
      ?>
      <label for="heure">Créneau</label></br>
      <input type="text" class="horaire-input" name="heure" required>(*)

      <input type='submit' class='info-button' name='valider' value='AJOUTER'>

      <?php
      //on récupère les dates de dispo du gestionnaire
      $dispo=$bdd->query('SELECT DateDispo, HeureDispo FROM etre_dispo');
      $table_dispo=$dispo->fetchall();
      if(empty($table_dispo)){
        echo "<p>Aucune disponnibilité</p>";
      }else{
      echo "<table class='date_dispo' border='1'>\n";
      echo "<tr>\n";
      echo "<th>Date</th>\n";
      echo "<th>Heure</th>\n";
      echo "</tr>\n";

        foreach($table_dispo as $row){
          echo '<tr>';
          echo '<td bgcolor="#FFFFFF">'.changedateusfr($row["DateDispo"]).'</td>';
          echo '<td bgcolor="#FFFFFF">'.$row["HeureDispo"].'</td>';
          echo '</tr>'."\n";
        }
        echo '</table>'."\n";// fin du tableau.
      }
      ?>
      </form>
      <form class='horaires' action="delete.php">
        <input type='submit' class='valid-button' name='delete' value='SUPPRIMER'>
      </form>

</div>
</body>
</html>
