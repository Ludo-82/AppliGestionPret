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

if(isset($_POST['recuperer'])){
    if(isset($_POST['Type'], $_POST['Num'], $_POST['NomE'], $_POST['EmailE'], $_POST['Vacataire'], $_POST['datereception'])){
        if($_POST['Type']!=''&& $_POST['Num']!=''&& $_POST['NomE']!=''&& $_POST['EmailE']!=''&& $_POST['Vacataire']!=''&& $_POST['datereception']!=''){
            $numero_materiel=$_POST['Num'];
            $vacat=$_POST['Vacataire'];
            $email=$bdd->query('SELECT EmailVac FROM vacataire WHERE NomVac LIKE "%'.$vacat.'%"');
            $email=$email->fetch(PDO::FETCH_ASSOC);
            $emailV=$email['EmailVac'];
            

            $insertion="INSERT INTO maintenir(NumMat,EmailVac) VALUES ('$numero_materiel','$emailV')";
            $execute=$bdd->query($insertion);
            if($execute==true){
                $maj="UPDATE materiel
                SET EtatMat='Récupéré'
                WHERE NumMat LIKE '%".$numero_materiel."%'";
                $update=$bdd->query($maj);

                echo "Le matériel récupéré a été enregistrée !";
            }else{
                echo "Le matériel récupéré n'a pas pu être enregistrer";
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
<?php

$users=$bdd->query('SELECT NomVac, PrenomVac From vacataire' );
$users->execute();
$users=$users->fetchall();

?>
<div id="header">
    <img class="logo" src="images/logo.gif">
    <img class="logo" src="images/titre.png">
    <div>
    <?php
      foreach($users as $row){
        echo"<p class='head-nav'>"."Bienvenue ".$row['PrenomVac']." ".$row['NomVac']." | <a href='acceuil.php'>Accueil</a> | Plan | Déconnexion </p>";
      }
    ?>
    </div>
    <ul id="nav">
        <a href='demande.php'><li>Demande de prêt</li></a>
        <a href='retour.php'><li>Retour de matériel</li></a>
        <a href='validationreception.php'><li style="background-color:#00FFFF;">Validation reception</li></a>
        <a href='intervention.php'><li>Intervention</li></a>
    </ul>
</div>
<div id="corpsVP">

<?php 
$allmats = $bdd->query('SELECT E.EmailEmp As Email, E.NomEmp As Nom, C.Type As Materiel, M.NumMat As Numero, Em.DateRetourReel As Day
FROM materiel M, emprunter Em, emprunteur E, categorie_mat C
WHERE M.NumMat = Em.NumMat
AND M.CodeCateMat = C.CodeCateMat
AND Em.EmailEmp = E.EmailEmp
AND Em.DateRetourReel is not null
AND Em.DateRetourReel in (SELECT max(Em2.DateRetourReel) FROM emprunter Em2 GROUP BY Em2.NumMat );');

if(empty($allmats)){
    echo"<div class='vide'>Aucune nouvelle matériel retourné</div>";
}
else{
    echo"<h1>Liste de matériels retournés</h1>";
    echo'<form name="ligne" method="GET">';
        echo"<input type='search' class='info-input' name='s' placeholder='Numéro du matériel'>";
        echo"<input type='submit' class='search_' name='envoyer'>
    </form>
    <section class=afficher_users>";


        if(isset($_GET['envoyer'])){
            if(isset($_GET['s']) AND !empty($_GET['s'])){
                $recherche = htmlspecialchars($_GET['s']);
                $allmats = $bdd->query('SELECT E.EmailEmp As Email, E.NomEmp As Nom, C.Type As Materiel, M.NumMat As Numero, Em.DateRetourReel As Day
                FROM materiel M, emprunter Em, emprunteur E, categorie_mat C
                WHERE M.NumMat = Em.NumMat
                AND M.CodeCateMat = C.CodeCateMat
                AND Em.EmailEmp = E.EmailEmp
                AND Em.DateRetourReel is not null
                AND Em.DateRetourReel in (SELECT max(Em2.DateRetourReel) FROM emprunter Em2 GROUP BY Em2.NumMat )
                AND M.NumMat LIKE "%'.$recherche.'%"');
            }
        }
        if($allmats->rowCount()>0){
            echo "<table class='recep' id='recep' border='1'>\n";
            echo "<tr>\n";
            echo "<th><strong>Type de matériel</strong></th>\n";
            echo "<th><strong>Numéro du matériel</strong></th>\n";
            echo "<th><strong>Nom du dernier emprunteur</strong></th>\n";
            echo "<th><strong>E-mail de l'emprunteur</strong></th>\n";
            echo "<th><strong>Date de récupération</strong></th>\n";

        echo "</tr>\n";
            $i=0;
            while ($mat = $allmats->fetch()) {
                ++$i;
                echo '<tr class="select"  id="'.$i.'" onclick="recupID(this)">';
                echo"<td id='A".$i."'>".$mat['Materiel']."</td>";
                echo"<td id='B".$i."'>".$mat['Numero']."</td>";
                echo"<td id='C".$i."'>".$mat['Nom']."</td>";
                echo"<td id='D".$i."'>".$mat['Email']."</td>";
                echo"<td id='E".$i."'>".changedateusfr($mat['Day'])."</td>";

                echo '</tr>'."\n";


            }
        }else{
            echo "<p>Aucune matériel retourné</p>";
        }
    }



    echo '</table>'."\n";// fin du tableau.
    echo '</section>';
    echo"<form method='POST'>
        <input type='number' class='c' name='c' id='c' readonly>
    </form>";
    echo" <input type='submit' class='valid-button' value='SELECTIONNER' id='boutton' onclick='recepmat();'>";


    echo "<div id='revoir'>";
    echo "<form action='' name='selection' id='selection' method='POST'>";
    $revoir = $bdd->query('SELECT NumMat FROM materiel');
    $revoir->execute();
    $table_revoir=$revoir->fetchall();
    echo "<table class='recep' id='res' border='1'>\n";
    echo "<tr>\n";
    echo "<th><strong>Type de matériel</strong></th>\n";
    echo "<th><strong>Numéro du matériel</strong></th>\n";
    echo "<th><strong>Nom du dernier emprunteur</strong></th>\n";
    echo "<th><strong>E-mail de l'emprunteur</strong></th>\n";
    echo "<th><strong>Vacataire</strong></th>\n";
    echo "<th><strong>Date de récupération</strong></th>\n";

    echo "</tr>\n";

    echo '<tr class="select">';
    echo'<td><input type="text" class="resume-input" name="Type" id="Type" readonly></td>';
    echo'<td><input type="text" class="resume-input" name="Num" id="Num" readonly></td>';
    echo'<td><input type="text" class="resume-input" name="NomE" id="NomE" readonly></td>';
    echo'<td><input type="text" class="resume-input" name="EmailE" id="EmailE" readonly></td>';

    
    echo'<td>
    <select class="resume-input" name="Vacataire" id="Vacataire" required>';
    //requete
    $vacataires=$bdd->query('SELECT * FROM vacataire');
    $table_vacataire=$vacataires->fetchall();
    foreach($table_vacataire as $row){
    
    
        echo'<option value="'.$row["NomVac"].'">'.$row["NomVac"].'</option></td>'};;

    echo'<td><input type="text" class="resume-input" name="datereception" id="datereception"></td>';
    echo '</tr>'."\n";

    echo '</table>'."\n";// fin du tableau.


    echo"<input type='submit' class='valid-button' value='RECUPERER' name='recuperer'>";
    echo"<input type='reset' class='return-button' value='ANNULER' name='reset'>";
    echo "</form>";
    echo"</div>";
    

?>

<script type="text/javascript">

function recupID(obj){
    var id=obj.id;
    var tab = document.getElementById('recep');
    
    document.getElementById('c').value=id;
    for (let i = 1; i< 1000; i++){
        if(i!=id){
            document.getElementById(''+ i +'').style.color='grey';
        }else{
            document.getElementById(''+ i +'').style.color='darkred';
        }
    }
}
  function recepmat(id){


    var id = document.getElementById('c').value;

    if (id!=""){

    document.getElementById("Type").value=document.getElementById("A"+id+"").innerHTML;

    document.getElementById("Num").value=document.getElementById("B"+id+"").innerHTML;

    document.getElementById("NomE").value=document.getElementById("C"+id+"").innerHTML;

    document.getElementById("EmailE").value=document.getElementById("D"+id+"").innerHTML;

    document.getElementById("Vacataire").value=document.getElementById("Vacataire").innerHTML;

    document.getElementById("datereception").value=document.getElementById("E"+id+"").innerHTML;

    }else{
        alert("Selectionnez un matériel retourné");
    }
  }
</script>
</div>
</body>
</html>