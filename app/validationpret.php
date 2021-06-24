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
    function changedatefrus($datefr){
    $dateus=$datefr{6}.$datefr{7}.$datefr{8}.$datefr{9}."-".$datefr{3}.$datefr{4}."-".$datefr{0}.$datefr{1};
    return $dateus;
    }
  if(isset($_POST['valider'])){
    if(isset($_POST['IdMat'], $_POST['TypeMat'], $_POST['Nom'], $_POST['Prenom'], $_POST['Gestionnaire'], $_POST['dateRetrait'])){
        if($_POST['IdMat']!='' && $_POST['TypeMat']!='' && $_POST['Nom']!='' && $_POST['Prenom']!='' && $_POST['Gestionnaire']!='' && $_POST['dateRetrait']!=''){
          $numeromat=$_POST['IdMat'];
          //on recupère l'email du gestionnaire
          $gest=$_POST['Gestionnaire'];
          $email=$bdd->query('SELECT EmailGes FROM gestionnaire WHERE NomGes LIKE "%'.$gest.'%"');
          $email=$email->fetchall();
          foreach($email as $row){
            $email=$row['EmailGes'];
            }
          $nom=$_POST['Nom'];
          $prenom=$_POST['Prenom'];
          $emailE=$bdd->query('SELECT EmailEmp FROM emprunteur WHERE NomEmp LIKE "%'.$nom.'%"');
          $emailE=$emailE->fetchall();
          foreach($emailE as $row){
            $emailE=$row['EmailEmp'];
            }

          $dateretrait=changedatefrus($_POST['dateRetrait']);
          $dateD=$_POST['dateD'];
          $Etat = 'Pret';
          $numcont=$bdd->query('SELECT NumCont FROM contrat');
          $numcont=$numcont->fetchall();
          foreach($numcont as $row){
            $numcont=$row['NumCont'];
          }

          $numcont=$numcont+1;

          //$retour = mail($_POST['emailE'],'Emprunt', 'Votre emprunt concernant le matériel '.$_POST['IdMat'].' a été enregistrée');
          //if($retour==true){
          //    $msgSucessMail="Un email de confirmation vous a été envoyer !";
          //    echo '<script type="text/javascript">window.alert("'.$msgSucessMail.'");</script>';
          //  }
          //  else{
          //  $msgErrorMail="L'envoi du email de confirmation n'as pas pu être effectué";
          //  }


          //Préparation de la requête d'insertion
          //On commence par créer le contrat
          //on ajoute a la table contrat
          $insertion1 = "INSERT INTO contrat(NumCont, ApercuCont, EmailGes) VALUES ( '$numcont','null','$email')";
          $execute1=$bdd->query($insertion1);
          //on procede a l'emprunt avec le contrat précédenment créé
          if($execute1==true){
            //on ajoute a la table emprunter
            $insertion2 = "INSERT INTO emprunter(DateRetrait,DateRetourSouhaite,DateRetourReel, NumMat, EmailEmp, NumCont) VALUES ( '$dateretrait',NULL,NULL,'$numeromat','$emailE', '$numcont')";
            $execute2=$bdd->query($insertion2);
            if($execute2==true){
                //on met a jour l'état du matériel selectionné en Pret
                $maj="UPDATE materiel
                SET EtatMat='Pret'
                WHERE NumMat LIKE '%".$numeromat."%'";
                $update=$bdd->query($maj);

               //2021-06-18 10:19:39
                if($update==true ){
                    $majD="UPDATE demander
                    SET Etat=1
                    WHERE CAST(DateDemande AS CHARACTER) LIKE '%".$dateD."%' ";
                    $update2=$bdd->query($majD);
                    if($update2==true){
                        $msgSucess="L'emprunt a été enregistrée !";
                        $retour = mail($emailE,'Emprunt de matériel informatique', 'Confirmation de votre emprunt concernant le matériel '.$_POST['IdMat'].' ');
                        if($retour==true){
                            echo '<script type="text/javascript">window.alert("Emprunt confirmé");</script>';
                        }else{
                            $msgErrorMail="L'envoi du email de confirmation n'as pas pu être effectué";
                        }
                        echo '<script type="text/javascript">window.alert("'.$msgSucess.'");</script>';
                    }else{
                        $msgError="L'emprunt n'as pas pu être validé 4 !";
                    }
                }else{
                        $msgError="L'emprunt n'as pas pu être validé 3 !";
                }

            }else{
                $msgError="L'emprunt n'as pas pu être validé 2 !";
            }

        }else{
            $msgError="L'emprunt n'as pas pu être validé 1 !";
        }
    }

    }
    }




?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css" />
<title>Validation pret</title>
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
        echo'<p class="head-nav"> | <a href="acceuil.php">Accueil</a> | Plan | Déconnexion </p>';
      }*/
    ?>
    </div>
    <ul id="nav">
        <a href='horaires.php'><li >MAJ plage horaire</li></a>
        <a href='receptionmatnormal.php'><li>Réception Matériel</li></a>
        <a href='validationpret.php'><li style="background-color:#00FFFF;">Validation prêt</li></a>
        <a href='ajout.php'><li>Ajout matériel</li></a>
        <a href='ajoutmodele.php'><li>Ajout modèle</li></a>
    </ul>
</div>
<div id="corpsVP">


<?php

//on récupère les données pour le tableau demande
$allusers = $bdd->query('SELECT E.EmailEmp As Email, E.NomEmp As Nom, E.PrenomEmp As Prenom, LibelleForma, C.Type As Materiel, DateDemande
FROM demander D, emprunteur E, categorie_mat C, formation F
WHERE D.EmailEmp=E.EmailEmp
AND D.CodeCateMat=C.CodeCateMat
AND F.CodeForma=E.CodeForma
AND Etat=0');

if(empty($allusers)){
    echo"<div class='vide'>Aucune nouvelle demande de prêt</div>";
}
else{
    echo"<h1>DEMANDE</h1>";
    echo"<form name='ligne' method='GET'>
        <input type='search' class='info-input' name='s' placeholder='Rechercher un emprunteur'>
        <input type='submit' class='search_' name='envoyer'>
    </form>
    <section class=afficher_users>";


    if(isset($_GET['envoyer'])){
        if(isset($_GET['s']) AND !empty($_GET['s'])){

            $recherche = htmlspecialchars($_GET['s']);
            $allusersR = $bdd->query('SELECT E.EmailEmp As Email, E.NomEmp As Nom, E.PrenomEmp As Prenom, LibelleForma, C.Type As Materiel, DateDemande
            FROM demander D, emprunteur E, categorie_mat C, formation F
            WHERE D.EmailEmp=E.EmailEmp
            AND D.CodeCateMat=C.CodeCateMat
            AND F.CodeForma=E.CodeForma
            AND Etat=0
            AND E.NomEmp LIKE "%'.$recherche.'%"');

        }
        if($_GET['s']==''){
            echo "<div id='scrolltab'>";
            echo "<p>Veuillez renseignez un nom.</p>";
        }else{
            if($allusersR->rowCount()>0){
                echo "<div id='scrolltab'>";
                echo "<table class='demande' id='demande' border='1'>\n";
                echo "<tr>\n";
                echo "<th><strong>Identifiant demandeur</strong></th>\n";
                echo "<th><strong>Nom</strong></th>\n";
                echo "<th><strong>Prénom</strong></th>\n";
                echo "<th><strong>Formation</strong></th>\n";
                echo "<th><strong>Matériel demandé</strong></th>\n";
                echo "<th><strong>Date de la demande</strong></th>\n";

                echo "</tr>\n";
                $i=0;
                while ($user = $allusersR->fetch()) {
                    ++$i;
                    echo '<tr class="select"  id="'.$i.'" onclick="recupID(this)">';
                    echo"<td id='A".$i."'>".$user['Email']."</td>";
                    echo"<td id='B".$i."'>".$user['Nom']."</td>";
                    echo"<td id='C".$i."'>".$user['Prenom']."</td>";
                    echo"<td id='D".$i."'>".$user['LibelleForma']."</td>";
                    echo"<td id='E".$i."'>".$user['Materiel']."</td>";
                    echo"<td id='F".$i."'>".$user['DateDemande']."</td>";

                    echo '</tr>'."\n";




                }
            }else{
                echo "<div id='scrolltab'>";
                echo "<p>Aucune correspondance à ce nom</p>";

            }
        }
    }else{
            echo "<div id='scrolltab'>";
            echo "<table class='demande' id='demande' border='1'>\n";
            echo "<tr>\n";
            echo "<th><strong>Identifiant demandeur</strong></th>\n";
            echo "<th><strong>Nom</strong></th>\n";
            echo "<th><strong>Prénom</strong></th>\n";
            echo "<th><strong>Formation</strong></th>\n";
            echo "<th><strong>Matériel demandé</strong></th>\n";
            echo "<th><strong>Date de la demande</strong></th>\n";
            echo "</tr>\n";
            $i=0;

            foreach ($allusers as $row){
                ++$i;
                echo '<tr class="select" onclick="recupID(this)" id="'.$i.'">';
                echo"<td id='A".$i."'>".$row['Email']."</td>";
                echo"<td id='B".$i."'>".$row['Nom']."</td>";
                echo"<td id='C".$i."'>".$row['Prenom']."</td>";
                echo"<td id='D".$i."'>".$row['LibelleForma']."</td>";
                echo"<td id='E".$i."'>".$row['Materiel']."</td>";
                echo"<td id='F".$i."'>".$row['DateDemande']."</td>";

                echo '</tr>'."\n";
            }
        }



            echo '</table>'."\n";// fin du tableau.
            echo'</div>';

        echo"</section>";
        echo"<form method='POST'>
            <input type='number' class='c' name='c' id='c' readonly>
            </form>";

    echo"<h1>MATERIEL</h1>";
    echo'<form action="" method="GET">';
        echo"<input type='search' class='info-input' name='id' placeholder='Saisir le numéro du matériel'>";
        echo"<input type='submit' class='search_' name='envoyerMat'>";
    echo"</form>";


    if(isset($_GET['envoyerMat'])){
    if(isset($_GET['id'])){
        if(isset($_GET['id']) AND !empty($_GET['id'])){
            $mat = htmlspecialchars($_GET['id']);
            $materiel = $bdd->query('SELECT NumMat, Type, Marque, Modele, Stockage, RAM, CarteGraphique, DateReception
            FROM materiel M, categorie_mat C
            WHERE M.CodeCateMat=C.CodeCateMat
            AND EtatMat="Disponible"
            AND M.NumMat LIKE "%'.$mat.'%"');
            $table_materiel=$materiel->fetchall();
            if(empty($table_materiel)){
                echo"<p>Pas de matériel correspondant ou matériel non disponible</p>";
            }else{
            //entete
            echo "<table class='materiel' border='1'>\n";
            echo "<tr>\n";
            echo "<th><strong>Numéro</strong></th>\n";
            echo "<th><strong>Type</strong></th>\n";
            echo "<th><strong>Marque</strong></th>\n";
            echo "<th><strong>Modèle</strong></th>\n";
            echo "<th><strong>Capacité de stockage</strong></th>\n";
            echo "<th><strong>Memoire RAM</strong></th>\n";
            echo "<th><strong>Mémoire graphique demandé</strong></th>\n";
            echo "<th><strong>Année d'acquisition</strong></th>\n";

            echo "</tr>\n";


            foreach($table_materiel as $row){
                echo '<tr>';
                echo '<td id="a">'.$row["NumMat"].'</td>';
                echo '<td id="b">'.$row["Type"].'</td>';
                echo '<td id="c">'.$row["Marque"].'</td>';
                echo '<td id="d">'.$row["Modele"].'</td>';
                echo '<td id="e">'.$row["Stockage"].'</td>';
                echo '<td id="f">'.$row["RAM"].'</td>';
                echo '<td id="g">'.$row["CarteGraphique"].'</td>';
                echo '<td id="h">'.changedateusfr($row["DateReception"]).'</td>';
                echo '</tr>'."\n";
            }
            echo '</table>'."\n";// fin du tableau.


    echo"<input type='submit' class='valid-button' value='SELECTIONNER' onclick='resumeDemande();'>";
            }
        }
    }
    }
    echo "<div id='resume'>";
    echo"<h1>RESUME</h1>";
    echo'<form action="" name="selection" id="selection" method="POST">';
    $resume = $bdd->query('SELECT NumMat FROM materiel');
    $resume->execute();
    $table_resume=$resume->fetchall();
    echo "<table class='resume' id='res' border='1'>\n";
    echo "<tr>\n";
    echo "<th><strong>Date de la demande</strong></th>\n";
    echo "<th><strong>Numéro du matériel</strong></th>\n";
    echo "<th><strong>Type</strong></th>\n";
    echo "<th><strong>Nom de l'emprunteur</strong></th>\n";
    echo "<th><strong>Prénom de l'emprunteur</strong></th>\n";
    echo "<th><strong>Gestionnaire</strong></th>\n";
    echo "<th><strong>Date de retrait</strong></th>\n";

    echo "</tr>\n";

    echo '<tr>';
    echo'<td><input type="text" class="resume-input" name="dateD" id="dateD"></td>';
    echo'<td><input type="text" class="resume-input" name="IdMat" id="IdMat" readonly></td>';
    echo'<td><input type="text" class="resume-input" name="TypeMat" id="TypeMat" readonly></td>';
    echo'<td><input type="text" class="resume-input" name="Nom" id="Nom" readonly></td>';
    echo'<td><input type="text" class="resume-input" name="Prenom" id="Prenom" readonly></td>';
    echo'<td><input type="text" class="resume-input" name="Gestionnaire" id="Gestionnaire" readonly></td>';
    echo'<td><input type="text" class="resume-input" name="dateRetrait" id="dateRetrait"></td>';
    echo '</tr>'."\n";

    echo '</table>'."\n";// fin du tableau.

    echo'<input type="button" class="return-button" name="imprimer" value="IMPRIMER" onclick="genContrat()" >';
    echo"<input type='submit' class='valid-button' name='valider' value='VALIDER'>";
    echo"<input type='reset' class='return-button' value='ANNULER'>";
    echo"</div>";
    echo"</form>";
}
?>
</div>
<script type="text/javascript">

function genContrat(){
    w=open("",'popup','width=800,height=500,toolbar=no,scrollbars=no,resizable=yes');

    w.document.write("<head>");
    w.document.write('<link rel="stylesheet" href="styleContrat.css" />');
    w.document.write("<title>Contrat</title></head>");
    w.document.write("<BODY>");
    w.document.write("<h1>Contrat d'emprunt de matériel</h1>");
    w.document.write("<p>Je soussigné "+ document.forms['selection'].elements["Nom"].value+" " + document.forms['selection'].elements["Prenom"].value+" ");
    w.document.write("déclare recevoir le matériel n°"+ document.forms['selection'].elements["IdMat"].value+" de type "+ document.forms['selection'].elements["TypeMat"].value);
    w.document.write("</p>");
    w.document.write("<p>Je m'engage à le restituer à tout moment si le responsable de la formation en a besoin ou avant le 14 Juillet 2021 dans le pire des cas.");
    w.document.write("</p>");
    w.document.write("<div id='sign'>");
    w.document.write("Fait à ................<br>");
    w.document.write("Le ...........<br>");
    w.document.write("Signature :");
    w.document.write("</div>");
    w.document.write("<input type='button' name='print' value='IMPRIMER' onclick='javascript:window.print();'")
    w.document.write("</BODY>");
    w.document.close();

}

function recupID(obj){
    var id=obj.id;
    var tab = document.getElementById('demande');

    document.getElementById('c').value=id;
    for (let i = 1; i< 1000; i++){
        if(i!=id){
            document.getElementById(''+ i +'').style.color='grey';
        }else{
            document.getElementById(''+ i +'').style.color='darkred';
        }
    }
}
  function resumeDemande(id){


    var id = document.getElementById('c').value;

    if (id!=""){

    document.getElementById("IdMat").value=document.getElementById("a").innerHTML;

    document.getElementById("TypeMat").value=document.getElementById("E"+id+"").innerHTML;

	document.getElementById("Nom").value=document.getElementById("B"+id+"").innerHTML;

	document.getElementById("Prenom").value=document.getElementById("C"+id+"").innerHTML;

    document.getElementById("Gestionnaire").value="PAGNOL";

    document.getElementById("dateD").value=document.getElementById("F"+id+"").innerHTML;

    var Now = new Date();
    document.getElementById("dateRetrait").value= Now.getDate() + "/" + ('0'+(Now.getMonth()+1)).slice(-2) + "/" + Now.getFullYear();
    }else{
        alert("Selectionnez une demande");
    }
  }
</script>

</body>
</html>
