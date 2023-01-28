<?php
include '../connexionbdd.php';

$id = $_GET["idCadenas"];
$statut = $_GET["statut"];



if($statut == 0) //Si le cadenas envoi la requête en mode Veille
{
    //Vérifie l'état du cadenas dans la BDD
    $query= "SELECT Actif FROM fablab.cadenas WHERE idMacAddress = ? ; ";

    if ($stmt = $bdd->prepare($query)) {
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt->bind_result($Actif);
        $stmt->fetch();

        //Renvoi l'état du cadenas de la BDD vers le cadenas
        $tab=array("idCadenas"=>$id,"Actif"=>$Actif);
        echo(json_encode($tab));

        $stmt->close();
    }

}
else if($statut == 1) //Si le cadenas envoi la requête en mode Actif
{
    //Vérifie si le cadenas a déja été utilisé
    $query= "SELECT cadenas_idCadenas FROM fablab.logs WHERE cadenas_idCadenas = ? ; ";

    if ($stmt = $bdd->prepare($query)) {
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt->bind_result($idCadenas);

        //Si le cadenas a des logs
        if($stmt->fetch())
        {
            $nbActif = -1;
            $date = date('Y-m-d',(strtotime ('-7 day', strtotime(date('Y-m-d')))));

            // /!\ NE FONCTIONNE PAS /!\

            //Vérifie si le cadenas a été utilisé dans les 7 derniers jours
            $querylog = "SELECT count(cadenas_idCadenas) as nombre FROM fablab.logs WHERE Date >= '".$date."' and cadenas_idCadenas = '".$id."' ; ";

            echo($querylog);

            if ($stmtlog = $bdd->query($querylog)) {
                $stmtlog->bind_result($nombre);
                $stmtlog->fetch();
                echo($nombre);
                $nbActif = $nombre;
                $stmtlog->close();
            }

        }
        $stmt->close();
    }
}

$bdd->close();

/*
<?php
include '../connexionbdd.php';

$id = $_GET["idCadenas"];
$statut = $_GET["statut"];

$CadenasNB = -1;


date_default_timezone_set('Europe/Paris');

//La veille se fait au bout de 7 jours d'inactivité du cadenas
$date = date('Y-m-d');
$date1 = date('Y-m-d',(strtotime ('-7 day', strtotime($date))));

//echo(gettype($id));

$query= "SELECT cadenas_idCadenas FROM fablab.logs WHERE cadenas_idCadenas = ? ; ";

if ($stmt = $bdd->prepare($query)) {
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->bind_result($idCadenas);

    if($stmt->fetch())
    {
        $querylog = "SELECT count(cadenas_idCadenas) as nombre FROM fablab.logs WHERE Date >= ? and cadenas_idCadenas = ? ; ";

        if ($stmtlog = $bdd->prepare($querylog)) {
            $stmtlog->bind_param('ss', $date1, $id);
            $stmtlog->execute();
            $stmtlog->bind_result($nombre);
            $stmtlog->fetch();
            $CadenasNB = $nombre;
            echo($nombre);
            $stmtlog->close();
        }
        else
            echo("erreur");
    }
    else
    {
        $CadenasNB = 1;
    }
    $stmt->close();
}


if($CadenasNB == 0) $setActif = 0;
if($CadenasNB > 0)  $setActif = 1;


if($CadenasNB != -1)
{
    $queryActif = "UPDATE cadenas SET `Actif` = ? WHERE (`idMacAddress` = ?);";
    if ($stmtActif = $bdd->prepare($queryActif)) {
        $stmtActif->bind_param('is',$setActif, $id);
        $stmtActif->execute();
    }
}


$query = "SELECT Actif from fablab.cadenas where idMacAddress = ?";
if ($stmt = $bdd->prepare($query)) {
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->bind_result($Actif);
    echo($Actif);

    $tab=array("idCadenas"=>$id,"Actif"=>$Actif);

    echo(json_encode($tab));
    $stmt->close();

}

$bdd->close();

 */
