<?php
session_start();
include_once('db.php');

if( isset( $_POST['login'] ) && isset( $_POST['password'] ) ) {
    $login = $_POST['login'];
    $sql = 'SELECT * FROM demandeur WHERE DM_login=:login';
    $reponse = $db->prepare( $sql );
    $reponse->execute( [':login'=>$login] );

    if( $acces = $reponse->fetch(PDO::FETCH_ASSOC) ) {
        if(sodium_crypto_pwhash_str_verify( $acces['DM_mdp'], $_POST['password'] ) ) {
            $_SESSION['login'] = $acces['DM_login'];
            $_SESSION['idUser'] = $acces['DM_id'];
            header('Location:index.php?error=0');
            die;
        } else {
            header('Location:login.php?error=1&passerror=1&login=1');
            die;
        }
    } else {
        header('Location:login.php?error=1&loginerror=1');
        die;
    }

}
