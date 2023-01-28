<?php
include '../connexionbdd.php';

$idCadenas = 0;
$idCarte = 0;
$idAdherent = 0;

if(isset($_GET['type']))
{
    //Requête depuis un cadenas
    if($_GET['type'] == "cadenas")
    {
        $idCadenas = $_GET['idCadenas'];
        $idCarte = $_GET['idCarte'];

        $Actif1 = 0;
        $Actif2 = 0;
        $GradeAdherent = 0;
        $NiveauCadenas = 0;

        //Requête pour trouver le grade de l'utilisateur en fonction de l'ID de sa carte
        $query = "SELECT idAdherent,Grade,Actif FROM fablab.carte INNER JOIN adherent ON idAdherent = carte_idAdherent where iduid = ?;";

        if ($stmt = $bdd->prepare($query)) {
            $stmt->bind_param('s', $idCarte);

            if ($stmt->execute()) {
                $stmt->bind_result($idAdherent,$Grade, $Actif);

                //Si la carte est reliée à un utilisateur
                if ($stmt->fetch()) {
                    $GradeAdherent = $Grade;
                    $Actif1 = $Actif;
                    $idAdherent = $idAdherent;

                } else { //Si pas de réponse de la requête
                    //Requête pour voir si la carte a un utilisateur
                    $query3 = "SELECT carte_idAdherent FROM fablab.carte where iduid = ?";
                    if ($stmt3 = $bdd->prepare($query3)) {
                        $stmt3->bind_param('i', $idCarte);
                        if ($stmt3->execute()) {
                            $stmt3->bind_result($carte_idAdherent);

                            //Si il n'y a pas de réponse -> la carte n'existe pas
                            if (!$stmt3->fetch()) {
                                //Si la carte n'existe pas -> Création dans la BDD avec son uid
                                $query = "INSERT INTO `fablab`.`carte` (`carte_idAdherent`, `Actif`, `iduid`) VALUES (-1,0,?)";
                                if ($stmt2 = $bdd->prepare($query)) {
                                    $stmt2->bind_param('s', $idCarte);
                                    $stmt2->execute();
                                    $stmt2->close();
                                }
                            }
                            $stmt3->close();
                        }
                    }
                }
            }
            $stmt->close();
        }

        //Requête pour trouver le niveau du cadenas en fonction de son ID
        $query = "SELECT Niveau,Actif FROM fablab.cadenas where idMacAddress = ?;";

        if ($stmt = $bdd->prepare($query)) {
            $stmt->bind_param('s', $idCadenas);
            if ($stmt->execute()) {
                $stmt->bind_result($Niveau, $Actif);

                if($stmt->fetch()) {
                    $NiveauCadenas = $Niveau;
                    $Actif2 = $Actif;
                }
                else {   //Si l'adresse Mac n'est relié à aucun cadenas -> Création du Cadenas dans la BDD
                    $query2 = "INSERT INTO `fablab`.`cadenas` (`Niveau`, `NomCadenas`, `Actif`,`idMacAddress`) VALUES (\"1\",\"NouveauCadenas\",1,\"{$idCadenas}\");";
                    if ($stmt2 = $bdd->prepare($query2)) {
                        $stmt2->execute();
                        $stmt2->close();
                    }
                }

                $stmt->close();
            }
        }


        //Vérifie que le cadenas et la carte sont actif
        if ($Actif1 != 0 && $Actif2 != 0) {
            //Verifie que l'utilisateur à le grade requis pour ouvrir le cadenas
            if ($GradeAdherent >= $NiveauCadenas) {
                $tab = array("succes" => true, "idCadenas" => $idCadenas);

                //Ajoute l'action dans les logs
                date_default_timezone_set('Europe/Paris');
                $date=date("y-m-d");
                $horaire=date("H:i:s");

                $query4 = "INSERT INTO `fablab`.`logs` (`cadenas_idCadenas`, `adherent_idAdherent`, `Horaire`, `Date`) VALUES (?,?,?,?)";
                if ($stmt4 = $bdd->prepare($query4)) {
                    $stmt4->bind_param('siss', $idCadenas, $idAdherent, strval($horaire), strval($date));
                    $stmt4->execute();

                    $stmt4->close();
                }

            } else {
                $tab = array("succes" => false, "idCadenas" => $idCadenas);
            }
        } else {
            $tab = array("succes" => false, "idCadenas" => $idCadenas);
        }


        echo(json_encode($tab));
    }

    //Requête depuis un android
    if($_GET['type'] == "android")
    {
        $idAdherent = $_GET['idAdherent'];


        $query = "SELECT idMacAddress FROM fablab.cadenas where NomCadenas = \"Porte d'entree\"";

        if ($stmt = $bdd->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($idMacAddress);
            if ($stmt->fetch()) {
                $idCadenas = $idMacAddress;
            }
            $stmt->close();
        }


        $jour = date('Y-m-d');
        $heure = date('G:i:s');
        $file = 'ouvrir_distance.json';

        if(file_exists($file)) {

            $tab=array("idAdherent"=>$idAdherent,"idCadenas"=>$idMacAddress,"jour"=>$jour,"heure"=>$heure);
            $json = json_encode($tab);
            file_put_contents($file,$json);
            echo($json);
        }


    }


    if($_GET['type'] == "read")
    {
        $idCadenas = $_GET['idCadenas'];
        $file = 'ouvrir_distance.json';
        $json = file_get_contents($file);

        $jour1 = date('Y-m-d');
        $heure1 = date('G:i:s');

        $data = json_decode($json);
        $id = $data->idAdherent;
        $idC = $data->idCadenas;
        $jour2 = $data->jour;
        $heure2 = $data->heure;

        $diffJour=date_diff(date_create($jour2),date_create($jour1));
        $diffHeure=date_diff(date_create($heure2),date_create($heure1));

        $valide = 0;
        if($idC == $idCadenas)
        {
            if($diffJour->format('%d') == 0 & $diffJour->format('%y') == 0 & $diffJour->format('%m') == 0)
            {
                if($diffHeure->format('%i') == 0 || $diffHeure->format('%i') == 1 || $diffHeure->format('%i') == 59)
                {
                    if($diffHeure->format('%s') < 15 || $diffHeure->format('%s') > 45)
                    {
                        $valide = 1;
                    }
                }
            }
        }

        if($valide == 1)
        {
            $tab = array("succes" => true, "idCadenas" => $idCadenas);

            $query4 = "INSERT INTO `fablab`.`logs` (`cadenas_idCadenas`, `adherent_idAdherent`, `Horaire`, `Date`) VALUES (?,?,?,?)";
            if ($stmt4 = $bdd->prepare($query4)) {
                $stmt4->bind_param('siss', $idCadenas, $id, strval($heure1), strval($jour1));
                $stmt4->execute();
                $stmt4->close();
            }

        }
        else
        {
            $tab = array("succes" => false, "idCadenas" => $idCadenas);
        }
        echo(json_encode($tab));

    }
}

$stmt->close();
