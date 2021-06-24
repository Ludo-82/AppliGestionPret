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

if(isset($_POST['dispoSAV'])){//bouton SAV enclenché
 if(isset($_POST['typem'], $_POST['numerom'], $_POST['daterecup'], $_POST['nomv'], $_POST['commentaire'])){
    if($_POST['typem']!='' && $_POST['numerom']!=''&& $_POST['daterecup']!=''&& $_POST['nomv']!=''){
      $nom_vac=$_POST['nomv'];
      $commen=$_POST['commentaire'];
      $date_sav=$_POST["datedepart"];
      $disponi1=$_POST['dispoSAV'];
      $num = $_POST['numerom'];
      $DateRetourReel = date("Y-m-d H:i:s");

      $EmailVac=$bdd->query("SELECT EmailVac AS Email FROM vacataire WHERE NomVac LIKE'%".$nom_vac."%'");
      $mailVac=$EmailVac->fetchall();
      foreach ($mailVac as $row) {
        $mailVac=$row['Email'];
      }
      if (isset($_POST['dispoSAV'])){
        if($_POST['datedepart']!=''){



          //Préparation de la requête d'insertion

          $insertion = "INSERT INTO maintenir (EmailVac, NumMat, DateIntervention, DateSAV, DateRecup, Commentaire, EtatMaintenir) VALUES ('$mailVac', '$num', NOW(), '$date_sav', NULL, '$commen', NULL)";
          $execute=$bdd->query($insertion);
          if($execute==TRUE){
            $maj = "UPDATE materiel SET EtatMat='$disponi1' WHERE NumMat LIKE '%".$num."%'";
            $execute2=$bdd->query($maj);
            if($execute2==true){
              echo"Matériel parti en SAV";
            }
          }else{
            echo 'ERROR insertion   !'.$date_sav.'';
          }







        }
      }
    }
  }
}

if(isset($_POST['dispoOUI'])){  //bouton Disponible enclenché
 if(isset($_POST['typem'], $_POST['numerom'], $_POST['daterecup'], $_POST['nomv'], $_POST['commentaire'])){
    if($_POST['typem']!='' && $_POST['numerom']!=''&& $_POST['daterecup']!=''&& $_POST['nomv']!=''){
      $nom_vac=$_POST['nomv'];
      $commen=$_POST['commentaire'];
      $date_sav=$_POST['datedepart'];
      $disponi2='Disponible';

      //Ici on récupère l'email du vacataire ayant intervenu
      $EmailVac=$bdd->query("SELECT EmailVac AS Email FROM vacataire WHERE NomVac LIKE'%".$nom_vac."%'");
      $mailVac=$EmailVac->fetchall();
      foreach ($mailVac as $row) {
        $mailVac=$row['Email'];
      }
      if (isset($_POST['dispoOUI'])){
        if($_POST['datedepart']==''){
          $num2 = $_POST['numerom'];

          //Préparation de la requête d'insertion
          $insertion2 = "INSERT INTO maintenir (EmailVac, NumMat, DateIntervention, DateSAV, DateRecup, Commentaire, EtatMaintenir) VALUES ('$mailVac', '$num2', NOW(), NULL, '$commen' ,NULL,NULL)";
          $executeBis=$bdd->query($insertion2);
          if($executeBis==TRUE){
            $maj = "UPDATE materiel SET EtatMat='$disponi2' WHERE NumMat LIKE '%".$num2."%'";
            $execute2=$bdd->query($maj);
            if($execute2==true){
              echo"Matériel de nouveau disponible, pensez à le renvoyer au local";
            }
          }else{

            echo 'ERROR insertion2 !';
          }
        }
      }
    }
  }
}

if(isset($_POST['dispoRetire'])){  //bouton Retire du parc enclenché
 if(isset($_POST['typem'], $_POST['numerom'], $_POST['daterecup'], $_POST['nomv'], $_POST['commentaire'])){
    if($_POST['typem']!='' && $_POST['numerom']!=''&& $_POST['daterecup']!=''&& $_POST['nomv']!=''){
      $nom_vac=$_POST['nomv'];
      $commen=$_POST['commentaire'];
      $date_sav=$_POST['datedepart'];
      $disponi2="RETIRER DU PARC";

      //Ici on récupère l'email du vacataire ayant intervenu
      $EmailVac=$bdd->query("SELECT EmailVac AS Email FROM vacataire WHERE NomVac LIKE'%".$nom_vac."%'");
      $mailVac=$EmailVac->fetchall();
      foreach ($mailVac as $row) {
        $mailVac=$row['Email'];
      }
      if (isset($_POST['dispoRetire'])){
        if($_POST['datedepart']==''){

          $num3 = $_POST['numerom'];

          //Préparation de la requête d'insertion
          //Préparation de la requête d'insertion
          $insertion3 = "INSERT INTO maintenir (EmailVac, NumMat, DateIntervention, DateSAV, DateRecup, Commentaire, EtatMaintenir) VALUES ('$mailVac', '$num3', NOW(), NULL, NULL,NULL,NULL)";
          $executeTer=$bdd->query($insertion3);
          if($executeTer==true){
            $maj = "UPDATE materiel SET EtatMat='$disponi2' WHERE NumMat LIKE '%".$num3."%'";
            $execute3=$bdd->query($maj);
            if($execute3==true){
              echo"Matériel retirer du parc informatique";
            }
          }else{

            echo 'ERROR insertion3 !';
          }
        }
      }
    }
  }
}



?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css" />
<title>Intervention</title>
</head>
<body>
  <?php

/*$users=$bdd->query('SELECT NomVac, PrenomVac From vacataire
  WHERE NomVac="HAKIM"' );
$users->execute();
$users=$users->fetchall();
*/
?>
<div id="header">
    <img class="logo" src="images/logo.gif">
    <img class="logo" src="images/titre.png">
    <div>
      <!--<?php
      //foreach($users as $row){
        //echo"<p class='head-nav'>"."Bienvenue ".$row['PrenomVac']." ".$row['NomVac']." | Déconnexion </p>";
    ?>!-->
</div>
    <ul id="nav">

      <a href='validationreception.php'><li>Validation reception</li></a>
      <a href='intervention.php'><li style="background-color:#00FFFF;">Intervention</li></a>
    </ul>
</div>
<div id="corpsVP">



<?php
//matériel est récupérer quand EtatMat=Receptionner
$allusers = $bdd->query('SELECT Ca.Type, M.NumMat, E.NomEmp, E.EmailEmp, Ma.DateIntervention
 FROM MATERIEL M, Emprunter Em, EMPRUNTEUR E, categorie_mat ca, maintenir Ma
 WHERE Ma.NumMat=M.NumMat
 AND M.NumMat=Em.NumMat
 AND Em.EmailEmp=E.EmailEmp
 AND ca.CodeCateMat=m.CodeCateMat
 AND (M.EtatMat="Récupéré" OR M.EtatMat="SAV")
 AND Ma.DateIntervention in(Select max(Ma2.DateIntervention)
  FROM maintenir Ma2
  GROUP BY Ma2.NumMat)
  GROUP BY Ma.NumMat');

if(empty($allusers)){
  echo"<div class='vide'>Aucune nouvelle intervention</div>";
}else{
  echo"<h1>Liste de matériel récupéré</h1>";
  echo'<form name="ligne" method="GET">';
  echo"<input type='search' class='info-input' name='s' placeholder='Numéro du matériel'>";
  echo"<input type='submit' class='search_' name='envoyer'>

    </form>

  <section class=afficher_users>";

  if(isset($_GET['envoyer'])){
    if(isset($_GET['s']) AND !empty($_GET['s'])){
      $recherche = htmlspecialchars($_GET['s']);
      $allusers = $bdd->query('SELECT Ca.Type, M.NumMat, E.NomEmp, E.EmailEmp, Ma.DateIntervention
      FROM MATERIEL M, Emprunter Em, EMPRUNTEUR E, categorie_mat ca, maintenir Ma
      WHERE Ma.NumMat=M.NumMat
      AND M.NumMat=Em.NumMat
      AND Em.EmailEmp=E.EmailEmp
      AND ca.CodeCateMat=m.CodeCateMat
      AND Ma.DateIntervention
       in(Select max(Ma2.DateIntervention)
      FROM maintenir Ma2
      GROUP BY Ma2.NumMat)
      AND M.NumMat LIKE "%'.$recherche.'%"
      GROUP BY Ma.NumMat');
    }
    if($allusers->rowCount()>0){
      echo "<table class='intervention' id='intervention' border='1'>\n";
      echo "<tr>\n";
      echo "<th><strong>Type de matériel</strong></th>\n";
      echo "<th><strong>Numéro du matériel</strong></th>\n";
      echo "<th><strong>Nom du dernier emprunteur</strong></th>\n";
      echo "<th><strong>E-mail de l'emprunteur</strong></th>\n";
      echo "<th><strong>Date de réception </strong></th>\n";
      echo "</tr>\n";

      $i=0;
      while ($user = $allusers->fetch()) {
        ++$i;
        echo '<tr class="select"  id="'.$i.'" onclick="recupID(this)">';

        echo"<td id='A".$i."'>".$user['Type']."</td>";
        echo"<td id='B".$i."'>".$user['NumMat']."</td>";
        echo"<td id='C".$i."'>".$user['NomEmp']."</td>";
        echo"<td id='D".$i."'>".$user['EmailEmp']."</td>";
        echo"<td id='E".$i."'>".$user['DateIntervention']."</td>";

        echo '</tr>'."\n";
      }
      }else{
        echo"<p>Aucune correspondance</p>";
      }
  }else{
    echo "<table class='intervention' id='intervention' border='1'>\n";
    echo "<tr>\n";
    echo "<th><strong>Type de matériel</strong></th>\n";
    echo "<th><strong>Numéro du matériel</strong></th>\n";
    echo "<th><strong>Nom du dernier emprunteur</strong></th>\n";
    echo "<th><strong>E-mail de l'emprunteur</strong></th>\n";
    echo "<th><strong>Date de réception </strong></th>\n";
    echo "</tr>\n";

    $i=0;
    foreach ($allusers as $row){
      ++$i;
      echo '<tr class="select"  id="'.$i.'" onclick="recupID(this)">';
      echo"<td id='A".$i."'>".$row['Type']."</td>";
      echo"<td id='B".$i."'>".$row['NumMat']."</td>";
      echo"<td id='C".$i."'>".$row['NomEmp']."</td>";
      echo"<td id='D".$i."'>".$row['EmailEmp']."</td>";
      echo"<td id='E".$i."'>".$row['DateIntervention']."</td>";

      echo '</tr>'."\n";
    }
    echo '</table>'."\n";
  }

}
echo"<form method='POST'>
        <input type='number' class='c' name='c' id='c' readonly>
    </form>";
    echo"<input type='submit' class='valid-button' value='SELECTIONNER' onclick='selection();'>";

echo"</section>";

?>

<table>

<div id="nouveaumateriel">

  <form  action="intervention.php" method="POST">

  <label for="typem">Type de matériel</label>
  <input type="text" class="info-input" id="typem" name="typem"/>

  <label for="numerom">Numéro matériel</label>
  <input type="text" class="info-input" id="numerom" name="numerom"/>

  <label for="daterecup">Date de récupération</label>
  <input type="text" class="info-input" id="daterecup" name="daterecup"/>

  <label for="nomv">Nom du Vacataire</label>
  <select class="info-select" name="nomv" id="nomv" required>
    <?php
    //requete
    $vac=$bdd->query('SELECT * FROM vacataire');
    $table_vac=$vac->fetchall();
    foreach($table_vac as $row){
      echo '<option value="'.$row["NomVac"].'">'.$row["NomVac"].'</option>';

    }
    ?>
    </select>
  <label for="commentaire">Commentaire</label>
  <textarea type="text" name="commentaire" id="commentaire" placeholder="Entrez votre commentaire" title="Entrez votre commentaire"></textarea><br>

  <label for="datedepart">Date de départ en SAV</label>
  <input type="date" class="info-input" id="datedepart" name="datedepart"/>

  <input type="submit" class="info-button" name="dispoSAV" id="dispo1" value="SAV">
  <input type="submit" class="info-button" name="dispoOUI" id="dispo2" value="Disponible">
  <input type="submit" class="info-button" name="dispoRetire" id="dispo3" value="RETIRE DU PARC">
  <input type="reset" class="info-button" id="dispo4" value="ANNULER">
  </form>

</div>

</table>

<div id="historique">
  <h1>Historique des interventions</h1>
  <?php
// On récupère tout le contenu de la table

$reponse = $bdd->query('SELECT ca.Type, m.NumMat, v.NomVac, ma.Commentaire, ma.DateIntervention, ma.DateSAV, m.EtatMat
FROM materiel m, categorie_mat ca, vacataire v, maintenir ma
WHERE ca.CodeCateMat=m.CodeCateMat
and m.NumMat=ma.NumMat
and v.EmailVac=ma.EmailVac');
$table_bdd=$reponse->fetchall();
echo "<table border='1'>\n";
echo "<tr>\n";
echo "<th><strong>Type de matériel</strong></th>\n";
echo "<th><strong>Numéro du matériel</strong></th>\n";
echo "<th><strong>Nom Vacataire</strong></th>\n";
echo "<th><strong>Commentaire</strong></th>\n";
echo "<th><strong>Date d'intervention</strong></th>\n";
echo "<th><strong>SAV</strong></th>\n";
echo "<th><strong>Date SAV</strong></th>\n";
echo "<th><strong>RETIRE DU PARC</strong></th>\n";

echo "</tr>\n";

foreach($table_bdd as $row){
    echo '<tr>';
    echo '<td>'.$row["Type"].'</td>';
    echo '<td>'.$row["NumMat"].'</td>';
    echo '<td>'.$row["NomVac"].'</td>';
    echo '<td>'.$row["Commentaire"].'</td>';
    echo '<td>'.$row["DateIntervention"].'</td>';
    if($row["DateSAV"]=="")
    {
      echo '<td>'."NON".'</td>';
    }else
    {
      echo '<td>'."OUI".'</td>';
    }
    echo '<td>'.$row["DateSAV"].'</td>';
    if($row["EtatMat"]=="RETIRER DU PARC")
    {
      echo '<td>'."OUI".'</td>';
    }
    else
    {
      echo '<td>'."NON".'</td>';
    }
    echo '</tr>'."\n";
}
echo '</table>'."\n";// fin du tableau.

?>

  </div>

</div>

<script type="text/javascript">
function selection(){
  //recupérer l'id de la ligne selectionnée
  var id = document.getElementById('c').value;
  //si non vide
  if (id!=""){
    document.getElementById('typem').value=document.getElementById('A'+id).innerHTML;
    document.getElementById('numerom').value=document.getElementById('B'+id).innerHTML;
    document.getElementById('daterecup').value=document.getElementById('E'+id).innerHTML;
    document.getElementById('nomv').value='';

  }
}
function recupID(obj){
    var id=obj.id;

    document.getElementById('c').value= id;
    for (let i = 1; i< 1000; i++){
        if(i!=id){
            document.getElementById(''+ id +'').style.color='grey';
        }else{
            document.getElementById(''+ id +'').style.color='darkred';
        }
    }
}

</script>


</body>
</html>
