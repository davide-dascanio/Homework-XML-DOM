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
sessione (nome, cognome, username, spesa finora) e reindirizza alla pagina shop.php. Gestisce anche messaggi di errore per credenziali errate. Utilizza lo stile definito in stile_autenticazione.php.

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
Mostra sidebar profilo, include il menu di navigazione dello shop (menu_shop.php) e lo stile condiviso (stile_shop.php). Le immagini sono gestite tramite array associativo PHP.

- carrello.php: Pagina di gestione del carrello che mostra tutti i biglietti aggiunti dall'utente. Permette di:​
  - Visualizzare la lista completa degli articoli nel carrello con checkbox di selezione​;
  - Eliminare singoli biglietti selezionati​;
  - Svuotare completamente il carrello​;
  - Deselezionare gli articoli selezionati;
  - Continuare con l'acquisto di articoli (shop_xml.php);
  - Procedere al riepilogo d'acquisto (riepilogo_xml.php)​.
  
  Gli articoli nel carrello sono gestiti tramite la variabile di sessione $_SESSION['carrello'], nel caso in cui non ci siano articoli nel carrello viene mostrato un pulsante per tornare al catalogo.
  Viene mostrata la sidebar profilo, include (menu_shop.php) e lo stile condiviso (stile_shop.php).














