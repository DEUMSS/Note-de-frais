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
    <link rel="stylesheet" href="styles.css" />
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
        $req = 'SELECT MT_id, MT_libelle FROM motif';
        $reponse = $db->query($req);
        $listeMotif = $reponse->fetchAll(PDO::FETCH_ASSOC);
                
        $errorMessage  = '';
        if( isset( $_GET['kmChaine'] ) ) {
            $errorMessage = 'Vous ne devez saisir que des chiffres dans la zone dédié au nombre de km.';
        }
        if( isset( $_GET['trajetChaine'] ) ) {
            $errorMessage = 'Vous ne devez saisir que des chiffres dans la zone dédié au coût du trajet.';
        }
        if( isset( $_GET['peageChaine'] ) ) {
            $errorMessage = 'Vous ne devez saisir que des chiffres dans la zone dédié au coût des péages.';
        }
        if( isset( $_GET['repasChaine'] ) ) {
            $errorMessage = 'Vous ne devez saisir que des chiffres dans la zone dédié au coût des repas.';
        }
        if( isset( $_GET['hebergChaine'] ) ) {
            $errorMessage = 'Vous ne devez saisir que des chiffres dans la zone dédié au coût de l\'hébergement.';
        }
        if(isset($_GET['insertValid'])){
            $validMessage = 'Votre ligne de frais à bien était enregister.';
        }
    ?>
    <h1>Ajouter une ligne de frais</h1>
    <section class="container mt-5">
        <div class="row">
            <div class="col-12">
            <?php
                if(!empty($errorMessage)){
                    echo'<p class="col-9 ml-4 col alert alert-danger">' .$errorMessage.'</p>';
                }
                if(!empty($validMessage)){
                    echo'<p class="col-9 ml-4 col alert alert-success">' .$validMessage.'</p>';
                }
            ?>

                <form name="fillingform" method="post" action="ajoutLigneDeFrais.php">

                    <div class="mt-3 row">
                        <label for="date" class="col-sm-4 col-form-label">Date de l'évènement :</label>
                        <div class="col-sm-4">
                            <input type="date" id="date" name="date">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="ligue" class="col-sm-4 col-form-label">Sélectionnez un motif :</label>
                        <select class="col-sm-3"name="motif" id="motif" required>
                            <?php
                                foreach ($listeMotif as $key => $motif) {
                            ?>
                                <option value=<?=$motif['MT_id']?>><?=$motif['MT_libelle']?></option>;
                            <?php
                                }
                            ?>
                        </select>
                    </div>

                    <div class="mt-3 row">
                        <label for="trajet" class="col-sm-4 col-form-label">Trajet :</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="trajet" name="trajet" required placeholder="<départ>-<arrivée>">
                        </div>
                    </div>

                    <div class="mt-3 row">
                        <label for="nbKm" class="col-sm-4 col-form-label">Nombre de kilomètres :</label>
                        <div class="col-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="nbKm" name="nbKm" required placeholder="EX: 30, 5, 230, ...">
                                <span class="input-group-text" id="basic-addon1">Km</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 row">
                        <label for="coutTrajet" class="col-sm-4 col-form-label">Coût du trajet :</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="coutTrajet" name="coutTrajet" required placeholder="EX: 30, 5, 230, ...">
                                <span class="input-group-text" id="basic-addon1">€</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 row">
                        <label for="coutPeages" class="col-sm-4 col-form-label">Coût des péages :</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="coutPeages" name="coutPeages" required placeholder="EX: 30, 5, 230, ...">
                                <span class="input-group-text" id="basic-addon1">€</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 row">
                        <label for="coutReaps" class="col-sm-4 col-form-label">Coût des repas :</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="coutRepas" name="coutRepas" required placeholder="EX: 30, 5, 230, ...">
                                <span class="input-group-text" id="basic-addon1">€</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 row">
                        <label for="coutHeberg" class="col-sm-4 col-form-label">Coût de l'hébergement :</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="coutHeberg" name="coutHeberg" required placeholder="EX: 30, 5, 230, ...">
                                <span class="input-group-text" id="basic-addon1">€</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 row justify-content-end">
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary mb-3">Ajouter la ligne de frais</button>
                        </div>
                    </div>
                        
                </form>
            </div>
        </div>
    </section>
</body>
</html>