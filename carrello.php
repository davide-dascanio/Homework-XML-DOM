<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    // Controllo accesso
    if (!isset($_SESSION['accessoPermesso']))
        header('Location: login.php');

    require_once("./stile_shop.php");

    // Gestione delle azioni
    $messaggio = "";

    // Elimina biglietti selezionati
    if (isset($_POST['eliminaSelezionati']) && isset($_POST['eliminandi'])) {
        foreach ($_POST['eliminandi'] as $k=>$indiceDaEliminare) {
            unset($_SESSION['carrello'][$indiceDaEliminare]);
        }
        $messaggio = "Biglietti selezionati eliminati dal carrello!";
    }else{
        // Svuota tutto il carrello
        if (isset($_POST['svuotaCarrello'])) {
            $_SESSION['carrello'] = array();
            $messaggio = "Carrello svuotato completamente!";
        }

        if (isset($_POST['eliminaSelezionati']))
            $messaggio = "Seleziona i biglietti che vuoi eliminare!";
    }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Il Tuo Carrello - Le Sette Meraviglie</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <?php echo $stile_shop; ?>
    </head>
    <body>
        <?php require("menu_shop.php"); ?>

        <?php 
            if ($messaggio != "") { 
        ?>      
                <div class="messaggio-aggiunto">
                    <p><?php echo $messaggio; ?></p>
                </div>
                <?php 
            } 
        ?>

        <div class="container-principale">
            <div class="sidebar">
                <h2>Il tuo profilo</h2>
                <div class="etichetta-sidebar">Username:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['username']; ?></div>
                
                <div class="etichetta-sidebar">Nome:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['nome']; ?></div>
                
                <div class="etichetta-sidebar">Cognome:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['cognome']; ?></div>
                
                <div class="etichetta-sidebar">Finora hai speso:</div>
                <div class="valore-sidebar"> <?php echo $_SESSION['spesaFinora']; ?> &euro; </div>
                
                <div class="etichetta-sidebar">Ti sei collegato alle:</div>
                <div class="valore-sidebar">
                    <?php echo date ('g:i a', $_SESSION['dataLogin']) ?>
                </div>
                
                <div class="etichetta-sidebar">Articoli nel carrello:</div>
                <div class="valore-sidebar">
                    <?php echo count($_SESSION['carrello']); ?>
                </div>
            </div>

            <!-- Contenuto carrello -->
            <div class="container-carrello">
                <h2 class="titolo-carrello">Il Tuo Carrello</h2>

                <?php 
                    if (empty($_SESSION['carrello'])){
                ?>
                        <div class="carrello-vuoto">
                            <p>Il tuo carrello è vuoto</p>
                            <a href="shop.php">Vai al Catalogo</a>
                        </div>
                        <?php 
                    }else{ 
                ?>
                        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="carrello-pieno">
                                <?php 
                                    foreach ($_SESSION['carrello'] as $indice => $nomeBiglietto){
                                ?>
                                        <div class="articolo-carrello">
                                            <input type="checkbox" name="eliminandi[]" value="<?php echo $indice; ?>" /> <?php echo $nomeBiglietto; ?>
                                        </div>
                                        <?php
                                    } 
                                ?>
                            </div>

                            <div class="container-pulsanti">
                                <input type="submit" name="eliminaSelezionati" class="bottone-rosso1" value="Elimina Selezionati" />
                                
                                <input type="submit" name="svuotaCarrello" class="bottone-rosso2" value="Svuota Carrello" />

                                <input type="reset" name="annullaSelezionati" class="bottone-bianco" value="Deseleziona Tutto" />

                                <a href="shop.php" class="bottone-grigio"> Continua Acquisti </a>
                                
                                <a href="riepilogo.php" class="bottone-acqua"> Vai al Pagamento </a>
                            </div>
                        </form>
                        <?php 
                    } 
                ?>
            </div>
        </div>
    </body>
</html>
