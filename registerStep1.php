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
        $errorMessage  = '';
        if( isset( $_GET['invalidLicence'] ) ) {
            $errorMessage = 'Le numéro de licence renseigné est incorrect, veuillez réessayer';
        }
        if(isset($_GET['invalidLicenceNum'])){
            $errorMessage = 'Nous ne trouvons pas de licence associé à ce nom dans notre base de données. Merci de vous référer à votre club.';
        }
    ?>

    <section class="container mt-5">
        <div class="row">
            <?php
            if(!empty($errorMessage)){
                echo'<p class="col-9 ml-4 col alert alert-danger">' .$errorMessage.'</p>';
            }
            ?>
        </div>
        <div class="row">
            <div class="col-12">
                <form name="accesform" method="post" action="registerStep2.php">

                    <div class="mb-3 row">
                        <label for="nom" class="col-sm-4 col-form-label">Nom :</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="nom" value="" placeholder="Votre nom" name="nom" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="numLicence" class="col-sm-4 col-form-label">Numéro de licence :</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="numLicence" name="numLicence" required>
                        </div>
                    </div>
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