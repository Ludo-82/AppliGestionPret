<?php
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
if(isset($_POST['valider']))
{
 if(isset($_POST['Numemp'], $_POST['TypeMat'], $_POST['EmailEmp'], $_POST['NomEmp'], $_POST['DateRetrait'], $_POST['DateRetourReel']))
  {
    if($_POST['Numemp']!='' && $_POST['TypeMat']!='' && $_POST['EmailEmp']!='' && $_POST['NomEmp']!='' && $_POST['DateRetrait']!='')
    {
      $DateRetourReel = date("Y-m-d H:i:s");
      $NumCont = $_POST['Numemp'];
      $EmailEmp = $_POST['EmailEmp'];
      $NumEmp = $_POST['TypeMat'];
      $execute1 = $bdd->query("UPDATE emprunter SET DateRetourReel = '$DateRetourReel' WHERE emprunter.NumMat = '$NumEmp' AND emprunter.EmailEmp = '$EmailEmp' AND emprunter.NumCont = '$NumCont'");
      $execute2 = $bdd->query("UPDATE materiel SET EtatMat = 'retourné' WHERE materiel.NumMat = '$NumEmp' ");
      if($execute1==true && $execute2==true)
      {
        echo"Demande enregistrée !";
        $retour = mail('monmail@gmail.com', 'Demande de retour','Le matériel '.$_POST['TypeMat'].' Numéro '.$_POST['TypeMat'].' a été retourné.');
      }
      else
      {
        echo"Erreur";
      }


    }

  }else{
    echo "erreur";
  }
}
 ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style2.css">
<link rel="stylesheet" href="style.css">
<script type="text/javascript" src="index.js"></script>
</head>
<body>

<div id="header">
    <img class="logo" src="images/logo.gif">
    <img class="logo" src="images/titre.png">
    <div>
    <?php
    /*  $users=$bdd->query('SELECT NomGes, PrenomGes From gestionnaire');
      $users->execute();
      $users=$users->fetchall();
      foreach($users as $row){
          echo"<p class='head-nav'> | <a href='acceuil.php'>Accueil</a> | Plan | Déconnexion </p>";
      } */
    ?>
    </div>
    <ul id="nav">
        <a href='horaires.php'><li >MAJ plage horaire</li></a>
        <a href='receptionmatnormal.php'><li style="background-color:#00FFFF;">Réception Matériel</li></a>
        <a href='validationpret.php'><li>Validation prêt</li></a>
        <a href='ajout.php'><li>Ajout matériel</li></a>
        <a href='ajoutmodele.php'><li>Ajout modèle</li></a>
    </ul>
</div>

<div class="corpsVP">

<!--Bouton retour urgent -->
<div id="boutton">
    <a class="valid-button" id="retourNormalButton" href="receptionmatnormal.php">Retour du jour</a>
</div>

<!--TABLEAU RETOUR URGENT -->
<div id="rdvUrgent" class="hide">
<?php
//On récupère les données pour le tableau liste RDV urgent
$pret=$bdd->query('SELECT E.NumCont, E.NumMat, E.EmailEmp, Em.NomEmp,E.DateRetrait, E.DateRetourSouhaite
FROM Emprunter E, Emprunteur Em
WHERE E.EmailEmp = Em.EmailEmp
AND E.DateRetourReel IS NULL');

    echo"<h1>Liste de tous les rendez-vous</h1>";
    echo'<form name="ligne" method="GET">';
        echo"<input type='search' class='info-input' name='s' placeholder='Rechercher un emprunteur'>";
        echo"<input type='submit' class='search_' name='envoyer'>
    </form>
    <section class=afficher_users>";


        if(isset($_GET['envoyer'])){
            if(isset($_GET['s']) AND !empty($_GET['s'])){
                $recherche = htmlspecialchars($_GET['s']);
                $pret = $bdd->query('SELECT E.NumCont, E.NumMat, E.EmailEmp, Em.NomEmp,E.DateRetrait, E.DateRetourSouhaite
                FROM Emprunter E, Emprunteur Em
                WHERE E.EmailEmp = Em.EmailEmp
                AND Em.NomEmp LIKE "%'.$recherche.'%"');
            }
        if($pret->rowCount()>0){
            echo "<table class='demande2' id='demande2' border='1'>\n";
            echo "<tr>\n";
            echo "<th><strong>Numéro de prêt</strong></th>\n";
            echo "<th><strong>Numéro matériel</strong></th>\n";
            echo "<th><strong>E-mail de l'emprunteur</strong></th>\n";
            echo "<th><strong>Nom de l'emprunteur</strong></th>\n";
            echo "<th><strong>Date de retrait</strong></th>\n";
            echo "<th><strong>Date du RDV</strong></th>\n";

        echo "</tr>\n";
            $i=0;
            while ($user = $pret->fetch()) {
                ++$i;
                echo '<tr class="select"  id="'.$i.'" onclick="recupID(this)">';
                echo"<td id='A".$i."'>".$user['NumCont']."</td>";
                echo"<td id='B".$i."'>".$user['NumMat']."</td>";
                echo"<td id='C".$i."'>".$user['EmailEmp']."</td>";
                echo"<td id='D".$i."'>".$user['NomEmp']."</td>";
                echo"<td id='E".$i."'>".changedateusfr($user['DateRetrait'])."</td>";
                echo"<td id='F".$i."'>".changedateusfr($user['DateRetourSouhaite'])."</td>";

                echo '</tr>'."\n";


            }
        }else{
            echo "<p>Aucune demande à ce nom</p>";
        }
    }
    else{
        echo "<table class='demande2' id='demande' border='1'>\n";
        echo "<tr>\n";
        echo "<th><strong>Numéro de prêt</strong></th>\n";
        echo "<th><strong>Numéro matériel</strong></th>\n";
        echo "<th><strong>E-mail de l'emprunteur</strong></th>\n";
        echo "<th><strong>Nom de l'emprunteur</strong></th>\n";
        echo "<th><strong>Date de retrait</strong></th>\n";
        echo "<th><strong>Date du RDV</strong></th>\n";

        echo "</tr>\n";
        $i=0;
        foreach ($pret as $row){
            ++$i;
            echo '<tr class="select" onclick="recupID(this)" id="'.$i.'">';
            echo"<td id='A".$i."'>".$row['NumCont']."</td>";
            echo"<td id='B".$i."'>".$row['NumMat']."</td>";
            echo"<td id='C".$i."'>".$row['EmailEmp']."</td>";
            echo"<td id='D".$i."'>".$row['NomEmp']."</td>";
            echo"<td id='E".$i."'>".changedateusfr($row['DateRetrait'])."</td>";
            echo"<td id='F".$i."'>".changedateusfr($row['DateRetourSouhaite'])."</td>";

            echo '</tr>'."\n";
        }
    }

        echo '</table>'."\n";// fin du tableau.

    echo"</section>";
    echo"<form method='POST'>
            <input type='number' class='c' name='c' id='c' readonly>
        </form>";


//Bouton sélection
echo"<input type='submit' class='valid-button' value='SELECTIONNER' onclick='resumeDemande();'>";

?>
</div>

<div class="resume_array">
<?php

    echo "<div id='resume'>";
    echo"<h1>RESUME</h1>";

    echo "<form action='' method='POST'>";

    $resume = $bdd->query('SELECT NumMat FROM materiel');
    $resume->execute();
    $table_resume=$resume->fetchall();
    echo "<table class='resume' id='res' border='1'>\n";
    echo "<tr>\n";
    echo "<th><strong>Numéro du prêt</strong></th>\n";
    echo "<th><strong>Numéro du matériel</strong></th>\n";
    echo "<th><strong>E-mail de l'emprunteur</strong></th>\n";
    echo "<th><strong>Nom de l'emprunteur</strong></th>\n";
    echo "<th><strong>Date de retrait</strong></th>\n";
    echo "<th><strong>Date de retour effective</strong></th>\n";

    echo "</tr>\n";

       echo '<tr>';
        echo'<td><input type="text" class="resume-input" name="Numemp" id="Numemp" readonly></td>';
        echo'<td><input type="text" class="resume-input" name="TypeMat" id="TypeMat" readonly></td>';
        echo'<td><input type="text" class="resume-input" name="EmailEmp" id="EmailEmp" readonly></td>';
        echo'<td><input type="text" class="resume-input" name="NomEmp" id="NomEmp" readonly></td>';
        echo'<td><input type="text" class="resume-input" name="DateRetrait" id="DateRetrait" readonly></td>';
        echo'<td><input type="text" class="resume-input" name="DateRetourReel" id="DateRetourReel"></td>';
        echo '</tr>'."\n";

    echo '</table>'."\n";// fin du tableau.
    echo "</div>";

    echo"<div class=threeButton>";
    echo"<input type='submit' class='valid-button' name='valider' value='VALIDER'>";
    echo"<input type='button' class='return-button' id='button3' value='ANNULER' onclick='reload();'>";
    echo"</div>";
    echo "</form>";



 ?>
</div>

</div>
<script type="text/javascript">

function recupID(obj){
    var id=obj.id;
    var tab = document.getElementById('demande');

    document.getElementById('c').value=id;
    for (let i = 1; i< 1000; i++){
        if(i!=id){
            document.getElementById(''+ id +'').style.color='grey';
        }else{
            document.getElementById(''+ id +'').style.color='darkred';
        }
    }
}

  function resumeDemande(id){

  var id = document.getElementById('c').value;

    if (id!=""){

    document.getElementById("Numemp").value=document.getElementById("A"+id+"").innerHTML;
    document.getElementById("TypeMat").value=document.getElementById("B"+id+"").innerHTML;
    document.getElementById("EmailEmp").value=document.getElementById("C"+id+"").innerHTML;
    document.getElementById("NomEmp").value=document.getElementById("D"+id+"").innerHTML;
    document.getElementById("DateRetrait").value=document.getElementById("E"+id+"").innerHTML;

    var Now = new Date();
    document.getElementById("DateRetourReel").value= Now.getDate() + "/" + ('0'+(Now.getMonth()+1)).slice(-2) + "/" + Now.getFullYear();
    }else{
        alert("Selectionnez une demande");
    }
  }

  function reload(){
    window.location.href='receptionmaturgent.php';
  }
</script>
</body>
</html>
