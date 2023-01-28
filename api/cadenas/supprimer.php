<?php
include'../connexionbdd.php';

if(isset($_POST["id"])){
    $id=$_POST["id"];
}

$query = "DELETE fablab.logs FROM fablab.logs inner join fablab.cadenas on cadenas_idCadenas=idMacAddress where idCadenas = ?;";


if ($stmt = $bdd->prepare($query)) {
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        
        $query2 = "DELETE from fablab.cadenas WHERE idCadenas =? ";
        if ($stmt2 = $bdd->prepare($query2)) {
            $stmt2->bind_param('i', $id);
            if ($stmt2->execute()) {
                $rep = array("success" => true);
            } else
                $rep = array("success" => false);
        } else
            $rep = array("success" => false);
        echo(json_encode($rep));
    }
}
$stmt->close();
$bdd->close();