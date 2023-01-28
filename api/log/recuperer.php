<?php
include'../connexionbdd.php';

function dateFR($datetime) {
    //setlocale(LC_ALL, 'fr_FR');
    return strftime('%A/%e/%m/%Y', strtotime($datetime));
}




//-- DELETE FROM fablab.logs WHERE adherent_idAdherent=2 AND cadenas_idCadenas=3000;
//-- INSERT INTO fablab.logs (cadenas_idCadenas, adherent_idAdherent, Horaire, Date) VALUES (2000,5,"17:50:00","2022-05-06");
//$query = "SELECT cadenas_idCadenas, adherent_idAdherent, Date FROM logs";
//$query = "select * FROM fablab.logs WHERE adherent_idAdherent = 93 and Date = \"2022-04-22\"";

if(isset($_GET["brut"])){
    $query ="SELECT  Nom,Prenom,Date,DATE_FORMAT(Horaire,'%H:%i')as Horaire,NomCadenas  FROM fablab.adherent inner join logs on adherent_idAdherent=idAdherent inner join cadenas on idMacAddress=cadenas_idCadenas order by Date desc,Horaire desc";
    if ($stmt = $bdd->prepare($query)) {
        $json=array();

        $stmt->execute();
        $stmt->bind_result($Nom,$Prenom,$Date,$Horaire,$NomCadenas);

        while ($stmt->fetch()) {
            $Date=dateFR($Date);
            $temp=explode("/",$Date);
            $jour=$temp[0];
            $Date=$temp[1]."/".$temp[2]."/".$temp[3];
            $tab=array("Nom"=>$Nom,"Prenom"=>$Prenom,"NomCadenas"=>$NomCadenas,"Date"=>$Date,"Horaire"=>$Horaire,"Jour"=>$temp[0]);
            array_push($json,$tab);

        }
        echo(json_encode($json));
        $stmt->close();
}
}

if(isset($_GET["brute"])){
    $query ="SELECT  Nom,Prenom,Date,DATE_FORMAT(Horaire,'%H:%i')as Horaire,NomCadenas  FROM fablab.adherent inner join logs on adherent_idAdherent=idAdherent inner join cadenas on idMacAddress=cadenas_idCadenas order by Date desc,Horaire desc";
    if ($stmt = $bdd->prepare($query)) {
        $json=array();

        $stmt->execute();
        $stmt->bind_result($Nom,$Prenom,$Date,$Horaire,$NomCadenas);

        while ($stmt->fetch()) {
            $Date=dateFR($Date);
            $temp=explode("/",$Date);
            $jour=$temp[0];
            $Date=$temp[1]."/".$temp[2]."/".$temp[3];
            $tab=array("Nom"=>$Nom,"Prenom"=>$Prenom,"NomCadenas"=>$NomCadenas,"Date"=>$Date,"Horaire"=>$Horaire,"Jour"=>$temp[0]);
            array_push($json,$tab);

        }
        $data["logs"] = $json;
        echo(json_encode($data));
        $stmt->close();
    }
}



    if(isset($_GET["cadenas"])){
        $mois=date("m");
        $query ="Select count(cadenas_idCadenas) as badger,NomCadenas FROM fablab.logs inner join cadenas on cadenas_idCadenas=idMacAddress where month(date) = {$mois} group by cadenas_idCadenas,NomCadenas ORDER BY badger desc;";
        if ($stmt = $bdd->prepare($query)) {
            $json=array();
            $stmt->execute();
            $stmt->bind_result($badger,$NomCadenas);

            while ($stmt->fetch()) {
                $tab=array("badger"=>$badger,"NomCadenas"=>$NomCadenas);
                array_push($json,$tab);

            }
            echo(json_encode($json));
            $stmt->close();
    }
}

    if(isset($_GET["mois"])){
        $query ="Select month(Date),count(month(Date))as passage FROM fablab.logs group by month(Date)";
        if ($stmt = $bdd->prepare($query)) {
            $json=array();
            $stmt->execute();
            $stmt->bind_result($mois,$passage);
           // $tab=array("Mois"=>["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"],"Passage"=>[0,0,0,0,0,0,0,0,0,0,0,0,0]);
            $tab=array("Janvier"=>0,"Février"=>0,"Mars"=>0,"Avril"=>0,"Mai"=>0,"Juin"=>0,"Juillet"=>0,"Août"=>0,"Septembre"=>0,"Octobre"=>0,"Novembre"=>0,"Décembre"=>0);

            while ($stmt->fetch()) {
                switch ($mois){
                    case 1:$tab["Janvier"]=$passage;break;
                    case 2:$tab["Février"]=$passage;break;
                    case 3:$tab["Mars"]=$passage;break;
                    case 4:$tab["Avril"]=$passage;break;
                    case 5:$tab["Mai"]=$passage;break;
                    case 6:$tab["Juin"]=$passage;break;
                    case 7:$tab["Juillet"]=$passage;break;
                    case 8:$tab["Août"]=$passage;break;
                    case 9:$tab["Septembre"]=$passage;break;
                    case 10:$tab["Octobre"]=$passage;break;
                    case 11:$tab["Novembre"]=$passage;break;
                    case 12:$tab["Décembre"]=$passage;break;
                }

            }
            array_push($json,$tab);
            echo(json_encode($tab));
            $stmt->close();
        }
    }

    if(isset($_GET["jour"])){
        $query ="Select Date FROM fablab.logs ";
        if ($stmt = $bdd->prepare($query)) {
            $json=array();
            $stmt->execute();
            $stmt->bind_result($Jour);
            $tab=array("Lundi"=>0,"Mardi"=>0,"Mercredi"=>0,"Jeudi"=>0,"Vendredi"=>0);


            while ($stmt->fetch()) {
                $Jour=strftime('%A', strtotime($Jour));
                switch ($Jour){
                    case "Monday":$tab["Lundi"]+=1;break;
                    case "Tuesday":$tab["Mardi"]+=1;break;
                    case "Wednesday":$tab["Mercredi"]+=1;break;
                    case "Thursday":$tab["Jeudi"]+=1;break;
                    case "Friday":$tab["Vendredi"]+=1;break;

                }

            }
            echo(json_encode($tab));
            $stmt->close();
        }
    }
if(isset($_GET["heures"])){
    $query ="Select hour(Horaire)  FROM fablab.logs ";
    if ($stmt = $bdd->prepare($query)) {
        $json=array();
        $stmt->execute();
        $stmt->bind_result($Heure);
        $tab=array("8h"=>0,"9h"=>0,"10h"=>0,"11h"=>0,"12h"=>0,"13h"=>0,"14h"=>0,"15h"=>0,"16h"=>0,"17h"=>0,"18h"=>0);


        while ($stmt->fetch()) {
            switch ($Heure){
                case 8:$tab["8h"]+=1;break;
                case 9:$tab["9h"]+=1;break;
                case 10:$tab["10h"]+=1;break;
                case 11:$tab["11h"]+=1;break;
                case 12:$tab["12h"]+=1;break;
                case 13:$tab["13h"]+=1;break;
                case 14:$tab["14h"]+=1;break;
                case 15:$tab["15h"]+=1;break;
                case 16:$tab["16h"]+=1;break;
                case 17:$tab["17h"]+=1;break;
                case 18:$tab["18h"]+=1;break;

            }

        }
        echo(json_encode($tab));
        $stmt->close();
    }
}


