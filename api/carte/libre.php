<?php
include'../connexionbdd.php';


$query = "SELECT iduid FROM fablab.carte where carte_idAdherent=-1;";
$json = array();
if ($stmt = $bdd->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($iduid);
    $nombre = 0;
    while ($stmt->fetch()) {
        $tab = array("iduid"=>$iduid);
        array_push($json, $tab);

    }
    echo(json_encode($json));
    $stmt->close();
}

$bdd->close();