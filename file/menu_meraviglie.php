<!-- Codice HTML/JavaScrpit per la creazione della barra di navigazione delle pagine descrittive delle 7 meraviglie (pagina1.php, pagina2.php, ...) -->
<div class="nav">
    <ul>
        <li><a class="active" href="../../index.php">HOME</a></li>
        <li class="posizione">
            <a href="javascript:void(0)" style="cursor:pointer" onclick="apriNavFull()"> VEDI GLI ALTRI MONUMENTI </a>
        </li>
        <li> 
            <a href="../../login.php"><i class="fas fa-user"></i> ACCEDI/REGISTRATI </a>
        </li>
        <li>
            <a href="../../shop.php"><i class="fas fa-shopping-bag"></i> ACQUISTA I BIGLIETTI </a>
        </li>
    </ul>
</div>
<div id="navigatore" class="sovrapposizione">
    <a href="javascript:void(0)" class="chiusuraNav" onclick="chiudiNavFull()">&times;</a>
    <div class="sovrapposizione-cont">
        <a href="../../index.php">HOME</a>
        <a href="../collegamento_1/pagina1.php">Grande Muraglia Cinese, Cina </a>
        <a href="../collegamento_2/pagina2.php">Petra, Giordania </a>
        <a href="../collegamento_3/pagina3.php">Cristo Redentore, Rio de Janeiro </a>
        <a href="../collegamento_4/pagina4.php">Machu Picchu, Cusco - Per&ugrave; </a>
        <a href="../collegamento_5/pagina5.php">Chich&eacute;n Itz&aacute;, Yucat&agrave;n - Messico </a>
        <a href="../collegamento_6/pagina6.php">Colosseo, Roma - Italia </a>
        <a href="../collegamento_7/pagina7.php">Taj Mahal, Agra - India </a>
    </div>
</div>