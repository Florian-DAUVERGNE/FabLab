<?php
include'../connexionbdd.php';

$mail=$_GET['mail'];

if(isset($_GET["brut"])){
    $query="Select count(hour(Horaire))as nb_heure,hour(Horaire) FROM fablab.logs inner join adherent on adherent_idAdherent=idAdherent WHERE Mail='{$_GET['mail']}' group by hour(Horaire);";
    if ($stmt = $bdd->prepare($query)) {
        $json=array();
        $stmt->execute();
        $stmt->bind_result($nb_heure,$Horaire);
        $nombre=0;
        while ($stmt->fetch()){
            $nombre+=$nb_heure;

            $tab_nombre=array("Nombre"=>$nombre);
            $tab=array("nb_heure"=>$nb_heure, "Horaire"=>$Horaire);
            array_push($json,$tab);
        }
        array_push($json,$tab_nombre);
        echo(json_encode($json));

        $stmt->close();
    }
}


if(isset($_GET["cadenas"])){
    $query="Select count(cadenas_idCadenas) as badger,NomCadenas FROM fablab.logs inner join cadenas on cadenas_idCadenas=idMacAddress inner join adherent on adherent_idAdherent=idAdherent where Mail='{$_GET["mail"]}' group by cadenas_idCadenas,NomCadenas ORDER BY badger desc;";
    if ($stmt = $bdd->prepare($query)) {
        $json=array();
        $stmt->execute();
        $stmt->bind_result($badger,$NomCadenas);
        while ($stmt->fetch()){
            $tab=array("badger"=>$badger, "NomCadenas"=>$NomCadenas);
            array_push($json,$tab);
        }
        echo(json_encode($json));
        $stmt->close();
    }
}

if(isset($_GET["heures"])) {
    $query = "Select hour(Horaire)  FROM fablab.logs inner join adherent on adherent_idAdherent=idAdherent where Mail='{$_GET["mail"]}' ";
    if ($stmt = $bdd->prepare($query)) {
        $json = array();
        $stmt->execute();
        $stmt->bind_result($Heure);
        $tab = array("8h" => 0, "9h" => 0, "10h" => 0, "11h" => 0, "12h" => 0, "13h" => 0, "14h" => 0, "15h" => 0, "16h" => 0, "17h" => 0, "18h" => 0);


        while ($stmt->fetch()) {
            switch ($Heure) {
                case 8:
                    $tab["8h"] += 1;
                    break;
                case 9:
                    $tab["9h"] += 1;
                    break;
                case 10:
                    $tab["10h"] += 1;
                    break;
                case 11:
                    $tab["11h"] += 1;
                    break;
                case 12:
                    $tab["12h"] += 1;
                    break;
                case 13:
                    $tab["13h"] += 1;
                    break;
                case 14:
                    $tab["14h"] += 1;
                    break;
                case 15:
                    $tab["15h"] += 1;
                    break;
                case 16:
                    $tab["16h"] += 1;
                    break;
                case 17:
                    $tab["17h"] += 1;
                    break;
                case 18:
                    $tab["18h"] += 1;
                    break;

            }

        }
        echo(json_encode($tab));
        $stmt->close();
    }
}
$bdd->close();