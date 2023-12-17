<?php
session_start();
?>
<html lang="fr">
<head>
    <title>Note de frais</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>

<?php
$message = '';
$login = '';
if( isset( $_GET['error'] ) ) {
    if( isset( $_GET['loginerror'] ) ) {
        $message = 'Erreur : Votre login est non valide !';
    }
    if( isset( $_GET['passerror'] ) ) {
        $message = 'Erreur : Votre mot de passse est non valide !';
    }
}
?>
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

    <div class="row justify-content-center">
        <div class="col-9 mt-3">
            <h5>Se connecter</h5>
        </div>
    </div>

    <section class="container mt-5">

        <?php
            if( isset( $_GET['error'] ) ) {
        ?>
            <div class="row"><p class="col-10 alert alert-danger"><?=$message?></p></div>
        <?php
            }
        ?>
        <div class="row">
            <div class="col-12">
                <form name="accesform" method="post" action="validlogin.php">
                    <div class="mb-3 row">
                        <label for="login" class="col-sm-2 col-form-label">Login</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="login" value="<?=$login?>" placeholder="Votre login" name="login" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Mot de passe</label>
                        <div class="col-sm-4">
                            <input type="password" class="form-control" id="inputPassword" name="password" required>
                        </div>
                    </div>

                    <div class="mb-3 row justify-content-end">
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary mb-3">OK</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>
</body>