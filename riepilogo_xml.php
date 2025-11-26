<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    // Controllo accesso
    if (!isset($_SESSION['accessoPermesso'])) {
        header("Location: login.php");
        exit();
    }

    require_once('./stile_shop.php');

?>

<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>Riepilogo - Le Sette Meraviglie</title>
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

        <div class="etichetta-sidebar">Articoli nel carrello:</div>
        <div class="valore-sidebar"><?php echo count($_SESSION['carrello']); ?></div>
    </div>
    
    <!-- Container riepilogo -->
    <div class="container-riepilogo">
        <h2 class="titolo-riepilogo">Riepilogo Ordine</h2>

        <?php if(empty($_SESSION['carrello'])){   ?>
                <div class="riepilogo-vuoto">
                    <p>Il tuo carrello è vuoto</p>
                    <a href="shop_xml.php">Vai al Catalogo</a>
                </div>
        <?php }else{ ?>
                <?php
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

                    
                    // Crea una mappa del tipo:  nome → prezzo  dal DOM
                    $catalogoMap = array();

                    for($i=0; $i<$biglietti->length; $i++){
                        $biglietto = $biglietti->item($i);

                        $nome = $biglietto->firstChild;
                        $nomeValue = $nome->textContent;
                        $prezzo = $nome->nextSibling;   // nextSibling ci porta al prossimo sottoelemento
                        $prezzoValue = $prezzo->textContent;
                        
                        $catalogoMap[$nomeValue] = $prezzoValue;
                    }

                    // Calcola il totale da pagare
                    $_SESSION['daPagare'] = 0;
                    // Raggruppa gli articoli uguali
                    $articoliRaggruppati = array(); // Array per raggruppare

                    // Conta le occorrenze di ogni biglietto nel carrello
                    // Avremo una cosa del genere: Array ( [Grande Muraglia, Cina] => 2 [Cristo Redentore, Rio de Janeiro] => 2 )
                    $conteggioCarrello = array_count_values($_SESSION['carrello']);
                    //print_r($conteggioCarrello);

                    foreach ($conteggioCarrello as $nomeBiglietto => $quantita) {
                        if (isset($catalogoMap[$nomeBiglietto])) {
                            $prezzoUnitario = $catalogoMap[$nomeBiglietto];
                            $subtotale = $prezzoUnitario * $quantita;
                            
                            // Accumula totale generale
                            $_SESSION['daPagare'] += $subtotale;
                            
                            // Aggiungi all'array raggruppato
                            $articoliRaggruppati[] = array(
                                'nome' => $nomeBiglietto,
                                'prezzoUnitario' => $prezzoUnitario,
                                'quantita' => $quantita,
                                'subtotale' => $subtotale,
                                'immagine' => isset($immagini[$nomeBiglietto]) ? $immagini[$nomeBiglietto] : 'bianco.jpg'
                            );
                        }
                    }
                    //print_r($_POST);
                ?>
                <div class="riepilogo-pieno">
                    <?php foreach ($articoliRaggruppati as $k => $articolo){  ?>
                            <div class="articolo-riepilogo">
                                <img src="<?php echo $articolo['immagine']; ?>" alt="<?php echo $articolo['nome']; ?>" class="immagine-articolo"/>
                                <div class="info-articolo">
                                    <div class="nome-articolo"><?php echo $articolo['nome']; ?></div>
                                    <div class="prezzo-articolo">
                                        <?php echo $articolo['quantita']; ?> x <?php echo $articolo['prezzoUnitario']; ?> &euro; = 
                                        <span style="color: #2ec4b6;"><?php echo $articolo['subtotale']; ?> &euro;</span>
                                    </div>
                                </div>
                            </div>
                    <?php }  ?>
                </div>
                <div class="container-prezzoTot">
                    <div class="titolo-totale">Totale da pagare:</div>
                    <div class="valore-totale"><?php echo $_SESSION['daPagare']; ?> &euro;</div>
                </div>
                
                <form action="pagamento_xml.php" method="post">
                    <div class="container-bottoni">
                        <a href="shop_xml.php" class="bottone-grey" style="margin-left: auto">Continua acquisti</a>
                        <a href="carrello.php" class="bottone-grey">Modifica carrello</a>
                        <input type="submit" name="invioPagamento" value="Procedi con il pagamento" class="bottone-verde" />
                    </div>
                </form>
        <?php }  ?>
    </div>
</div>
</body>
</html>
