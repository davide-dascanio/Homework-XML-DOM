<?php
$stile_shop = "
<style type=\"text/css\">


    /* PARTE DI STILE USATO IN shop_xml.php, carrello.php, riepilogo_xml.php e pagamento_xml.php */

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: Arial, sans-serif;
        background: #0f0f10;
        color: #f6f7f9;
    }
    
    /* Navbar dello shop */
    .navbar {
        background: #17181b;
        border-bottom: 1px solid #26272b;
        padding: 15px 30px;
    }
    
    .contenuto-navbar {
        align-items: center;
        justify-content: center;
        max-width: 1300px;
        margin: 0 auto;
        display: flex;
    }
    
    .contenuto-navbar a {
        text-decoration: none;
    }
    
    .navbar h1 {
        color: #2ec4b6;
        font-size: 22px;
        margin-right: 50px;
    }
    
    .link-navbar {
        color: #f6f7f9;
        text-decoration: none;
        margin-right: 20px;
        padding: 8px 15px;
        border-radius: 8px;
        font-weight: bold;
    }
    
    .link-navbar:hover {
        background: #26272b;
    }
    
    /* Container principale */
    .container-principale {
        display: flex;
        max-width: 1300px;
        margin: 30px auto;
        padding: 0 20px;
    }
    
    /* Sidebar profilo utente*/
    .sidebar {
        min-width: 210px;
        max-width: 210px;
        background: #17181b;
        border: 1px solid #26272b;
        border-radius: 12px;
        padding: 25px;
        margin-right: 30px;
        height: fit-content;
    }
    
    .sidebar h2 {
        font-size: 20px;
        margin-bottom: 20px;
        color: #2ec4b6;
    }
    
    .etichetta-sidebar {
        color: #a3a6ad;
        font-size: 13px;
        margin-bottom: 5px;
    }
    
    .valore-sidebar {
        margin-bottom: 15px;
        font-size: 15px;
    }
    






    /* PARTE DI STILE USATO SOLO IN shop_xml.php */
    
    /* Container dei biglietti delle meraviglie */
    .container-meraviglie {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
    }

    /* Scheda meraviglia */
    .scheda-meraviglia {
        background: #17181b;
        border: 1px solid #26272b;
        border-radius: 12px;
        width: 300px;
        display: flex;
        flex-direction: column;
    }
    
    .scheda-meraviglia img {
        width: 100%;
        height: 180px;
        object-fit: cover;  /* controlla il modo in cui un'immagine viene ridimensionata per adattarsi al suo contenitore */
        border-radius: 12px 12px 0 0;
    }
    
    .contenuto-scheda {
        padding: 18px;
        display: flex;
        flex-direction: column;
        flex: 1;   /* fa crescere gli elementi per occupare tutto lo spazio disponibile nel contenitore */
    }
    
    .titolo-scheda {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #f6f7f9;
    }

    .info-scheda {
        display: flex; 
        gap: 10px; 
        margin: 8px 0; 
        flex-wrap: wrap;
    }

    .info {
        background: #26272b; 
        color: #2ec4b6; 
        padding: 4px 10px; 
        border-radius: 5px; 
        font-size: 0.75em; 
        font-weight: bold;
    }

    .descrizione-scheda {
        color: #a3a6ad; 
        font-size: 0.9em; 
        margin: 10px 0; 
        line-height: 1.4;
    }

    .caratt-scheda {
        background: #17181b; 
        padding: 12px; 
        border-radius: 8px; 
        margin: 12px 0; 
        font-size: 0.85em;
    }

    .dettagli {
        color: #2ec4b6; 
        font-weight: bold; 
        margin-bottom: 8px;
    }

    .caratt {
        color: #f6f7f9; 
        margin: 5px 0;
        display: flex;
    }

    .stile-icona {
        color: #2ec4b6; 
        width: 20px;
    }

    .righa-carrello {
        display: flex;
        justify-content: space-between; 
        align-items: center;
        margin-top: auto;
    }
    
    .prezzo-scheda {
        background: #26272b;
        color: #2ec4b6;
        font-size: 16px;
        font-weight: bold;
        padding: 6px 14px;
        border-radius: 6px;
    }
    
    .bottone-aggiungi {
        background: #2ec4b6;
        color: #0f2420;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        font-size: 14px;
        padding: 8px 16px;
        cursor: pointer;
    }
    
    .bottone-aggiungi:hover {
        filter: brightness(1.1);  /* aumenta la luminosità di un elemento del 10% rispetto all'immagine originale */
    }

    .bottone-esaurito {
        background: #e74c3c; 
        color: #ecf0f1; 
        border: none; 
        border-radius: 6px; 
        font-weight: bold; 
        font-size: 13px; 
        padding: 8px 12px; 
        cursor: not-allowed; 
        opacity: 0.6;
    }

    .bottone-limitato {
        background: #f39c12; 
        color: white; 
        border: none; 
        border-radius: 6px; 
        font-weight: bold; 
        font-size: 13px; 
        padding: 8px 12px; 
        cursor: not-allowed; 
        opacity: 0.8;
    }
















    /* PARTE DI STILE USATO SOLO IN shop_xml.php e carrello.php */

    /* Notifica carrello */
    .messaggio-aggiunto {
        background: #27ae60;
        color: white;
        padding: 15px 20px;
        margin: 20px auto 20px auto;
        max-width: 1200px;
        border-radius: 8px;
        text-align: center;
    }

    .messaggio-aggiunto p {
        margin: 0 0 10px 0;
        padding: 10px 0 0 0;
        font-size: 16px;
    }

    .messaggio-aggiunto strong {
        font-size: 18px;
    }
    
    .messaggio-aggiunto a {
        color: white;
    }

    .messaggio-aggiunto a:hover {
        color: #ecf0f1;
    }









    

    /* PARTE DI STILE USATO SOLO IN carrello.php */

    .container-carrello {
        flex: 1;           /* fa crescere gli elementi per occupare tutto lo spazio disponibile nel contenitore */
    }

    .titolo-carrello {
        font-size: 28px;
        color: #f6f7f9;
        margin-bottom: 30px;
    }

    .carrello-vuoto {
        background: #17181b;
        border: 1px solid #26272b;
        border-radius: 12px;
        padding: 60px;  
        text-align: center;
    }

    .carrello-vuoto p {
        font-size: 20px;
        color: #a3a6ad;
        margin-bottom: 30px;
    }

    .carrello-vuoto a {
        background: #27ae60;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        font-size: 15px;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
    }

    .carrello-vuoto a:hover {
        background: #229954;
    }

    .carrello-pieno {
        background: #17181b;
        border: 1px solid #26272b;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .articolo-carrello {
        padding: 15px;
        border-bottom: 1px solid #26272b;
        display: flex;
        align-items: center;
    }
    
    input[type='checkbox'] {
        width: 20px;
        height: 20px;
        margin-right: 15px;
        cursor: pointer;
    }

    .container-pulsanti {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .bottone-rosso1 {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: bold;
        cursor: pointer;
    }

    .bottone-rosso1:hover {
        background: #c0392b;
    }

    .bottone-rosso2 {
        background: #0f0f10;
        color: #e74c3c;
        border: 2px solid #e74c3c;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: bold;
        cursor: pointer;
    }

    .bottone-rosso2:hover {
        background: #e74c3c;
        color: white;
    }

    .bottone-bianco {
        background: white;
        color: #0f0f10;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: bold;
        cursor: pointer;
    }

    .bottone-bianco:hover {
        background: #CCCCCC;
    }

    .bottone-grigio {
        background: #95a5a6;
        color: white;
        border: none;
        padding: 14px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        margin-left: 281px;
    }

    .bottone-grigio:hover {
        background: #7f8c8d;
    }

    .bottone-acqua {
        background: #2ec4b6;
        color: #0f2420;
        border: none;
        padding: 14px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        margin-left: auto;
    }

    .bottone-acqua:hover {
        filter: brightness(1.1);
    }











    /* PARTE DI STILE USATO SOLO IN riepilogo_xml.php */

    .container-riepilogo {
        flex: 1;
    }

    .titolo-riepilogo {
        font-size: 28px;
        color: #f6f7f9;
        margin-bottom: 30px;
    }

    .riepilogo-vuoto {
        background: #17181b;
        border: 1px solid #26272b;
        border-radius: 12px;
        padding: 60px;  
        text-align: center;
    }

    .riepilogo-vuoto p {
        font-size: 20px;
        color: #a3a6ad;
        margin-bottom: 30px;
    }

    .riepilogo-vuoto a {
        background: #27ae60;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        font-size: 15px;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
    }

    .riepilogo-vuoto a:hover {
        background: #229954;
    }

    .riepilogo-pieno {
        background: #17181b;
        border: 1px solid #26272b;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .articolo-riepilogo {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #26272b;
    }

    .immagine-articolo {
        width: 80px;
        height: 80px;
        object-fit: cover;    /* immagine viene ridimensionata per adattarsi al suo contenitore */
        border-radius: 8px;
        margin-right: 20px;
    }

    .info-articolo {
        flex: 1;
    }

    .nome-articolo {
        font-size: 16px;
        font-weight: bold;
        color: #f6f7f9;
    }

    .prezzo-articolo {
        font-size: 18px;
        font-weight: bold;
        color: #2ec4b6;
        background: #26272b;
        padding: 8px 16px;
        border-radius: 6px;
    }

    .container-prezzoTot {
        background: #17181b;
        border: 2px solid #2ec4b6;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .titolo-totale {
        font-size: 22px;
        font-weight: bold;
        color: #f6f7f9;
    }

    .valore-totale {
        font-size: 28px;
        font-weight: bold;
        color: #2ec4b6;
    }
    
    .container-bottoni {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .bottone-grey {
        background: #95a5a6;
        color: white;
        border: none;
        padding: 14px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        margin-left: auto;
    }

    .bottone-grey:hover {
        background: #7f8c8d;
    }
    
    .bottone-verde {
        background: #27ae60;
        color: white;
        border: none;
        padding: 14px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
    }

    .bottone-verde:hover {
        background: #c0392b;
    }













    /* PARTE DI STILE USATO SOLO IN pagamento_xml.php */
    
    .container-pagamento {
        flex: 1;
    }

    .container-successo {
        background: #17181b;
        border: 2px solid #27ae60;
        border-radius: 12px;
        padding: 50px;
        text-align: center;
        margin-bottom: 30px;
    }

    .icona-successo {
        background: #27ae60;
        color: white;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: bold;
        margin: 0 auto 20px auto;
    }

    .titolo-successo {
        font-size: 32px;
        color: #27ae60;
        margin-bottom: 15px;
    }

    .testo-successo {
        font-size: 18px;
        color: #a3a6ad;
        margin-bottom: 30px;
    }

    .dettaglio-spesa {
        background: #26272b;
        border-radius: 8px;
        padding: 20px;
    }

    .importo-ora {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 16px;
        color: #f6f7f9;
    }

    .importo-ora strong {
        color: #2ec4b6;
    }

    .spesa-totale {
        display: flex;
        justify-content: space-between;
        border-top: 2px solid #2ec4b6;
        margin-top: 10px;
        padding-top: 15px;
        font-size: 18px;
    }

    .spesa-totale strong {
        color: #2ec4b6;
    }

    .container-errore {
        background: #17181b;
        border: 2px solid #e74c3c;
        border-radius: 12px;
        padding: 50px;
        text-align: center;
        margin-bottom: 30px;
    }

    .icona-errore {
        background: #e74c3c;
        color: white;
        width: 80px;
        height: 80px;
        border-radius: 50%;   /* questo è un cerchio */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: bold;
        margin: 0 auto 20px auto;
    }

    .titolo-errore {
        font-size: 32px;
        color: #e74c3c;
        margin-bottom: 15px;
    }

    .testo-errore {
        font-size: 18px;
        color: #a3a6ad;
    }
    
    .container-azioni {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
    }

    .bottone-back {
        background: #95a5a6;
        color: white;
        padding: 14px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
    }

    .bottone-back:hover {
        background: #7f8c8d;
    }
    
    .bottone {
        background: #2ec4b6;
        color: #0f2420;
        border: none;
        padding: 14px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
    }

    .bottone:hover {
        filter: brightness(1.1);
    }

    
</style>
";
?>
