<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $msg = "";
    $success = "";

    require_once("./connessione1.php");

    require_once("./stile_autenticazione.php");

    if(isset($_POST['invio'])) {
        //Validazione campi
        if(($_POST['nome']) && ($_POST['cognome']) && ($_POST['username']) && ($_POST['password']) ) {

            //verifica se utente già registrato
            $sql = "SELECT *
                    FROM $Utenti_table_name
                    WHERE username = \"{$_POST['username']}\"
                    ";
            
            // il risultato della query va in $resultQ
            if (!$resultQ = mysqli_query($mysqliConnection, $sql)) {
                printf("Errore nel controllo dei dati.\n");
                exit();
            }

            $row = mysqli_fetch_array($resultQ);

            if($row){
                $msg = "<em> Se sei già registarto, <a href='login.php'>accedi qui</a>. <br /> Altrimenti scegli un username diverso.</em>";
            }else{
                $sql = "INSERT INTO $Utenti_table_name
                    (nome, cognome, username, password, sommeSpese)
                    VALUES
                    ('{$_POST['nome']}', '{$_POST['cognome']}','{$_POST['username']}','{$_POST['password']}', \"0\")
                    ";

                //controllo query
                if(!($resultQ = mysqli_query($mysqliConnection, $sql))) {
                    printf("Si è verificato un errore. Impossibile registrarsi.\n");
                    exit();
                }else{
                    $success = "<p>Registrazione completata con successo! 
                                <br /><a href='login.php'>Clicca qui per effettuare il login</a></p>";
                }
            }
        }else{
            $msg = "<em>Tutti i campi sono obbligatori.</em>";
        }
    }

    //chiudiamo la connessione
    $mysqliConnection->close();

?>

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head> 
		<title> Registrazione </title> 
        <?php echo $stile_autenticazione; ?>
	</head>
	
	<body>
		<h3> Crea il tuo account </h3>
		
        <?php 
            if(!empty($msg)) {
                echo "<p>".$msg."</p>";
            }
            if(!empty($success)) {
                echo "<div class='messaggio-successo'>".$success."</div>";
            }
        ?>
		
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <p>Nome: <input type="text" name="nome" size="30" /></p>
            
            <p>Cognome: <input type="text" name="cognome" size="30" /></p>
            
            <p>Username: <input type="text" name="username" size="30" /></p>
            
            <p>Password: <input type="password" name="password" size="30" /></p>
            <p>
                <input type="submit" name="invio" value="Registrati">
                <input type="reset" name="reset" value="Cancella">
            </p>
        </form>
    
		<p class="ultima-p">
            Hai già un account? <a href="login.php">Accedi qui</a>
        </p>
        
	</body>
</html>