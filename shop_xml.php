<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    // Controllo accesso
    if (!isset($_SESSION['accessoPermesso'])) {
        header('Location: login.php');
        exit();
    }

    require_once("./stile_shop.php");


    // Conta quanti biglietti con disponibilità limitata l'utente ha già acquistato negli ordini passati
    // Per farlo leggiamo ordini.xml, file di verità sugli acquisiti effettuati fino ad ora
    // Calcola storico acquisti UNA VOLTA SOLA 
    $acquistatiStorico = array();

    if (file_exists('ordini.xml')) {
        $xmlStringOrdini = "";
        foreach (file("ordini.xml") as $node) {
            $xmlStringOrdini .= trim($node);
        }
        $docOrdini = new DOMDocument();
        if (!$docOrdini->loadXML($xmlStringOrdini)) {
            die("Errore durante il parsing");
        }
        
        //getElementsByTagName('ordine') restituisce una LISTA (array) che contiene TUTTI gli elementi <ordine> del documento
        $ordini = $docOrdini->getElementsByTagName('ordine');

        //print_r($ordini); Per esempio abbiamo una cosa del genere: $ordini = [ordine1, ordine2, ordine3] → length = 3   (3 elementi)
        //                                                                         ↑        ↑        ↑
        //                                                                      item(0)  item(1)  item(2)   

        $quantitaGiaAcquistata = 0;

        for ($i = 0; $i < $ordini->length; $i++) {   // $i va da 0 a 3
            $ordine = $ordini->item($i);  // Prima item(0), poi item(1), poi item(2)
 
            // Dentro questo ordine specifico, cerca <username>
            // (ce n'è solo UNO per ordine)
            $usernameOrdine = $ordine->getElementsByTagName('username')->item(0)->textContent;
            // → Sempre item(0) perché c'è solo UN username per ordine
            
            // Controlla solo gli ordini dell'utente corrente
            if ($usernameOrdine == $_SESSION['username']) {

                //getElementsByTagName('articolo') restituisce una LISTA (array) che contiene tutti gli elementi <articolo> di quello specifico ordine
                $articoli = $ordine->getElementsByTagName('articolo');
                
                // Ciclo FOR per scorrere tutti gli <articolo> dentro questo ordine
                for ($j = 0; $j < $articoli->length; $j++) {
                    $articolo = $articoli->item($j);
                    
                    $nomeBigliettoOrdine = $articolo->getElementsByTagName('biglietto')->item(0)->textContent;
                    $qta = $articolo->getElementsByTagName('quantita')->item(0)->textContent;
                    
                    // Controlla se nell'array $acquistatiStorico esiste già questo biglietto
                    // Se NON esiste (!isset), lo inizializziamo a 0
                    if (!isset($acquistatiStorico[$nomeBigliettoOrdine])) {
                        $acquistatiStorico[$nomeBigliettoOrdine] = 0;
                    }

                    // Aggiungi la quantità di questo articolo al totale storico
                    // Esempio: se aveva già 2 "Grande Muraglia" e ne trova altri 3, diventa 2 + 3 = 5
                    $acquistatiStorico[$nomeBigliettoOrdine] += $qta;
                }
            }
        }
    }

    // Verifichiamo se il file xml che vogliamo caricare esiste
    if (!file_exists('data.xml')) {
        die("Errore: file data.xml non trovato");
    }

    // Costruiamo una stringa con il contenuto del file
    $xmlString = "";
    foreach ( file("data.xml") as $node ) {
	    $xmlString .= trim($node);
    }

    // Creazione del documento da usare
    $doc = new DOMDocument();

    // Carica contentuo del file (appiattito), nel documento $doc con DOM
    if (!$doc->loadXML($xmlString)) {
        die("Errore durante il parsing");
    }

    // Validazione DTD
    if ($doc->validate()) {
        $validDTD = "Il file XML data.xml è valido";
    } else  {
        $validDTD = "Il file XML data.xml non è valido";
    }


    // $biglietti è la lista degli elementi figli di catalogo del documento XML data.xml
    $biglietti = $doc->documentElement->childNodes;

    // Array immagini
    $immagini = array(
        "Grande Muraglia, Cina" => "./file/collegamento_1/img/muraglia.jpg",
        "Petra, Giordania" => "./file/collegamento_2/img/petra.jpg",
        "Cristo Redentore, Rio de Janeiro" => "./file/collegamento_3/img/redentore2.jpg",
        "Machu Picchu, Cusco - Perù" => "./file/collegamento_4/img/machu_picchu.jpg",
        "Chichén Itzá, Yucatàn - Messico" => "./file/collegamento_5/img/chichen_itza.jpg",
        "Colosseo, Roma - Italia" => "./file/collegamento_6/img/colosseo-roma.jpg",
        "Taj Mahal, Agra - India" => "./file/collegamento_7/img/taj_mahal.jpg"
    );

?>

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Shop XML - Le Sette Meraviglie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <?php echo $stile_shop; ?>
</head>
<body>
    <?php
        require("menu_shop.php");
    ?>

    <?php
        //Se il carrello non è creato e l'arrey $_POST è vuoto, allora creiamo il carrello
        if((!isset($_SESSION['carrello']) && !$_POST)) {
            $_SESSION['carrello'] = array();
        }else{
            // Se $_POST['selection'] è settato (1) allora aggiungiamo l'articolo nel carrello
            if(isset($_POST['selection'])) {
                $_SESSION['carrello'][] = $_POST['selection'];
    ?>  
                <!-- Mostra la notifica SOLO se è stato appena aggiunto qualcosa --> 
                <div class="messaggio-aggiunto">
                    <p><strong><i class="fas fa-circle-check"></i> Aggiunto al carrello:</strong> <?php echo $_POST['selection']; ?></p>
                    <a href="carrello.php">Vai al carrello (<?php echo count($_SESSION['carrello']); ?> articoli)</a> oppure
                    <a href="riepilogo_xml.php">Vai al pagamento (<?php echo count($_SESSION['carrello']); ?> articoli)</a>  <!-- Conta il numero di articoli nel carrello -->
                </div>
     <?php  } ?>
 <?php  } ?>


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
            
            <!-- Info DTD -->
            <div class="etichetta-sidebar">Modalità XML/DOM Validazione DTD</div>
            <div class="valore-sidebar" style="padding: 15px; background: #26272b; border-radius: 8px; border-left: 3px solid white;">
                <?php echo $validDTD ?> 
            </div>
        </div>
        
        <!-- Contentuo shop -->
        <div class="container-meraviglie">
            <?php
            for($i=0; $i<$biglietti->length; $i++){
                /* i-esimo biglietto del documento, questo ha quattro sottoelementi: 
                nome, prezzo, descrizione, caratteristiche */
                $biglietto = $biglietti->item($i);
                
                // Lettura attributi DTD dell'i-esimo biglietto
                $idBiglietto = $biglietto->getAttribute('id'); //otteniamo attributo con metodo getAttribute
                $categoria = $biglietto->getAttribute('categoria');
                $disponibilita = $biglietto->getAttribute('disponibilita');
                $lingua = $biglietto->getAttribute('lingua');
                

                // Scorriamo l'albero ed estraiamo/leggiamo i sottoelementi (di interesse) figli dell'i-esimo biglietto
                $nome = $biglietto->firstChild;
                $nomeValue = $nome->textContent;
                $prezzo = $nome->nextSibling;   // nextSibling ci porta al prossimo sottoelemento
                $prezzoValue = $prezzo->textContent;
                $descrizione = $prezzo->nextSibling;
                $descrizioneValue = $descrizione->textContent;

                // caratteristiche è un elemento con suoi figli
                $caratteristiche = $biglietto->lastChild;
                $durata = $caratteristiche->firstChild;   // Primo figlio di caratteristiche
                $durataValue = $durata->textContent;
                $guida = $durata->nextSibling;
                $guidaValue = $guida->textContent;
                $accessibilita = $caratteristiche->lastChild;
                $accessibilitaValue = $accessibilita->textContent;


                // Usa immagine dall'array definito sopra se disponibile
                if(isset($immagini[$nomeValue])){
                    $immagineFinale = $immagini[$nomeValue];
                }else{
                    $immagineFinale = "bianco.jpg";
                }

                
                // Badge disponibilità
                $coloriDisponibilita = array(
                    "disponibile" => "#2ec4b6",
                    "limitato" => "#f39c12",
                    "esaurito" => "#e74c3c"
                );

                if(isset($coloriDisponibilita[$disponibilita])){
                    $badgeColor = $coloriDisponibilita[$disponibilita];
                }else{
                    $badgeColor = "#95a5a6";  // default se non esiste
                }
            ?>        
                <div class="scheda-meraviglia">
                    <img src="<?php echo $immagineFinale; ?>" alt="<?php echo $nomeValue; ?>"/>
                    <div class="contenuto-scheda">
                        <div class="titolo-scheda"> <?php echo $nomeValue; ?> </div>

                        <!-- Riga id, categoria e disponibilità (sotto il titolo) -->
                        <div class="info-scheda">
                            <span class="info"> <?php echo $idBiglietto; ?> </span>
                            <span class="info"> <i class="fas fa-tag"></i> <?php echo $categoria; ?> </span>
                            <span class="info" style="background: <?php echo $badgeColor; ?>; color: white;">
                                <?php echo $disponibilita; ?>
                            </span>
                        </div>
                        
                        <!-- Descrizione -->
                        <div class="descrizione-scheda"> <?php echo $descrizioneValue; ?> </div>
                        
                        <!-- Caratteristiche -->
                        <div class="caratt-scheda">
                            <div class="dettagli"> <i class="fas fa-info-circle"></i> Dettagli Tour </div>
                            <div class="caratt">
                                <div class="stile-icona"> <i class="fas fa-clock"></i> </div>
                                Durata: <strong><?php echo $durataValue; ?></strong>
                            </div>
                            <div class="caratt">
                                <div class="stile-icona"> <i class="fas fa-user-tie"></i> </div>
                                Guida: <strong><?php echo $guidaValue; ?></strong>
                            </div>
                            <div class="caratt">
                                <div class="stile-icona"> <i class="fas fa-wheelchair"></i> </div>
                                Accessibilità: <strong><?php echo $accessibilitaValue; ?></strong>
                            </div>
                        </div>

                        
                        <div class="righa-carrello">
                            <span class="prezzo-scheda"><?php echo $prezzoValue; ?> &euro;</span>

                            <?php if ($disponibilita == 'esaurito'){  ?>
                                    <!-- Biglietto esaurito: input disabilitato SENZA form -->
                                    <input type="submit" disabled value="Esaurito" class="bottone-esaurito"/>

                            <?php }elseif($disponibilita == 'limitato'){ ?>
                                    <!-- LIMITATO: conta quanti ne ha già nel carrello -->
                                    <?php
                                    // 1. Conta quanti ne hai nel carrello
                                    $quantitaNelCarrello = 0;
                                    if (isset($_SESSION['carrello'])) {
                                        foreach ($_SESSION['carrello'] as $indice => $nomeBiglietto) {
                                            if ($nomeBiglietto == $nomeValue) {
                                                $quantitaNelCarrello++;
                                            }
                                        }
                                    }

                                    // 2. Leggi dallo storico pre-calcolato a inizio script
                                    $quantitaGiaAcquistata = isset($acquistatiStorico[$nomeValue]) ? $acquistatiStorico[$nomeValue] : 0;

                                    // 3. Calcola totale
                                    $limiteMassimo = 2;
                                    $quantitaTotale = $quantitaNelCarrello + $quantitaGiaAcquistata;

                                    // 4. Mostra bottone appropriato
                                    if ($quantitaTotale >= $limiteMassimo) { ?>
                                        <!-- Ha già raggiunto il limite -->
                                        <input type="submit" disabled value="Limite raggiunto (<?php echo $quantitaTotale; ?>/<?php echo $limiteMassimo; ?>)" class="bottone-limitato"/>
                                    <?php } else { ?>
                                        <!-- Può ancora aggiungere -->
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <input type="hidden" name="selection" value="<?php echo $nomeValue; ?>"/>
                                            <input type="submit" name="aggiungiAlCarrello" class="bottone-aggiungi" value="Aggiungi (<?php echo $quantitaTotale; ?>/<?php echo $limiteMassimo; ?>)"/>
                                        </form>
                                    <?php } ?>

                            <?php }else{  ?>
                                    <!-- Biglietto disponibile: input normale -->
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                        <input type="hidden" name="selection" value="<?php echo $nomeValue; ?>"/>
                                        <input type="submit" name="aggiungiAlCarrello" class="bottone-aggiungi" value="Aggiungi al carrello"/>
                                    </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
    <?php   } ?>  
        </div>
    </div>
</body>
</html>
