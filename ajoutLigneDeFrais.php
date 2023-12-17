<?php
    session_start();
    include "db.php";
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
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <?php
        $date = $_POST['date'];
        $idMotif = $_POST['motif'];
        $trajet = $_POST['trajet'];
        $nbKm = str_replace(',', '.', $_POST['nbKm']);
        $coutTrajet = str_replace(',', '.',$_POST['coutTrajet']);
        $coutPeages = str_replace(',', '.',$_POST['coutPeages']);
        $coutRepas = str_replace(',', '.',$_POST['coutRepas']);
        $coutHeberg = str_replace(',', '.',$_POST['coutHeberg']);

        if(!preg_match('/^[^A-Za-z]+$/', $nbKm)){
            header('Location: ligneDeFrais.php?kmChaine=1');
        }
        if(!preg_match('/^[^A-Za-z]+$/', $coutTrajet)){
            header('Location: ligneDeFrais.php?trajetChaine=1');
        }
        if(!preg_match('/^[^A-Za-z]+$/', $coutPeages)){
            header('Location: ligneDeFrais.php?peageChaine=1');
        }
        if(!preg_match('/^[^A-Za-z]+$/', $coutRepas)){
            header('Location: ligneDeFrais.php?repasChaine=1');
        }
        if(!preg_match('/^[^A-Za-z]+$/', $coutHeberg)){
            header('Location: ligneDeFrais.php?hebergChaine=1');
        }

        $req = $db->prepare("SELECT BD_id FROM bordereau WHERE BD_id_demandeur = :idDemandeur");
        $req->execute([':idDemandeur'=>$_SESSION['idUser']]);
        $resultat = $req->fetch(PDO::FETCH_ASSOC);
        if(!empty($resultat)){
            $idBordereau = $resultat['BD_id'];
        }else{
            $dateDuJour="";
            $dateDuJour = date('Y-m-d');
            $req = $db->prepare("INSERT INTO bordereau (BD_id_demandeur, BD_date_deb) VALUES (:idDemandeur, :dateDeb)");
            $isInsertOk = $req->execute([
                ':idDemandeur'=>$_SESSION['idUser'],
                ':dateDeb'=>$dateDuJour
            ]);
            if( !$isInsertOk ) {
                echo "Erreur lors de l'enregistrement";
                die;
             } else {
                 $idBordereau = $db->lastInsertId();
                 $_SESSION['idBordereau'] = $idBordereau;
             }    
        }
        

        $req = $db->prepare("INSERT INTO ligne_frais (LF_id_motif, LF_id_demandeur, LF_date, LF_trajet, LF_nb_km, LF_cout_peage, LF_cout_repas, LF_cout_heberg, LF_cout_trajet) 
        VALUES (:idMotif, :idDemandeur, :date, :trajet, :nbKm, :coutPeage, :coutRepas, :coutHeberg, :coutTrajet)");
        $isInsertOk = $req->execute([
            ':idMotif'=>$idMotif,
            ':idDemandeur'=>$_SESSION['idUser'],
            ':date'=>$date,
            ':trajet'=>$trajet,
            ':nbKm'=>$nbKm,
            ':coutPeage'=>$coutPeages,
            ':coutRepas'=>$coutRepas,
            ':coutHeberg'=>$coutHeberg,
            ':coutTrajet'=>$coutTrajet
        ]);
        if( !$isInsertOk ) {
            echo "Erreur lors de l'enregistrement de la ligne de frais";
            die;
         } else {
            $idLigneFrais = $db->lastInsertId();
            $_SESSION['idLigneFrais'] = $idLigneFrais;
         }
         $req = $db->prepare("INSERT INTO bordereau_ligne_frais (BD_id, LF_id) VALUES (:idBordereau, :idLigneFrais)");
         $isInsertOk = $req->execute([
            ':idBordereau'=>$_SESSION['idBordereau'],
            ':idLigneFrais'=>$_SESSION['idLigneFrais']
         ]);
         if(!$isInsertOk){
            echo "Erreur lors de l'enregistrement";
            die;
         }else{
            header('Location: ligneDeFrais.php?insertValid=1');
         }
    ?>
</body>