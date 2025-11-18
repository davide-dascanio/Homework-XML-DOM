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

            
            //chiudiamo la connessione
            $mysqliConnection->close();

        ?>
    </body>
</html>