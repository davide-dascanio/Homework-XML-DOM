<?php
    //dati relativi al db e alle tabelle da usare negli script che includono questo file
    $db_name = "7MeraviglieDB";
    $Utenti_table_name = "Utenti";
    $Biglietti_table_name = "Biglietti";

    //esecuzione del tentativo di connessione al DB creato
    $mysqliConnection = new mysqli("localhost", "root", "root", $db_name);
    
    //controllo della connessione
    if (mysqli_connect_errno()) {
        printf("Problemi con la connessione al db: %s\n", mysqli_connect_error($mysqliConnection));
        exit();
    }
?>