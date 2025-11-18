<?php
$stile_autenticazione = "
<style type=\"text/css\">

    /* stile_autenticazione.php viene require_once in login.php, logout.php e registrazione.php */

    body {
        font-family: Arial, sans-serif;
        background: #2c3e50;
        margin: 0;
        padding: 50px 20px;
    }
    
    h3 {
        color: #ecf0f1;
        text-align: center;
        font-size: 32px;
        margin-bottom: 30px;
    }
    
    form {
        background: white;
        max-width: 400px;
        margin: 0 auto;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    p {
        margin-bottom: 20px;
        color: #34495e;
        font-size: 15px;
    }
    
    input[type='text'],
    input[type='password'] {
        width: 100%;
        padding: 12px;
        border: 2px solid #bdc3c7;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box;
    }
    
    input[type='text']:focus,
    input[type='password']:focus {
        border-color: #3498db;
        outline: none;
    }
    
    input[type='submit'],
    input[type='reset'] {
        padding: 12px 30px;
        margin: 10px 5px 0 0;
        border: none;
        border-radius: 5px;
        font-size: 15px;
        cursor: pointer;
        font-weight: bold;
    }
    
    input[type='submit'] {
        background: #27ae60;
        color: white;
    }
    
    input[type='submit']:hover {
        background: #229954;
    }
    
    input[type='reset'] {
        background: #95a5a6;
        color: white;
    }
    
    input[type='reset']:hover {
        background: #7f8c8d;
    }
    
    em {
        display: block;
        text-align: center;
        color: #e74c3c;
        background: #fadbd8;
        padding: 15px;
        border-radius: 5px;
        margin: 0 auto 20px;
        max-width: 400px;
    }





    /* parte di stile che rigurda solo login.php */


    .sezione-finale-login {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #ecf0f1;
    }
    
    .sezione-finale-login p {
        color: #7f8c8d;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .sezione-finale-login a {
        display: inline-block;
        padding: 12px 40px;
        background: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 15px;
        font-weight: bold;
        transition: background 0.3s ease;
    }
    
    .sezione-finale-login a:hover {
        background: #2980b9;
    }





    /* parte di stile che rigurda solo registrazione.php */


    .messaggio-successo {
        display: block;
        text-align: center;
        background: #fadbd8;
        padding: 15px;
        border-radius: 5px;
        margin: 0 auto 20px;
        max-width: 400px;
    }

    .messaggio-successo a {
        color: #2ec4b6;
        text-decoration: underline;
        font-weight: bold;
    }

    .ultima-p {
        text-align: center; 
        margin-top: 20px; 
        color: #a3a6ad;
    }

    .ultima-p a {
        color: #2ec4b6;
    }





    /* parte di stile che rigurda solo logout.php */


    .stile-logout {
        background: white;
        max-width: 400px;
        margin: 0 auto;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        text-align: center;
    }

    .stile-logout p {
        color: #7f8c8d;
        font-size: 16px;
    }

    .bottone a {
        display: inline-block;
        padding: 12px 30px;
        margin: 10px 5px 0 0;
        border-radius: 5px;
        font-size: 15px;
        font-weight: bold;
        text-decoration: none;
        color: white;
    }

    .bottone-verde {
        background: #27ae60;
    }
    
    .bottone-verde:hover {
        background: #229954;
    }
    
    .bottone-grigio {
        background: #95a5a6;
    }
    
    .bottone-grigio:hover {
        background: #7f8c8d;
    }
    
</style>
";
?>
