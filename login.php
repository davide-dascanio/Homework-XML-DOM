<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $msg = "";

    session_start();

    /* Se l'utente loggato ripassa per Accedi/Registrati viene fatto l'unset, altrimenti carrello uguale per un utente con doppio account o per due utenti diversi */
    if (isset($_SESSION['accessoPermesso'])){
        unset($_SESSION);
        session_destroy();
    }

    /*dati sui nomi delle tabelle e del database, nonche' sulle modalita' di 
    connessione e di selezione del database sono messi in un file a parte */
    require_once("./connessione1.php");

    //per lo stile
    require_once("./stile_autenticazione.php");



    //una volta che siamo nel db, verichiamo se siano stati inseriti correttamente
    //i campi username e password e facciamo una query per controllare

    if (isset($_POST['invio'])){          // abbiamo appena inviato dati attraverso la form di login
        if (empty($_POST['username']) || empty($_POST['password'])){
            $msg = "<em>Dati mancanti. Riprova</em>";
        }else {
            //verifichiamo se i dati inseriti corrispondono a un account esistente
            $sql = "SELECT *
                    FROM $Utenti_table_name
                    WHERE username = \"{$_POST['username']}\" AND password =\"{$_POST['password']}\"
                    ";

            // il risultato della query va in $resultQ
            if (!$resultQ = mysqli_query($mysqliConnection, $sql)) {
                printf("Errore, la query non ha risultato\n");
                exit();
            }
            
            //se l'account esiste
            $row = mysqli_fetch_array($resultQ);

            if($row) {  
                session_start();
                $_SESSION['nome']=$row['nome'];
                $_SESSION['cognome']=$row['cognome'];
                $_SESSION['username']=$_POST['username'];
                $_SESSION['spesaFinora']=$row['sommeSpese'];
                $_SESSION['dataLogin']=time();
                $_SESSION['accessoPermesso']=1000;
                header('Location: shop.php');    // accesso alla pagina iniziale
                exit();
            }else 
                $msg = "<em>Username e password inseriti non corrispondono a nessun account. <br /> Riprova o registrati.</em>";
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
        <title>Login</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <?php echo $stile_autenticazione; ?>
    </head>

    <body>
        <h3>User Login</h3>

        <?php 
            if(!empty($msg)) {
                echo "<p>".$msg."</p>";
            }
        ?>

        <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
            <p>  <i class="fas fa-user"></i>  username: <input type="text" name="username" size="30" /> </p>
            <p>  <i class="fas fa-lock"></i>  password: <input type="password" name="password" size="30" /> </p>
            
            <p>
                <input type="submit" name="invio" value="Accedi" />
                <input type="reset" name="reset" value="Cancella" /> 
            </p>
            <div class="sezione-finale-login">
                <p>Non hai ancora un account?</p>
                <a href="registrazione.php">Registrati</a>
            </div>
        </form>

    </body>
</html>