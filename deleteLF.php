<?php
    session_start();
    include_once('db.php');

    $req = $db->prepare("DELETE FROM ligne_frais WHERE LF_id = :idLF");
    $isDeleteOkay = $req->execute([':idLF' => $_POST['idLF']]);
    if(!$isDeleteOkay){
        echo 'Erreur lors de la suppression';
        die;
    }else{
        header('Location: index.php?deleteOkay=1');
    }
?>
