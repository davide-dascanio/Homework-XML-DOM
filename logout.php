<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("./stile_autenticazione.php");

session_start();
unset($_SESSION);
session_destroy();
?>


<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Logout</title>
        <?php echo $stile_autenticazione; ?>
    </head>
    <body>
        <h3>Logout Effettuato!</h3>
        <div class="stile-logout">
            <p>
                Hai effettuato il logout con successo.<br />
                La tua sessione è stata terminata.
            </p>
            
            <div class="bottone">
                <a href="login.php" class="bottone-verde">Torna al Login</a>
                <a href="index.php" class="bottone-grigio">Vai alla Home</a>
            </div>
        </div>
    </body>
</html>