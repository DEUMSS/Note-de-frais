<?php
    session_start();
    require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="fr">
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
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <?php
        if(isset($_SESSION['numLicence'])){
            $LicenceNum = str_replace(' ', '', $_POST['numLicence']);
            if(isset($_POST['nom']) || isset($LicenceNum )){
                if(strlen($LicenceNum )< 12){
                    header('Location: registerStep1.php?invalidLicence=1');
                    exit;
                }elseif(!is_numeric($LicenceNum )){
                    header('Location: registerStep1.php?invalidLicence=1');
                    exit;
                }
            }

            if(isset($LicenceNum )){
                $req = 'SELECT AD_id FROM adherent where AD_num_licence = :numLicence AND AD_nom = :nom';
                $reponse = $db->prepare($req);
                $reponse->execute([':numLicence'=>$LicenceNum , ':nom'=>$_POST['nom']]);
                $resultat = $reponse->fetch(pdo::FETCH_ASSOC);
                if(count($resultat) == 0){
                    header('Location: registerStep1.php?invalidLicenceNum=1');
                    die;
                }
                $_SESSION['idAdherent'] = $resultat['AD_id'];
                $_SESSION['nom'] = $_POST['nom'];
                $_SESSION['numLicence'] = $LicenceNum ;
            }
        }
        
        $errorMessage  = '';
        if( isset( $_GET['emptyCar'] ) ) {
            $errorMessage = 'Votre mot de passe doit contenir un ou plusieurs caractères spéciaux.';
        }
        if( isset( $_GET['emptyNb'] ) ) {
            $errorMessage = 'Votre mot de passe doit contenir un ou plusieurs chiffres.';
        }
        if( isset( $_GET['shortPass'] ) ) {
            $errorMessage = 'Votre mot de passe doit contenir 12 caractères minimum.';
        }
        if( isset( $_GET['passDif'] ) ) {
            $errorMessage = 'Les deux mot de passe ne sont pas identiques.';
        }
        if(isset($_GET['emptyMaj'])){
            $errorMessage = 'Votre mot de passe doit contenir au moins une majuscule.';
        }
        if(isset($_GET['loginUsed'])){
            $errorMessage = 'Ce login est déjà utilisé, merci d\'en choisir un autre.';
        }

        $req = 'SELECT LG_num, LG_nom FROM ligue';
        $reponse = $db->query($req);
        $listeLigue = $reponse->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <section class="container mt-5">
        <div class="row">
            <div class="col-12">
                <form name="accesform" method="post" action="registerStep3.php">
                <?php
                    if(!empty($errorMessage)){
                        echo'<p class="col-9 ml-4 col alert alert-danger">' .$errorMessage.'</p>';
                    }
                ?>
                    <div class="mb-3 row">
                        <label for="login" class="col-sm-4 col-form-label">Pseudo</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="login" value="" placeholder="Votre pseudo" name="login" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="passwordOne" class="col-sm-4 col-form-label">Mot de passe</label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" id="passwordOne" name="passwordOne" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="passwordTwo" class="col-sm-4 col-form-label">Confirmer le mot de passe</label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" id="passwordTwo" name="passwordTwo" required>
                        </div>
                    </div>
                    <label for="ligue">Sélectionnez une ligue :</label>
                    <select name="numLigue" id="numLigue" required>
                        <?php
                            foreach ($listeLigue as $key => $ligue) {
                        ?>
                            <option value=<?=$ligue['LG_num']?>><?=$ligue['LG_nom']?></option>;
                        <?php
                            }
                        ?>
                    </select>
                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary mb-3">Valider</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>

</body>
</html>