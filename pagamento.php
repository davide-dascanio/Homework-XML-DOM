<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    // Controllo accesso
    if (!isset($_SESSION['accessoPermesso'])){
        header('Location: login.php');
        exit();
    }

    //print_r($_POST);

    // Connessione al database
    require_once("./connessione1.php");

    require_once("./stile_shop.php");
    

    // Variabili per il messaggio
    $messaggio = "";
    $pagamentoOk = false;

    //print_r($_SESSION);
    // Controlla se c'è qualcosa da pagare
    if (!isset($_SESSION['daPagare']) && !empty($_SESSION['carrello'])){
        $messaggio = "Devi passare per il riepilogo dell'ordine prima di concludere l'acquisto! (Premi su Vai al Pagamento)";
    }else{
        if (!isset($_SESSION['daPagare']) || $_SESSION['daPagare'] == 0) {
            // Se non c'è nulla da pagare, rimanda al carrello
            $messaggio = "Non c'è nulla da pagare. Il carrello è vuoto! (Premi su Vai al Catalogo)";
        } else {

            //ULTIMO CONTROLLO
            if(isset($_POST['invioPagamento'])!="Procedi con il pagamento"){
                header('Location: riepilogo.php');
                exit();
            }

            // Salviamo l'importo pagato ora (prima di azzerarlo)
            $importoPagatoOra = $_SESSION['daPagare'];
        
            // Calcoliamo il nuovo totale
            // Somma quanto pagato ora + quanto speso in passato
            $nuovoTotale = $_SESSION['daPagare'] + $_SESSION['spesaFinora'];
            
            // QUERY UPDATE: aggiorna la spesa dell'utente
            $sql = "UPDATE $Utenti_table_name 
                    SET sommeSpese = \"$nuovoTotale\" 
                    WHERE username = \"{$_SESSION['username']}\"";
            
            // Eseguiamo la query e la controlliamo
            if (!mysqli_query($mysqliConnection, $sql)) {
                printf("Errore nella gestione del pagamento: %s\n", mysqli_error($mysqliConnection));
                exit(); // FERMIAMO subito lo script
            }
            
            // Controlla se la query ha modificato una riga
            if (mysqli_affected_rows($mysqliConnection) == 1) {
                $pagamentoOk = true;
                
                // Aggiorna la sessione con il nuovo totale
                $_SESSION['spesaFinora'] = $nuovoTotale;
                
                // Svuotiamo il carrello
                $_SESSION['carrello'] = array();
                $_SESSION['daPagare'] = 0;
                
                $messaggio = "il pagamento è stato completato con successo";
            } else {
                $messaggio = "Si è verificato un problema durante il pagamento.";
            }
        }
    }

    $mysqliConnection->close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Pagamento - Le Sette Meraviglie</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
        <?php echo $stile_shop; ?>
    </head>
    <body>
        <?php require("menu_shop.php"); ?>
        
        <div class="container-principale">
            <div class="sidebar">
                <h2>Il tuo profilo</h2>
                <div class="etichetta-sidebar">Username:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['username']; ?></div>
                
                <div class="etichetta-sidebar">Nome:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['nome']; ?></div>
                
                <div class="etichetta-sidebar">Cognome:</div>
                <div class="valore-sidebar"><?php echo $_SESSION['cognome']; ?></div>
                
                <div class="etichetta-sidebar">Totale speso:</div>
                <div class="valore-sidebar">
                    <?php echo $_SESSION['spesaFinora']; ?> &euro;
                </div>
                
                <div class="etichetta-sidebar">Ti sei collegato alle:</div>
                <div class="valore-sidebar">
                    <?php echo date ('g:i a', $_SESSION['dataLogin']) ?>
                </div>
            </div>
            
            <!-- Contenuto pagamento -->
            <div class="container-pagamento">
                <?php 
                    if ($pagamentoOk){
                ?>
                        <!-- Pagamento riuscito -->
                        <div class="container-successo">
                            <div class="icona-successo">
                                <i class="fas fa-check"></i>
                            </div>
                            <h2 class="titolo-successo">Pagamento Effettuato!</h2>
                            <p class="testo-successo">
                                Gentile <?php echo $_SESSION['nome']; ?>, <?php echo $messaggio; ?>.
                            </p>
                            <div class="dettaglio-spesa">
                                <div class="importo-ora">
                                    <span>Importo pagato ora:</span>
                                    <strong><?php echo $importoPagatoOra; ?> &euro;</strong>
                                </div>
                                <div class="spesa-totale">
                                    <span>Spesa totale:</span>
                                    <strong><?php echo $_SESSION['spesaFinora']; ?> &euro;</strong>
                                </div>
                            </div>
                        </div>
                        <div class="container-azioni">
                            <a href="shop.php" class="bottone-back">Torna al Catalogo</a>
                            <a href="logout.php" class="bottone">Esci</a>
                        </div>
                        <?php
                    }else{
                ?>
                        <!-- Errore o carrello vuoto -->
                        <div class="container-errore">
                            <div class="icona-errore">
                                <i class="fas fa-times"></i>
                            </div>
                            <h2 class="titolo-errore">Attenzione</h2>
                            <p class="testo-errore"><?php echo $messaggio; ?></p>
                        </div>
                        
                        <div class="container-azioni">
                            <a href="shop.php" class="bottone-back">Vai al Catalogo</a>
                            <a href="riepilogo.php" class="bottone">Vai al Pagamento</a>
                        </div>
                        <?php 
                    }
                ?>
            </div>
        </div>
    </body>
</html>
