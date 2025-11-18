<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Creazione e popolamento DB</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
                padding: 20px;
            }
        </style>
    </head>

    <body>
        <h3>Creazione e popolazione del database 7MeraviglieDB</h3>
        
        <?php
            error_reporting(E_ALL);

            //Connessione al database e sua creazione
            require_once("connessione2.php");

        //creazione tabelle
            //creazione tabella Utenti
            $sqlQuery = "CREATE TABLE if not exists $Utenti_table_name (";
            $sqlQuery.= "userId int NOT NULL auto_increment, primary key (userId), ";
            $sqlQuery.= "nome varchar (50) NOT NULL, ";
            $sqlQuery.= "cognome varchar (50) NOT NULL, ";
            $sqlQuery.= "username varchar (50) NOT NULL UNIQUE, ";
            $sqlQuery.= "password varchar (32) NOT NULL, ";
            $sqlQuery.= "sommeSpese float";
            $sqlQuery.= ");";

            //echo "<pre>$sqlQuery</pre>";

            //verifica creazione tabella Utenti
            if ($resultQ = mysqli_query($mysqliConnection, $sqlQuery))
                printf("La tabella Utenti è stata creata <br />\n");
            else {
                printf("Errore nella creazione della tabella Utenti! <br />\n");
                exit();
            }
            
            //creazione tabella Biglietti
            $sqlQuery = "CREATE TABLE if not exists $Biglietti_table_name (";
            $sqlQuery.= "bigliettoId int NOT NULL auto_increment, primary key (bigliettoId), ";
            $sqlQuery.= "nome varchar (50) NOT NULL, ";
            $sqlQuery.= "costoBiglietto float";
            $sqlQuery.= ");";

            //echo "<pre>$sqlQuery</pre>";

            //verifica creazione tabella Biglietti
            if ($resultQ = mysqli_query($mysqliConnection, $sqlQuery))
                printf("La tabella Biglietti è stata creata <br />\n");
            else {
                printf("Errore nella creazione della tabella Biglietti! <br />\n");
                exit();
            }

            //echo "<br />Messaggi relativi all'ultimo errore: " . mysqli_errno($mysqliConnection). "<br /><br />\n";

        //popolamento tabelle
            //popolamento Utenti (NB: userId gestito automaticamente)
            $sql = "INSERT INTO $Utenti_table_name
                    (nome, cognome, username, password, sommeSpese)
                    VALUES
                    (\"Mario\", \"Rossi\", \"mario.11\", \"1234\", \"0\")
                    ";

            if ($resultQ = mysqli_query($mysqliConnection, $sql))
                printf("Utente inserito correttamente <br />\n");
            else {
                printf("Errore inserimento utente <br />\n");
                exit();
            }

            $sql = "INSERT INTO $Utenti_table_name
                    (nome, cognome, username, password, sommeSpese)
                    VALUES
                    (\"Luigi\", \"Verdi\", \"luigi.verdi11\", \"1234\", \"0\")
                    ";

            if ($resultQ = mysqli_query($mysqliConnection, $sql))
                printf("Utente inserito correttamente <br />\n");
            else {
                printf("Errore inserimento utente <br />\n");
                exit();
            }


            //popolamento Biglietti (NB: bigliettoId gestito automaticamente)
            $sql = "INSERT INTO $Biglietti_table_name
                    (nome, costoBiglietto)
                    VALUES
                    (\"Grande Muraglia Cinese, Cina\", \"300\")
                    ";

            if ($resultQ = mysqli_query($mysqliConnection, $sql))
                printf("Biglietto inserito correttamente <br />\n");
            else {
                printf("Errore inserimento biglietto <br />\n");
                exit();
            }


            $sql = "INSERT INTO $Biglietti_table_name
                    (nome, costoBiglietto)
                    VALUES
                    (\"Petra, Giordania\", \"80\")
                    ";

            if ($resultQ = mysqli_query($mysqliConnection, $sql))
                printf("Biglietto inserito correttamente <br />\n");
            else {
                printf("Errore inserimento biglietto <br />\n");
                exit();
            }


            $sql = "INSERT INTO $Biglietti_table_name
                    (nome, costoBiglietto)
                    VALUES
                    (\"Cristo Redentore, Rio de Janeiro\", \"50\")
                    ";  

            if ($resultQ = mysqli_query($mysqliConnection, $sql))
                printf("Biglietto inserito correttamente <br />\n");
            else {
                printf("Errore inserimento biglietto <br />\n");
                exit();
            }


            $sql = "INSERT INTO $Biglietti_table_name
                    (nome, costoBiglietto)
                    VALUES
                    (\"Machu Picchu, Cusco - Perù\", \"150\")
                    ";  

            if ($resultQ = mysqli_query($mysqliConnection, $sql))
                printf("Biglietto inserito correttamente <br />\n");
            else {
                printf("Errore inserimento biglietto <br />\n");
                exit();
            }

            
            $sql = "INSERT INTO $Biglietti_table_name
                    (nome, costoBiglietto)
                    VALUES
                    (\"Chichén Itzá, Yucatàn - Messico\", \"120\")
                    ";  

            if ($resultQ = mysqli_query($mysqliConnection, $sql))
                printf("Biglietto inserito correttamente <br />\n");
            else {
                printf("Errore inserimento biglietto <br />\n");
                exit();
            }


            $sql = "INSERT INTO $Biglietti_table_name
                    (nome, costoBiglietto)
                    VALUES
                    (\"Colosseo, Roma - Italia\", \"70\")
                    ";  

            if ($resultQ = mysqli_query($mysqliConnection, $sql))
                printf("Biglietto inserito correttamente <br />\n");
            else {
                printf("Errore inserimento biglietto <br />\n");
                exit();
            }


            $sql = "INSERT INTO $Biglietti_table_name
                    (nome, costoBiglietto)
                    VALUES
                    (\"Taj Mahal, Agra - India\", \"100\")
                    ";

            if ($resultQ = mysqli_query($mysqliConnection, $sql))
                printf("Biglietto inserito correttamente <br />\n");
            else {
                printf("Errore inserimento biglietto <br />\n");
                exit();
            }
            
            
            //chiudiamo la connessione
            $mysqliConnection->close();

        ?>
    </body>
</html>