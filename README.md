# Homework-XML-DOM
Il progetto è un'applicazione web e-commerce per l'acquisto di biglietti virtuali delle Sette Meraviglie del Mondo Moderno. A differenza del progetto precedente (Homework-PHP-MySQL),
questo sistema utilizza XML/DOM per la gestione del catalogo biglietti e degli ordini effettuati dagli utenti (parte nuova del progetto, implementata in questo terzo homework), 
mantenendo MySQL solo per la gestione utenti e tracciamento delle spese. Il progetto implementa validazione DTD, manipolazione DOM e parsing XML. Questo è composto da più script:

- connessione1.php: File di connessione standard al database MySQL utilizzato dopo che il database è stato creato. Contiene le credenziali per connettersi al database 7MeraviglieDB
già esistente. Il file definisce parametri come hostname, username, password e nome del database, ed è richiamato da tutte le pagine che necessitano di accesso al database
per gestione utenti (login.php, registrazione.php, pagamento_xml.php).

- connessione2.php: File di connessione speciale utilizzato esclusivamente durante la fase di installazione iniziale, ovvero solo in install.php. Questo file si connette al server MySQL
senza specificare un database particolare e contiene la query SQL per la creazione automatica del database 7MeraviglieDB. Dopo aver creato il database, chiude la connessione e
richiama connessione1.php per stabilire la connessione al database appena creato.

- install.php: Script di inizializzazione completa del database che viene eseguito una sola volta. Richiama connessione2.php per creare il database e successivamente:​
  - Crea la tabella Utenti con campi: userId (auto-increment), nome, cognome, username (UNIQUE), password e sommeSpese​;
  - Popola automaticamente la tabella Utenti con due account di test (Mario Rossi e Luigi Verdi);​
  - Dopo l'esecuzione di questo file, il sistema è pronto per l'uso.
 
  Differenza chiave rispetto al progetto precedente: NON crea la tabella Biglietti in MySQL, poiché i dati del catalogo sono gestiti interamente dal file XML data.xml.

- index.php: Pagina di benvenuto del sito che presenta il progetto, che servirà come pagina introduttiva dei 7 monumenti, e come riferimento alle varie pagine web che sono state create
per ognuna delle sette meraviglie, inoltre include il menu di navigazione principale (menu_home.php).

- menu_home.php: Menu di navigazione per la pagina index.php che permette di accedere alle pagine di login e registrazione o di acquistare i biglietti (si passa sempre per il login/registrazione).

- pagina1.php, ..., pagina6.php, pagina7.php: Pagine informative dedicate alla descrizione delle 7 specifiche meraviglie del mondo. Queste inoltre includono il menu di navigazione
(menu_meraviglie.php).

- menu_meraviglie.php: Menu importato dalle pagine informative (pagina1.php, ecc..) che permette di navigare tra le diverse pagine di approfondimento, tornare alla home principale
(index.php) e anche qui di accedere/registrarsi/acquistare biglietti.

- style.css: Regole di stile CSS utilizzate in index.php e in tutte le pagine informative.

- script.js: File JavaScript che gestisce l'interattività del menu overlay full-screen.

- login.php: Form di accesso al sistema che verifica username e password inseriti confrontandoli con i dati nel database. In caso di login corretto, inizializza le variabili di
sessione (nome, cognome, username, spesa finora) e reindirizza alla pagina shop_xml.php. Gestisce anche messaggi di errore per credenziali errate. Utilizza lo stile definito in stile_autenticazione.php.

- registrazione.php: Form per la creazione di nuovi account utente. Raccoglie nome, cognome, username e password, verificando che lo username scelto non sia già presente nel database prima di
procedere con l'inserimento. In caso di successo, reindirizza l'utente alla pagina login.php. Utilizza lo stile definito in stile_autenticazione.php.

- logout.php: Pagina che gestisce la disconnessione dell'utente distruggendo la sessione attiva. Mostra un messaggio di conferma logout avvenuto con successo e fornisce
link per tornare alla home o al login. Utilizza lo stile definito in stile_autenticazione.php.

- stile_autenticazione.php: Script in cui si definisce una variabile $stile_autenticazione con le regole CSS dedicate alle pagine di autenticazione (login, registrazione, logout).
Definisce lo stile per vari elementi.

- data.xml: File XML che sostituisce completamente la tabella Biglietti del database MySQL. Contiene il catalogo completo dei biglietti delle Sette Meraviglie con struttura gerarchica:
elemento radice <'catalogo'>, ogni <'biglietto'> ha attributi (id, categoria, disponibilità, lingua) e sottoelementi obbligatori (<'nome'>, <'prezzo'>, <'descrizione'>, <'caratteristiche'>).
Il file viene letto e parsato con DOM in shop_xml.php per la visualizzazione del catalogo. Validato tramite DTD esterno (data.dtd).

- data.dtd: Document Type Definition che definisce la struttura e le regole di validazione per data.xml. Specifica gli elementi ammessi
(biglietto, nome, prezzo, descrizione, caratteristiche, durata, guida, accessibilità), i tipi di attributi (id, categoria, disponibilità, lingua) e le relazioni gerarchiche tra elementi.
Permette di garantire che il file XML rispetti sempre la struttura sintattica (grammatica) corretta prima di lettura/scrittura.

- ordini.xml: File XML dinamico che memorizza lo storico completo degli ordini effettuati dagli utenti, sostituendo una potenziale tabella Ordini in MySQL. Struttura gerarchica:
elemento radice <'ordini'>, ogni <'ordine'> ha attributo id e sottoelementi obbligatori <'username'>, <'date'>, <'totale'>, <'articoli'>
(contenitore per uno o più <'articolo'>, ciascuno con <'biglietto'>, <'prezzo'>, <'quantita'>).
Il file viene creato dinamicamente se non esiste, letto per visualizzare lo storico in ordini_utente.php, e aggiornato tramite manipolazione DOM in pagamento_xml.php ad ogni nuovo ordine completato.
Il file viene inoltre letto per gestire, in shop_xml.php, la disponibilità dei biglietti limitati, andando a contare il numero di biglietti limitati che sono stati già acquistati in passato da un determinato
utente e andando quindi disabilitare il bottone di aggiunta al carrello nel momento in cui si fosse raggiunto il limite di acquisto su quel biglietto.
I nuovi ordini vengono inseriti in testa (insertBefore) per mantenere l'ordine cronologico inverso.

- ordini.dtd: Document Type Definition per la validazione di ordini.xml. Definisce la struttura degli ordini e le relazioni gerarchiche tra elementi.
Garantisce integrità strutturale del file ordini.xml prima di lettura/scrittura.

- shop_xml.php: Pagina principale del catalogo che legge e visualizza i biglietti da data.xml utilizzando PHP DOM. Procedimento in generale:
carica il file XML con file() e utilizza trim per rimuovere spazi, crea oggetto DOMDocument, valida con validate() contro il DTD, estrae la lista degli elementi figli della radice del documento XML data.xml
con documentElement->childNodes, cicla sui nodi traversando la struttura (firstChild/nextSibling) per leggere attributi (getAttribute()) e contenuti testuali (textContent). Ogni biglietto è presentato
in una scheda con immagine, nome della meraviglia, informazioni generali e prezzo.
Gestisce biglietti limitati contando: 1) quanti ne sono già nel carrello sessione; 2) quanti ne ha già acquistati l'utente leggendo ordini.xml con getElementsByTagName().
Se il totale raggiunge il limite (es. 2), il bottone "Aggiungi" diventa disabilitato.
Mostra sidebar profilo con validazione DTD, include il menu di navigazione dello shop (menu_shop.php) e lo stile condiviso (stile_shop.php). Le immagini sono gestite tramite array associativo PHP.

- carrello.php: Pagina di gestione del carrello che mostra tutti i biglietti aggiunti dall'utente. Permette di:​
  - Visualizzare la lista completa degli articoli nel carrello con checkbox di selezione​;
  - Eliminare singoli biglietti selezionati​;
  - Svuotare completamente il carrello​;
  - Deselezionare gli articoli selezionati;
  - Continuare con l'acquisto di articoli (shop_xml.php);
  - Procedere al riepilogo d'acquisto (riepilogo_xml.php)​.
  
  Gli articoli nel carrello sono gestiti tramite la variabile di sessione $_SESSION['carrello'], nel caso in cui non ci siano articoli nel carrello viene mostrato un pulsante per tornare al catalogo.
  Viene mostrata la sidebar profilo, include (menu_shop.php) e lo stile condiviso (stile_shop.php).

- riepilogo_xml.php: Pagina di riepilogo pre-acquisto che mostra i dettagli completi dei biglietti selezionati leggendo i prezzi aggiornati da data.xml tramite DOM. Procedimento:
carica il file XML con file() e utilizza trim, crea oggetto DOMDocument e valida il parsing; cicla sui nodi <'biglietto'> della radice <'catalogo'> traversando il documento
(firstChild per <nome>, nextSibling per <prezzo>); costruisce una mappa associativa $catalogoMap che associa ogni nome biglietto al suo prezzo letto dall'XML.
Conta le occorrenze di ogni biglietto nel carrello usando array_count_values() per gestire quantità multiple dello stesso articolo, recupera il prezzo dalla mappa e l'immagine dall'array statico $immagini.
Calcola il subtotale (prezzo × quantità) per ogni articolo e accumula il totale generale in $_SESSION['daPagare']. Costruisce array $articoliRaggruppati con tutte le informazioni
(nome, prezzoUnitario, quantità, subtotale, immagine) per la visualizzazione raggruppata. Mostra gli articoli in card con immagini, visualizza la formula "quantità × prezzo = subtotale",
il totale finale da pagare, e fornisce pulsanti per modificare il carrello (carrello.php), continuare con gli acquisti (shop_xml.php) o procedere al pagamento (pagamento_xml.php).
Viene mostrata la sidebar profilo, include (menu_shop.php) e lo stile condiviso (stile_shop.php).

- pagamento_xml.php: Pagina che gestisce il completamento dell'acquisto con doppio salvataggio: MySQL per spese utente + XML per storico ordini. Procedimento:
  1) Quando si arriva da riepilogo_xml.php senza POST conferma, mostra schermata di conferma con importo da pagare, form con pulsante "Conferma Pagamento"
     (invia conferma=true via POST) e pulsante "Annulla" per tornare allo shop;
  2) Legge data.xml per ottenere prezzi aggiornati e calcolare totale;
  3) Aggiorna campo sommeSpese dell'utente in MySQL con query UPDATE;
  4) Crea/modifica ordini.xml con manipolazione DOM: se il file non esiste, usa DOMImplementation()->createDocumentType() per creare DOCTYPE con riferimento a ordini.dtd, poi
     createDocument() per creare struttura iniziale; se esiste, carica con loadXML() e valida;
  5) Genera ID ordine con rand();
  6) Crea nuovo nodo <ordine> con createElement(), imposta attributo id con setAttribute();
  7) Crea e popola sottoelementi (username, date, totale, articoli) usando createElement() e createTextNode();
  8) Per ogni articolo nel carrello crea struttura <'articolo'> con <'biglietto'>, <'prezzo'>, <'quantita'>;
  9) Inserisce ordine in testa con insertBefore;
  10) Salva modifiche con save('ordini.xml');
  11) Se salvataggio XML fallisce, non esegue UPDATE MySQL; se UPDATE fallisce, rimuove ordine appena aggiunto dall'XML con removeChild().
      Mostra messaggio successo/errore, svuota carrello, aggiorna $_SESSION['spesaFinora']. Viene mostrata la sidebar profilo, include (menu_shop.php) e lo stile condiviso (stile_shop.php).

- ordini_utente.php: Pagina per visualizzare lo storico ordini dell'utente corrente leggendo da ordini.xml. Utilizza DOM per: caricare e validare il file XML, navigare con loop for su
documentElement->childNodes, filtrare ordini per username con firstChild->textContent, leggere data e totale con navigazione nextSibling, estrarre articoli con loop annidato su
childNodes del nodo <'articoli'>, popolare array associativo $userOrdini con struttura: id, date, totale, articoli[] (ciascuno con biglietto, prezzo, quantità, subtotale calcolato).
Visualizza ordini, mostra quantità e subtotale per articolo, calcola e mostra totale speso complessivo da $_SESSION['spesaFinora'].
Viene mostrata la sidebar profilo con validazione DTD, include (menu_shop.php) e lo stile condiviso (stile_shop.php).

- menu_shop.php: Menu di navigazione importato dalle pagine autenticate accessibili dopo il login, che permette di andare:
  - alla pagina di benvenuto del sito cliccando su "Le Sette Meraviglie" (index.php);
  - al catalogo/shop (shop_xml.php);
  - alla visualizzione del carrello (carrello.php);
  - alla pagina di riepilogo pre-acquisto degli articoli (pagamento_xml.php);
  - alla pagina dello storico ordini (ordini_utente.php);
  - alla pagina di logout (logout.php).
 
  Questo menu è presente in tutte le pagine operative del sito, quindi (shop, carrello, riepilogo, ordini utente, pagamento).

- stile_shop.php: Script in cui si definisce una variabile $stile_shop con le regole CSS principali condivise tra tutte le pagine operative. Definisce lo stile per tutte gli
elementi presenti nelle pagine, come lo stile per la sidebar, la barra di navigazione, form, tutti i vari layout strutturali, ecc..



Gli stili vengono importati all'interno dei diversi documenti tramite la funzione require_once(). Gli stili vengono applicati stampando nella head la variabile definita nell'apposito script.

I menu vengono importanti all'interno dei diversi documenti tramite la funzione require().

Per la visualizzazione delle icone nei pulsanti e negli elementi dell'interfaccia, il progetto utilizza Font Awesome 6.0, una libreria di icone. Per utilizzare le icone Font Awesome
nel progetto, è necessario includere il file CSS della libreria nell'header di ogni pagina. Questo si fa aggiungendo un link nella sezione <head> degli script.





