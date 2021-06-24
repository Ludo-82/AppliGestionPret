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
if(isset($_POST['valider']))
{
   if(isset($_POST['IdMat'], $_POST['TypeMat'], $_POST['dateRetrait'], $_POST['DateDispo'], $_POST['DateRetourSouhaite']))
    {
        if($_POST['IdMat']!='' && $_POST['TypeMat']!='' && $_POST['dateRetrait']!='' && $_POST['DateDispo']!='' && $_POST['DateRetourSouhaite']!='')
        {
          $DateR= $_POST['DateRetourSouhaite'];
          $retrait = changedatefrus($_POST['dateRetrait']);
          $query=$bdd->query('SELECT NumCont FROM emprunter WHERE dateretrait="'.$retrait.'"');
          $num_row = $query->fetch(PDO::FETCH_ASSOC);
          $num = $num_row['NumCont'];
         $execute1="UPDATE
                      emprunter
                    SET
                      DateRetourSouhaite ='$DateR'
                    WHERE
                      NumCont='".$num."'";
         }
        $resultat=$bdd->query($execute1);
        if($resultat==true){
            echo "Demande de retour enregistrée !";

            $retour = mail('monmail@gmail.com', 'Demande de retour','Demande de retour effectuée pour un/une '.$_POST['TypeMat'].' Numéro '.$_POST['IdMat'].'.');
        }else{
             echo "Erreur !";
        }
     }
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
    /*  $users=$bdd->query('SELECT NomEmp, PrenomEmp From EMPRUNTEUR WHERE NomEmp LIKE "%HE%"' );
      $users->execute();
      $users=$users->fetchall();

        echo"<p class='head-nav'> | <a href='acceuil.php'>Accueil</a> | Plan | Déconnexion </p>";
*/
    ?>
    </div>
    <ul id="nav">
      <a href='demande.php'><li>Demande de prêt</li></a>
      <a href='retour.php'><li style="background-color:#00FFFF;">Retour de matériel</li></a>
    </ul>
</div>
<div id="corpsVP">

    <?php
      $allusers=$bdd->query('SELECT C.Type, M.NumMat, E.Dateretrait
      FROM CATEGORIE_MAT C, MATERIEL M, EMPRUNTER E, EMPRUNTEUR EM
      WHERE E.NumMat = M.NumMat
      AND M.CodeCateMat = C.CodeCateMat
      AND E.EmailEmp = EM.EmailEmp
      AND E.emailemp="maildestinataire@gmail.com"
      AND E.DateRetourSouhaite is NULL');
      if(empty($allusers))
      {
        echo"<div class='vide'>Aucun prêt en cours</div>";
      }
      else
      {
        echo"<h1>Liste de vos prêts en cours</h1>";
        echo"<p>Date</p>";
        echo'<form method="GET">';
        echo"<input type='date' class='info-input' name='s' placeholder='Date de retrait'>";
        echo"<input type='submit' class='search_' name='Rechercher' value='Rechercher'>
    </form>

    <section class=afficher_users>";
        echo "<div id='scrolltab'>";
        echo "<table class='encours' border='1'>\n";
        echo "<tr>\n";
        echo "<th><strong>Type de matériel</strong></th>\n";
        echo "<th><strong>Numéro du matériel</strong></th>\n";
        echo "<th><strong>Date de retrait</strong></th>\n";
        //echo "<th></th>\n";
        echo "</tr>\n";
         if(isset($_GET['Rechercher'])){
            if(isset($_GET['s']) AND !empty($_GET['s'])){
                $recherche = htmlspecialchars($_GET['s']);
                $allusers = $bdd->query('SELECT C.Type, M.NumMat, E.Dateretrait
                FROM CATEGORIE_MAT C, MATERIEL M, EMPRUNTER E, EMPRUNTEUR EM
                WHERE E.NumMat = M.NumMat
                AND M.CodeCateMat = C.CodeCateMat
                AND E.EmailEmp = EM.EmailEmp
                AND E.DateRetrait LIKE "%'.$recherche.'%"');
          }
        }
        if($allusers->rowCount()>0){
           $i=1;
            while ($user = $allusers->fetch()) {
              echo '<tr class="select" onclick="recupID(this)" id="'.$i.'">';
              echo"<td id ='A".$i."'>".$user['Type']."</td>";
              echo"<td id ='B".$i."'>".$user['NumMat']."</td>";
              echo"<td id ='C".$i."'>".changedateusfr($user['Dateretrait'])."</td>";
              //echo"<td><input type='radio' name='selection' value='Ticket' checked id='id_paiement'></td>";
             ++$i;
              echo "</tr>\n";
            }

        }
        else{

          echo"<p>Aucun prêt en cours</p>";

        }
          }
          echo '</table>'."\n";// fin du tableau.
          echo "</div>";
          echo"</section>";
          echo"<form method='POST'>
                <input type='number' class='c' name='c' id='c' readonly>
              </form>";

         echo "<br>";
        echo "<input type='submit' class='search_' name='selectionner' value='selectionner' onclick='validation()'>";

         echo "<br>";
         echo "<br>";
            echo"<form method='POST'>";
            echo "<table class='resume' border ='1' id='validation'>";
            echo "<tr>";
            echo    "<th>";
            echo    "<span>Type de matériel </span>";
            echo    "</th>";
            echo    "<th>Numéro du matériel</th>";
            echo    "<th>";
            echo    "<span>Date de retrait </span>";
            echo    "</th>";
            echo    "<th>Date de retour dispo</th>";
            echo    "</th>";
            echo    "<th>Date de retour souhaitée</th>";
            echo "</tr>\n";

        echo '<tr>';
        echo'<td><input type="text" class="resume-input" name="TypeMat" id="TypeMat" readonly></td>';
        echo'<td><input type="text" class="resume-input" name="IdMat" id="IdMat" readonly></td>';
        echo'<td><input type="text" class="resume-input" name="dateRetrait" id="dateRetrait"readonly></td>';
        echo'<td> <select name="DateDispo" id="DateDispo" class="info-select" required> ';

        $disponi=$bdd->query('SELECT * From etre_dispo' );
        $table_bdd=$disponi->fetchall();
        //on ajoute tous les dates de dispo de la gestionnaire exsistant de la base de donées
        if(empty($table_bdd)){
          echo '<option>Aucune disponnibilité</option>';
        }else{
          foreach($table_bdd as $row){
            echo '<option value='.$row["DateDispo"].'>'.changedateusfr($row["DateDispo"]).' à '.$row["HeureDispo"].'</option>';
            }
        }
        echo "</select></td>";
        echo "<td>";
        $today=date('Y-m-d');
        echo'<input type="date" class="info-input" name="DateRetourSouhaite" id="DateRetourSouhaite" placeholder="JJ/MM/AAAA"  min="'.$today.'" required></br>';
       echo"</td>";

        echo '</tr>'."\n";

    echo '</table>'."\n";// fin du tableau

    echo"<input type='submit' class='valid-button' name='valider' value='VALIDER'>";
    echo"<input type='reset' class='return-button' value='ANNULER'>";
    echo"</div>";
    echo"</form>";

    ?>
</div>
</div>
<script type="text/javascript">

function recupID(obj){
    var id=obj.id;
    var tab = document.getElementById('encours');

    document.getElementById('c').value=id;
    for (let i = 1; i< 1000; i++){
        if(i!=id){
            document.getElementById(''+ id +'').style.color='grey';
        }else{
            document.getElementById(''+ id +'').style.color='darkred';
        }
    }
}

function validation(id){


    var tableau = document.getElementById('validation');

    var id = document.getElementById('c').value;

    if (id!=""){
      document.getElementById("TypeMat").value=document.getElementById("A"+id+"").innerHTML;

     document.getElementById("IdMat").value=document.getElementById("B"+id+"").innerHTML;

  document.getElementById("dateRetrait").value=document.getElementById("C"+id+"").innerHTML;


    }else{
    }
  }
</script>
</body>
</html>
