<?php
    session_start();
    include_once('db.php');
?>
<!DOCTYPE html>
<html lang="frs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="style.css" />
    <title>Note de frais</title>
</head>
<body>
    <header>
        <div class="row justify-content-center">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Note de frais</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Accueil</a>
                            </li>
                            <?php
                                if(isset($_SESSION['login'])){
                            ?>
                                <li class="nav-item">
                                        <a class="nav-link" href="logout.php">Déconnexion</a>
                                </li>
                            <?php
                                }

                                if(empty($_SESSION['login'])){
                            ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="login.php">Connexion</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="registerStep1.php">Inscription</a>
                                </li>
                            <?php
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <?php
        $numLigue = $_POST['numLigue'];
        $idAdherent = $_SESSION['idAdherent'];
        $licenceNum = $_SESSION['numLicence'];
        $nom = $_SESSION['nom'];
        $login = $_POST['login'];
        $passwordOne = $_POST['passwordOne'];
        $passwordTwo = $_POST['passwordTwo'];

        $req = $db->prepare('SELECT * FROM demandeur WHERE DM_login = :login');
        $req->execute([':login'=>$login]);
        if($req->rowCount()){
            header('Location: registerStep2.php?loginUsed=1');
            die;
        }

        if(isset($login) && isset($passwordOne) && isset($passwordTwo)){
            if(strlen($passwordOne)< 12){
                header('Location: registerStep2.php?shortPass=1');
                exit;
            }elseif(!preg_match('/[A-Z]/', $passwordOne)){
                header('Location: registerStep2.php?emptyMaj=1');
                exit;
            }elseif(!preg_match('/\d/', $passwordOne)){
                header('Location: registerStep2.php?emptyNb=1');
                exit;
            }elseif(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $passwordOne)){
                header('Location: registerStep2.php?emptyCar=1');
                exit;
            }elseif($passwordOne != $passwordTwo){
                header('Location: registerStep2.php?passDif=1');
                exit;
            }
        }

        $passHash = sodium_crypto_pwhash_str(
            $passwordOne,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
        );

        $req = $db->prepare('SELECT LG_id FROM ligue WHERE LG_num = :numLigue');
        $req->execute([':numLigue'=>$_POST['numLigue']]);
        $resultat = $req->fetch(PDO::FETCH_ASSOC);
        $idLigue = $resultat['LG_id'];

        $req = $db->prepare( 
            "INSERT INTO demandeur (DM_id_adherent, DM_login, DM_mdp, DM_num_ligue, DM_num_licence,DM_id_ligue)
            VALUES (:id_adherent, :login,:password, :numLigue, :numLicence, :idLigue)"
         );
        $isInsertOk = $req->execute([
            ':id_adherent' => $idAdherent,
            ':login'   => $login,
            ':password' => $passHash,
            ':numLigue' => $numLigue,
            ':numLicence' => $licenceNum,
            ':idLigue' => $idLigue
         ]);
         if( $isInsertOk == false ) {
            echo "Erreur lors de l'enregistrement";
            die;
         } else {
             $idUser = $db->lastInsertId();
             $_SESSION['idUser'] = $idUser;
             $_SESSION['login'] = $login;
             header('Location: index.php?error=0');
         }
        ?>
</body>
</html>