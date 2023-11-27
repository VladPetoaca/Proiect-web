<?php
$hostname = 'localhost';

$username = 'root';

$password = '';

$db = 'proiect_pagina_web';

$mysqli = new mysqli($hostname, $username, $password, $db);

if(!mysqli_connect_errno())
{
    //echo 'Connectat la baza de date: '. $db;

}
else
{
    echo 'Nu se poate connecta';
    exit();
}