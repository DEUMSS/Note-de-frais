<?php
    session_start();
    include_once('db.php')
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.min.css"/>
    <link rel="stylesheet" href="style.css"/>
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
    $req = $db->prepare("SELECT DM_id_ligue, DM_id_adherent, LG_nom FROM demandeur 
    INNER JOIN ligue ON (DM_id_ligue = LG_id)
    WHERE DM_id = :id");
    $req->execute([':id'=>$_SESSION['idUser']]);
    $resultatDM = $req->fetch(PDO::FETCH_ASSOC);

    $req = $db->prepare("SELECT AD_nom, AD_prenom, AD_rue, AD_code_pstl, AD_ville FROM adherent WHERE AD_id = :id");
    $req->execute([':id'=>$resultatDM['DM_id_adherent']]);
    $resultatAD = $req->fetch(PDO::FETCH_ASSOC);

    $req = $db->prepare("SELECT LF_id, LF_date, LF_trajet, LF_nb_km, LF_cout_peage, LF_cout_repas, LF_cout_heberg, LF_cout_trajet
    FROM ligne_frais WHERE LF_id_demandeur = :idDemandeur");
    $req->execute([':idDemandeur'=>$_SESSION['idUser']]);
    $listFrais = $req->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($ligneFrais)){
        foreach($listFrais as $ligneFrais){
            $timestamp = strtotime($ligneFrais['LF_date']);
            $annee = date("Y", $timestamp);

        }
    }else{
        $annee= date('Y');
    }

    $multiTotal=0;

    ?>
    <section class="container-fluid mt-3">
        <div class="row mt-3">
            <div class="col-6">
                <h1>Note de frais des bénévoles</h1>
            </div>
            <div class="col-6">
                <h1 class="text-end">Année civile <?=$annee?></h1>
            </div>
        </div>
        <div class="row mt-4">
            <p>Je soussigné(e), </p>
        </div>
        <div class="col-5">
            <div class="row mt-1">
                <p class="bg-success-subtle"><strong><?=$resultatAD['AD_prenom']?> <?=$resultatAD['AD_nom']?></strong></p>
            </div>
        </div>
        <div class="row mt-2">
            <p>demeurant</p>
        </div>
        <div class="col-5">
            <div class="row mt-1">
                <p class="bg-success-subtle"><strong><?=$resultatAD['AD_rue']?>, <?=$resultatAD['AD_code_pstl']?>, <?=$resultatAD['AD_ville']?></strong></p>
            </div>
        </div>
        <div class="row mt-2">
            <p>certifie renoncer au remboursement des frais ci-dessous et les laisser à l'association</p>
        </div>
        <div class="col-5">
            <div class="row mt-1">
                <p class="bg-success-subtle"><strong><?=$resultatDM['LG_nom']?></strong></p>
            </div>
        </div>
        <div>
            <p>en tant que don.</p>
        </div>
        <div class="row mt-5 justify-content-center">
            <div class="col-3">
                <p>Frais de déplacement</p>
            </div>
            <div class="col-4">
                <p><em>Tarif kilométrique appliqué pour le remboursement : 0,28 €</em></p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-10 mt-2">
                <?php
                if(!empty($deleteOkay)){
                    echo'<p class="col-9 ml-4 col alert alert-success">' .$validMessage.'</p>';
                }   
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Trajet</th>
                            <th>Nombre de kilomètres</th>
                            <th>Coût péages</th>
                            <th>Coût repas</th>
                            <th>Coût hébergement</th>
                            <th>Coût trajet</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($listFrais as $ligneFrais){
                                $timestamp = strtotime($ligneFrais['LF_date']);
                                $date = date("d/m/Y", $timestamp);
                                $total = $ligneFrais['LF_cout_peage'] + $ligneFrais['LF_cout_repas'] + $ligneFrais['LF_cout_heberg'] + 
                                $ligneFrais['LF_cout_trajet'];
                                $multiTotal += $total;
                        ?>
                            <tr>
                                <td><?=$date?></td>
                                <td><?=$ligneFrais['LF_trajet']?></td>
                                <td><?=$ligneFrais['LF_nb_km']?> Km</td>
                                <td><?=$ligneFrais['LF_cout_peage']?> €</td>
                                <td><?=$ligneFrais['LF_cout_repas']?> €</td>
                                <td><?=$ligneFrais['LF_cout_heberg']?> €</td>
                                <td><?=$ligneFrais['LF_cout_trajet']?> €</td>
                                <td><?=$total?> €</td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-center">Montant total des frais de déplacement</td>
                            <td><?=$multiTotal?> €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-2">
                <p>Montant total des dons :</p>
            </div>
            <div class="col-2">
                <p class="bg-success-subtle"><strong><?=$multiTotal?> €</strong></p>
            </div>
        </div>
        <div class="row mt-3 justify-content-center">
            <div class="col-7">
                <p><em>Pour bénéficier du reçu de dons, cette note de frais doit être accompagnée de toutes les justificatifs correspondants</em></p>
            </div>
        </div>
        <div class="row mt-3 justify-content-center">
            <div class="col-2">
                <p>A </p>
            </div>
            <div class="col-2">
                <p>le</p>
            </div>
        </div>
        <div class="row mt-3 justify-content-center">
            <div class="col-4">
                <p>Signature du bénévole</p>
            </div>
        </div>
    </section>
    <section class="container-fluid mt-5 bg-info-subtle col-6">
        <div class="row mt-2 justify-content-center">
            <div class="col-8">
                <p class="text-center">Partie réservée à l'association</p>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-4">
                <p>N° d'ordre de reçu : </p>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-4">
                <p>Remis le : </p>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-4">
                <p>Signature du trésorier : </p>
            </div>
        </div>
    </section>
    <div class="row justify-content-end mt-5">
        <div class="col-3">
            <button class="btn btn-primary hideInPrint" onclick="window.print()">Imprimer</button>
        </div>
    </div>
</body>
