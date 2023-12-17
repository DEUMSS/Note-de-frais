<?php
    session_start();
    include_once('db.php')
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
        if(isset($_SESSION['login'])){
            $req = $db->prepare("SELECT LF_id, LF_date, LF_trajet, LF_nb_km, LF_cout_peage, LF_cout_repas, LF_cout_heberg, LF_cout_trajet
            FROM ligne_frais WHERE LF_id_demandeur = :idDemandeur");
            $req->execute([':idDemandeur'=>$_SESSION['idUser']]);
            $listFrais = $req->fetchAll(PDO::FETCH_ASSOC);
             
            if(isset($_GET['deleteOkay'])){
                $validMessage = "Supression réussis.";
            }
    ?>
    
    <section >
        <div class="mt-5">
            <h1 class="row justify-content-center">Lignes de frais enregistrées</h1>
        </div>
        <div class="row justify-content-center">
            <div class="col-10 mt-4">
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($listFrais as $ligneFrais){
                                $dateObj = new DateTime($ligneFrais['LF_date']);
                                $date = $dateObj->format("d/m/Y");
                        ?>
                            <tr>
                                <td><?=$date?></td>
                                <td><?=$ligneFrais['LF_trajet']?></td>
                                <td><?=$ligneFrais['LF_nb_km']?></td>
                                <td><?=$ligneFrais['LF_cout_peage']?></td>
                                <td><?=$ligneFrais['LF_cout_repas']?></td>
                                <td><?=$ligneFrais['LF_cout_heberg']?></td>
                                <td><?=$ligneFrais['LF_cout_trajet']?></td>
                                <form name="deleteform" method="post" action="deleteLF.php">
                                    <input type="hidden" class="form-control" id="idLF" value="<?=$ligneFrais['LF_id']?>" name="idLF">
                                    <td><button type="submit" class="btn btn-danger">Supprimer</button>
                                </form>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-3 mt-5 text-center">
                    <form name="accesform" method="post" action="ligneDeFrais.php">
                        <button type="submit" class="btn btn-info btn-lg">Ajouter une ligne de frais</button>
                    </form>
                </div>
                <div class="col-3 mt-5 text-center">
                    <form name="accesform" method="post" action="noteDeFrais.php">
                        <button type="submit" class="btn btn-info btn-lg">Visualiser la note de frais</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php
         }
    ?>
</body>
</html>