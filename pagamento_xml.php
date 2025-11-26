<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    // Controllo accesso
    if (!isset($_SESSION['accessoPermesso'])){
        header('Location: login.php');
        exit();
    }


    // Connessione al database
    require_once("./connessione1.php");

    require_once("./stile_shop.php");


    // Variabili per il messaggio
    $messaggio = "";
    $pagamentoOk = false;
    $mostraConferma = false;

    //print_r($_POST);
    //print_r($_SESSION);

    // Controlla se c'è qualcosa da pagare
    if (!isset($_SESSION['daPagare']) || $_SESSION['daPagare'] == 0 || empty($_SESSION['carrello'])) {
        $messaggio = "Il carrello è vuoto, oppure stai saltando passaggi devi passare per il riepilogo dell'ordine per completare il pagamento!";
    } else {
        // C'è qualcosa da pagare

        // Se NON ha ancora confermato, mostra la conferma
        if (!isset($_POST['confermaPagamento']) ) {
            // Mostra schermata conferma
            $mostraConferma = true;
        } else {
            // Confermato, salva ordine in XML

            // Salviamo l'importo pagato ora (prima di azzerarlo)
            $importoPagatoOra = $_SESSION['daPagare'];

            // Calcoliamo il nuovo totale
            // Somma quanto pagato ora + quanto speso in passato
            $nuovoTotale = $_SESSION['daPagare'] + $_SESSION['spesaFinora'];
            
            // Verifichiamo se il file xml esiste
            if (!file_exists('data.xml')) {
                die("Errore: file data.xml non trovato");
            }

            // Costruiamo una stringa con il contenuto del file
            $xmlString = "";
            foreach ( file("data.xml") as $node ) {
                $xmlString .= trim($node);
            }

            // Creazione del documento
            $doc = new DOMDocument();

            // Carica contentuo del file nel documento $doc con DOM
            if (!$doc->loadXML($xmlString)) {
                die("Errore durante il parsing");
            }

            // $biglietti è la lista degli elementi figli della radice catalogo del documento XML data.xml
            $biglietti = $doc->documentElement->childNodes;


            // SCRITTURA ORDINE IN ordini.xml

            // Carica o crea ordini.xml
            if (file_exists('ordini.xml')) {
                $xmlStringOrdini = "";
                foreach (file("ordini.xml") as $node) {
                    $xmlStringOrdini .= trim($node);
                }
                $docOrdini = new DOMDocument();
                if (!$docOrdini->loadXML($xmlStringOrdini)) {
                    die("Errore durante il parsing");
                }
                $ordini = $docOrdini->documentElement;
            } else {
                // Crea documento
                // Aggiunge dichiarazione DOCTYPE
                $implementation = new DOMImplementation();
                $dtd=$implementation->createDocumentType('ordini','','ordini.dtd');
                $docOrdini=$implementation->createDocument('','',$dtd);
                $docOrdini->encoding ='UTF-8';

                // Crea radice <ordini>
                $ordini=$docOrdini->createElement('ordini');
                $docOrdini->appendChild($ordini);
            }

            // Vogliamo output leggibile (indentato)
            $docOrdini->formatOutput = true;
            
            // Crea nuovo elemento <ordine>
            $nuovoOrdine = $docOrdini->createElement('ordine');
            $nuovoOrdine->setAttribute('id', rand(0,10000)); // Attributo id obbligatorio
            
            // Elementi figli di <ordine>
            // <username>
            $elemUsername = $docOrdini->createElement('username', $_SESSION['username']);
            $nuovoOrdine->appendChild($elemUsername);

            // <date>
            $elemDate = $docOrdini->createElement('date', date('d-m-Y H:i:s'));
            $nuovoOrdine->appendChild($elemDate);

            // <totale>
            $elemTotale = $docOrdini->createElement('totale', $importoPagatoOra);
            $nuovoOrdine->appendChild($elemTotale);

            // <articoli> (contenitore di sotto-elementi <articolo>)
            $elemArticoli = $docOrdini->createElement('articoli');

            // Vogliamo salvare tutti gli <articolo> dell'ordine in <articoli>, che a sua volta è un sotto-elemento di <ordine>
            // (vedere ordini.dtd!!)
            // Conta le occorrenze di ogni biglietto nel carrello
            $conteggio = array_count_values($_SESSION['carrello']);
            
            foreach ($conteggio as $nomeBiglietto => $quantita) {
                // Recupera prezzo (attraversando DOM catalogo data.xml)
                $prezzoTrovato = 0;

                // Navigazione nel catalogo
                for ($i = 0; $i < $biglietti->length; $i++) {
                    $biglietto = $biglietti->item($i);
                    
                    $nome = $biglietto->firstChild;
                    $nomeValue = $nome->textContent;
                    if ($nomeValue == $nomeBiglietto) {
                        $prezzo = $nome->nextSibling;
                        $prezzoTrovato = $prezzo->textContent;
                        // Il break serve per uscire dal ciclo for non appena viene trovato l’elemento desiderato, 
                        // evitando di continuare a controllare gli altri
                        break;
                    }
                }   

                // Crea elemento <articolo>
                $elemArticolo = $docOrdini->createElement('articolo');

                // Figli di <articolo>: <biglietto>, <prezzo>, <quantita>
                $elemBiglietto = $docOrdini->createElement('biglietto', $nomeBiglietto);
                $elemPrezzo = $docOrdini->createElement('prezzo', $prezzoTrovato);
                $elemQuantita = $docOrdini->createElement('quantita', $quantita);

                $elemArticolo->appendChild($elemBiglietto);
                $elemArticolo->appendChild($elemPrezzo);
                $elemArticolo->appendChild($elemQuantita);
                

                // Aggiungi articolo alla lista articoli
                $elemArticoli->appendChild($elemArticolo);
            }
            
            $nuovoOrdine->appendChild($elemArticoli);

            $primoOrdine = $ordini->firstChild;

            // Aggiungi ordine alla radice <ordini>, ma come nuovo primo figlio
            $ordini->insertBefore($nuovoOrdine,$primoOrdine);
            
                
            // Salva su file
            $filename="ordini.xml";
            if ($docOrdini->save($filename)){
                // QUERY UPDATE: aggiorna la spesa dell'utente
                $sql = "UPDATE $Utenti_table_name 
                        SET sommeSpese = \"$nuovoTotale\" 
                        WHERE username = \"{$_SESSION['username']}\"";
                
                // Eseguiamo la query e la controlliamo
                if (!mysqli_query($mysqliConnection, $sql) || mysqli_affected_rows($mysqliConnection) != 1) {
                    printf("Errore nella gestione del pagamento: %s\n", mysqli_error($mysqliConnection));
                    // UPDATE fallito: elimina l'ordine appena salvato nell'XML 
                    $ordini->removeChild($nuovoOrdine);
                    $docOrdini->save($filename);
                    $messaggio = "Errore nell'aggiornamento della spesa totale.";
                }else{
                    // SUCCESSO
                    $pagamentoOk = true;

                    // Aggiorna la sessione con il nuovo totale
                    $_SESSION['spesaFinora'] = $nuovoTotale;
                    
                    // Svuotiamo il carrello
                    $_SESSION['carrello'] = array();
                    $_SESSION['daPagare'] = 0;
                    
                    $messaggio = "il pagamento è stato completato con successo";
                }
            }else{
                $messaggio = "Si è verificato un problema durante il salvataggio dell'ordine.";
            }
        }
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Pagamento - Le Sette Meraviglie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <?php echo $stile_shop; ?>
</head>
<body>

<?php require_once('./menu_shop.php'); ?>

<div class="container-principale">
    
    <!-- Sidebar profilo -->
    <div class="sidebar">
        <h2>Il tuo profilo</h2>
        <div class="etichetta-sidebar">Username:</div>
        <div class="valore-sidebar"><?php echo $_SESSION['username']; ?></div>
        
        <div class="etichetta-sidebar">Nome:</div>
        <div class="valore-sidebar"><?php echo $_SESSION['nome']; ?></div>
        
        <div class="etichetta-sidebar">Cognome:</div>
        <div class="valore-sidebar"><?php echo $_SESSION['cognome']; ?></div>
        
        <div class="etichetta-sidebar">Finora hai speso:</div>
        <div class="valore-sidebar"><?php echo $_SESSION['spesaFinora']; ?> &euro;</div>
        
        <div class="etichetta-sidebar">Ti sei collegato alle:</div>
        <div class="valore-sidebar"><?php echo date('g:i a', $_SESSION['dataLogin']); ?></div>
    </div>
    
    <!-- Contenuto pagamento -->
    <div class="container-pagamento">
        <?php if ($pagamentoOk){ ?>
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
                            <span>Importo pagato:</span>
                            <strong><?php echo $importoPagatoOra; ?> &euro;</strong>
                        </div>
                        <div class="spesa-totale">
                            <span>Totale speso finora:</span>
                            <strong><?php echo $_SESSION['spesaFinora']; ?> &euro;</strong>
                        </div>
                    </div>
                </div>
                <div class="box-info">
                    <div class="contenuto-info">
                        <div style="color: #a3a6ad; font-size: 20px;">Per vedere la cronologia degli ordini: </div>
                        <a href="ordini_utente.php" class="bottone"><i class="fas fa-box"></i> I Miei Ordini </a>
                    </div>
                </div>
                <div class="container-azioni">
                    <a href="shop_xml.php" class="bottone-back">Torna al Catalogo</a>
                    <a href="logout.php" class="bottone">Esci</a>
                </div>
       <?php }else{

                if ($mostraConferma){ ?>
                    <!-- Schermata conferma -->
                    <div class="container-conferma">
                        <div class="icona-allerta"> 
                            <i class="fas fa-exclamation-triangle"></i> 
                        </div>
                        <h2 class="titolo-conferma"> Conferma Pagamento </h2>
                        <p class="testo-conferma">Stai per procedere con il pagamento di:</p>
                        <div class="importo-conferma"><?php echo $_SESSION['daPagare']; ?> &euro; </div>
                        
                        <!-- Bottoni -->
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
                            <div class="container-azioni">
                                <a href="riepilogo_xml.php" class="bottone-back"> Annulla </a>
                                <input type="submit" name="confermaPagamento" value="Conferma e Paga" class="bottone" />
                            </div>
                        </form>
                    </div>
          <?php }else{ ?>
                    <!-- ERRORE -->
                    <div class="container-errore">
                        <div class="icona-errore">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h2 class="titolo-errore">Attenzione</h2>
                        <p class="testo-errore"><?php echo $messaggio; ?></p>
                    </div>
                    <div class="container-azioni">
                        <a href="shop_xml.php" class="bottone-back">Vai al Catalogo</a>
                        <a href="riepilogo_xml.php" class="bottone">Vai al Riepilogo/Pagamento</a>
                    </div>
          <?php } ?>
       <?php } ?>
    </div>
</div>

</body>
</html>
