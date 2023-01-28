<?php
include '../connexionbdd.php';

use PHPMailer\PHPMailer\PHPMailer;


require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function random_str(
    $length,
    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
)
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
$ida=0;

$Nom = $_POST["Nom"];

$Prenom = $_POST["Prenom"];

$Mail = $_POST["Mail"];

$Password = random_str(16);

$Grade = $_POST["Grade"];

$Age = $_POST["Age"];

$Type = $_POST["Type"];

$Tel = "0123456793";

$Photo = "http://51.210.151.13/btssnir/projets2022/fablab/site/assets/img/avatars/photo_profil.jpg";

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.orange.fr';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
//$mail->Username = 'fablabjr127@gmail.com';                 // SMTP username
//$mail->Password = 'fablab92';                           // SMTP password
$mail->Username = 'jean.rostand2@orange.fr';                 // SMTP username
$mail->Password = 'Rostand92';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to

$mail->From = 'jean.rostand2@orange.fr';
$mail->FromName = 'FabLabPass';
$mail->addAddress($Mail, 'User');     // Add a recipient

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Inscription FabLab';
$mail->Body    = '<b>Votre inscription au FabLab a bien 	&eacute;t&eacute; effectu&eacute;</b><p>Vos identifiants:<b>' . $Mail . '</b><br>Votre mot de passe: <b>' . $Password . '</b><br>Vous pouvez vous connecter au site avec vos identifiants en cliquant sur ce lien: http://51.210.151.13/btssnir/projets2022/fablab/site/Global/login.html</p>';
$mail->AltBody = 'Votre inscription au FabLab a bien été effectué Vos identifiants: ' . $Mail . ' Votre mot de passe: ' . $Password . 'Vous pouvez vous connecter au site avec vos identifiants en cliquant sur ce lien: http://51.210.151.13/btssnir/projets2022/fablab/site%203/Global/login.html';

if(!$mail->send()) {
    echo json_encode($mail->ErrorInfo);
} else {
    $query = "INSERT INTO `fablab`.`adherent` (`Nom`, `Prenom`, `Mail`, `Password`, `Grade`, `Age`, `Type`, `Tel`, `Photo`) VALUES (?,?,?,?,?,?,?,?,?);";
    if ($stmt = $bdd->prepare($query)) {
        $stmt->bind_param("ssssissss", $Nom, $Prenom, $Mail, $Password, $Grade, $Age, $Type, $Tel, $Photo);
        if ($stmt->execute()) {
            $query2 = "SELECT idAdherent FROM fablab.adherent where Mail=?";

            if ($stmt2 = $bdd->prepare($query2)) {
                $stmt2->bind_param('s', $Mail);

                if ($stmt2->execute()) {

                    $stmt2->bind_result($idadherent);
                    if ($stmt2->fetch()) {
                        $ida = $idadherent;
                    }
                    $stmt->close();
                    $stmt2->close();
                }
            }
        }
        else
            $rep = array("success" => false);

        $stmt->close();
    }

    if($ida !=0)
    {
        $rep = array("success" => true);
        $query3 = "UPDATE fablab.carte SET carte_idAdherent = ? WHERE iduid= ?";

        if ($stmt3 = $bdd->prepare($query3)) {
            $stmt3->bind_param('is', $ida, $_POST['iduid']);
            $stmt3->execute();
            $stmt3->close();
        }
    }
    echo("mail has been sent");
    echo(json_encode($rep));
}