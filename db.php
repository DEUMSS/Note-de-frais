<?php

if( strstr($_SERVER['HTTP_HOST'], '51.178.86.117') ){
    $dbname = 'damien_2';
    $dblogin = 'damien';
    $dbpassword = 'Cei7Thi&';
} else {
    $dbname = 'note_de_frais';
    $dblogin = 'root';
    $dbpassword = '';
}

// Connexion à la base de donnÃ©es
try
{
    $db = new PDO('mysql:host=localhost;dbname='.$dbname.';charset=utf8', $dblogin, $dbpassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On Ã©met une alerte Ã  chaque fois qu'une
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

?>