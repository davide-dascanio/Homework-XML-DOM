<?php

    $db_name = "7MeraviglieDB";

    //effettuazione della connessione al database
    $mysqliConnection = new mysqli("localhost", "root", "root");

    //controllo della connessione
    if (mysqli_connect_errno()) {
        printf("Problemi con la connessione al db: %s\n", mysqli_connect_error());
        exit();
    }

    /* ELIMINIAMO IL DBS SE GIA' CREATO*/
    $queryEliminaDatabase = "DROP DATABASE if exists $db_name";
    // il risultato della query va in $dropResult
    $dropResult = mysqli_query($mysqliConnection, $queryEliminaDatabase);


    // creazione del database
    $queryCreazioneDatabase = "CREATE DATABASE $db_name";
    // il risultato della query va in $resultQ
    $resultQ = mysqli_query($mysqliConnection, $queryCreazioneDatabase);
    if ($resultQ && $dropResult) {
        printf("Database creato <br />\n");
    }
    else {
        printf("Errore nella creazione del database <br />\n");
        exit();
    }

    //chiudiamo la connessione
    $mysqliConnection->close();

    //e la riapriamo con il collegamento alla base di dati
    require_once("connessione1.php")

?>