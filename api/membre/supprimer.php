<?php
include'../connexionbdd.php';

$query = "DELETE from fablab.adherent WHERE idAdherent =?;";
if(isset($_POST["id"])){
    $id=$_POST["id"];
}



if ($stmt = $bdd->prepare($query)) {
    $stmt->bind_param('i',$id);

    if ($stmt->execute()){
        $query2="UPDATE carte SET carte_idAdherent = -1 WHERE carte_idAdherent= ?";

        if ($stmt2 = $bdd->prepare($query2)) {
            $stmt2->bind_param('i',$id);
            if ($stmt2->execute()){
                $query3="DELETE FROM fablab.logs WHERE adherent_idAdherent=?";

                if ($stmt3 = $bdd->prepare($query3)) {
                    $stmt3->bind_param('i',$id);
                    if ($stmt3->execute()){
                        $rep = array("success" => true);
                    }
                }
            }
        }
    }

    else
        $rep = array("success" => false);

    echo(json_encode($rep));
}
$stmt->close();

$bdd->close();