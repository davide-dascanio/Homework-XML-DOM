<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    session_start();

    // Controllo accesso
    if (!isset($_SESSION['accessoPermesso']))
        header('Location: login.php');


    // Connessione al database
    require_once("./connessione1.php");


    require_once("./stile_shop.php");

    // Array immagini
    $immagini = array(
        "Grande Muraglia Cinese, Cina" => "./file/collegamento_1/img/muraglia.jpg", 
        "Petra, Giordania" => "./file/collegamento_2/img/petra.jpg", 
        "Cristo Redentore, Rio de Janeiro" => "./file/collegamento_3/img/redentore2.jpg",
        "Machu Picchu, Cusco - Perù" => "./file/collegamento_4/img/machu_picchu.jpg", 
        "Chichén Itzá, Yucatàn - Messico" => "./file/collegamento_5/img/chichen_itza.jpg", 
        "Colosseo, Roma - Italia" => "./file/collegamento_6/img/colosseo-roma.jpg", 
        "Taj Mahal, Agra - India" => "./file/collegamento_7/img/taj_mahal.jpg"
    );

    // Calcola il totale da pagare
    $_SESSION['daPagare'] = 0; // Parte da 0, poi sommiamo i prezzi
    $biglietti = array(); // Inizializzazione: array vuoto []

    foreach ($_SESSION['carrello'] as $indice => $nomeBiglietto) {
        // Query per ogni biglietto
        $sql = "SELECT * 
                FROM $Biglietti_table_name
                WHERE nome = \"$nomeBiglietto\"";
        
        if (!$resultQ = mysqli_query($mysqliConnection, $sql)) {
            printf("Si è verificato un errore nella selezione.\n");
            exit();
        }
        
        $row = mysqli_fetch_array($resultQ);
        
        // Accumula il totale
        $_SESSION['daPagare'] += $row['costoBiglietto'];
        
        // --- AGGIUNGIAMO AL NOSTRO ARRAY ---
        // $biglietti[] significa: aggiungi un elemento ALLA FINE dell'array
        // array associativo con 3 chiavi: nome, prezzo, immagine
        $biglietti[] = array(
            'nome' => $row['nome'],
            'prezzo' => $row['costoBiglietto'],
            'immagine' => isset($immagini[$row['nome']]) ? $immagini[$row['nome']] : 'bianco.jpg'
        );
    }

    // Chiudi la connessione
    $mysqliConnection->close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Riepilogo Acquisti - Le Sette Meraviglie</title>
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

            <!-- Contenuto riepilogo -->
            <div class="container-riepilogo">
                <h2 class="titolo-riepilogo">Riepilogo Acquisti</h2>

                <?php
                    if(empty($_SESSION['carrello'])){
                ?>  
                        <div class="riepilogo-vuoto">
                            <p>Il tuo carrello è vuoto</p>
                            <a href="shop.php">Vai al Catalogo</a>
                        </div>
                        <?php 
                    }else{
                ?>
                        <div class="riepilogo-pieno">
                            <?php
                                //print_r($biglietti);
                                foreach ($biglietti as $k => $biglietto){ 
                            ?>
                                    <div class="articolo-riepilogo">
                                        <img src="<?php echo $biglietto['immagine']; ?>" alt="<?php echo $biglietto['nome']; ?>" class="immagine-articolo" />
                                        <div class="info-articolo">
                                            <div class="nome-articolo"><?php echo $biglietto['nome']; ?></div>
                                        </div>
                                        <div class="prezzo-articolo"><?php echo $biglietto['prezzo']; ?> &euro; </div>
                                    </div>
                                    <?php 
                                }
                            ?>
                        </div>
                        <div class="container-prezzoTot">
                            <div class="titolo-totale">Totale da pagare:</div>
                            <div class="valore-totale"><?php echo $_SESSION['daPagare']; ?> &euro;</div>
                        </div>

                        <form action="pagamento.php" method="post">
                            <div class="container-bottoni">
                                <a href="shop.php" class="bottone-grey">Continua con gli acquisti</a>
                                <input type="submit" name="invioPagamento" value="Procedi con il pagamento" class="bottone-verde" />
                            </div>
                        </form>
                        <?php 
                    }
                ?>
            </div>
        </div>
    </body>
</html>
