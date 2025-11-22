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
                                    $quantitaNelCarrello = 0;
                                    if (isset($_SESSION['carrello'])) {
                                        foreach ($_SESSION['carrello'] as $indice => $nomeBiglietto) {
                                            if ($nomeBiglietto == $nomeValue) {
                                                $quantitaNelCarrello++;
                                            }
                                        }
                                    }
                                    $limiteMassimo = 2;
                                    if ($quantitaNelCarrello >= $limiteMassimo){ ?>
                                        <!-- Limite raggiunto: input disabilitato SENZA form -->
                                        <input type="submit" disabled value="Limite (<?php echo $quantitaNelCarrello; ?>/<?php echo $limiteMassimo; ?>)" class="bottone-limitato"/>
                              <?php }else{  ?>
                                        <!-- Può ancora aggiungerne -->
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <input type="hidden" name="selection" value="<?php echo $nomeValue; ?>"/>
                                            <input type="submit" name="aggiungiAlCarrello" class="bottone-aggiungi" value="Aggiungi (<?php echo $quantitaNelCarrello; ?>/<?php echo $limiteMassimo; ?>)"/>
                                        </form>
                              <?php }  ?>


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
