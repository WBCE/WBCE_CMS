<section class="help">
    <h1><img src="<?=get_url_from_path(dirname(__DIR__))?>/img/droplets_logo.png" alt="Droplets"></h1>
    <div class="authors">
        <p><span>Programmiert von</span></p>
             <p><b>Initiatoren:</b> Ruud (<a href="https://dev4me.com/">Dev4me</a>) und John (PCWacht)<br>
            <b>Weitergeführt von:</b> Bianka Martinovic (WebBird), cwsoft, NorHei, Bernd, <br>Colinax, Christian M. Stefan (Stefek)<br>

        </p>
    </div>

    
<h2>Einführung</h2> 
<p>Droplets sind kleine PHP Funkionen, die mithilfe von Droplet Aufrufen im Template oder an anderen Stellen eingebunden und ausgeführt werden können.</p>

<h2>Droplets aufrufen</h2> 
<p>Der Droplet Aufruf wird gesetzt indem man den Namen des Droplets in doppelten eckigen Klammern einkapselt.</p>
<p>Z.B. wenn Du das Droplet "ModifiedWhen" einbinden willst 
    (um auszugeben wann die Seite zuletzt geändert wurde) brauchst Du nur:</p> 
    
    <pre>[[ModifiedWhen]] <i style="color: #f1b04d">    // Ein Droplet Aufruf</i></pre>
    <p>in Deinem Template oder im WYSIWYG Modul einzutragen.</p>
    
<h2>Die Ausführung eines Droplets unterbinden</h2> 
<p>Wenn ein zuvor platzierter Droplet Aufruf (vorrübergehend) von der Ausführung ausgeschlioßen werden soll, setze ein # Zeichen zwischen den öffnenden eckicken Klammern, wie im folgenden Beispiel:</p>
<pre>[<b>#</b>[ModifiedWhen]] <i style="color: #f1b04d">    // Droplet auskommentieren</i></pre>
<p>Das sorgt dafür, dass an dieser Stelle kein Code ausgeführt wird. </p>

<h2>Ausführung unterbinden aber Droplet Aufruf an der Stelle anzeigen</h2> 
<p>Wenn der PHP Code des Droplets nicht ausgegeben werden soll, aber der Aufruf angezeigt werden soll, verwende:</p>
<pre>[<b>\\</b>[ModifiedWhen]] <i style="color: #f1b04d">    // Droplet auskommentieren aber Aufruf anzeigen</i></pre>
<p>Auf diese Weise wird kein Code ausgeführt aber an der Stelle wird [<b>\\</b>[ModifiedWhen]] angezeigt, was manchmal praktisch sein kann. </p>

<h2>Droplet nicht ausführen, nur dessen Aufruf anzeigen</h2> 
<p>Manchmal möchte man verhindern, dass der Code ausgeführt wird, da man nur den Droplet Aufruf als solches ausgeben möchte. Für diesen Zweck verwende:</p>
<pre>[<b>\</b>[ModifiedWhen]] <i style="color: #f1b04d">    // Droplet Aufruf als solches ausgeben</i></pre>
<p>Auf diese Weise wird nur der Droplet Aufruf als solches Ausgegeben: [[ModifiedWhen]]. </p>


<h2>Weitere Droplets</h2> 
<p>Einige fertige Droplets sind hier zu finden: <a href="https://addons.wbce.org/pages/droplets.php" target="_blank">Droplets im WBCE AOR</a>.</p>
<p>Wir laden Dich ein, eigene Droplets zu schreiben und mit der Community zu teilen.</p>


<h2>Droplets programmieren</h2> 
<p>Droplets bestehen aus ausgeführtem PHP Code, wobei der öffnende <span style="color: #ff0000; ">&lt;?php</span> noch der schlie&szlig;ende <span style="color: #ff0000; ">?&gt;</span> Tag weder nötig noch erlaubt sind. (Der öffnende PHP-Tag, sollte er im Droplet stehen, wird vom Modul entfernt.)
    </p>
<p>Mit Droplets kann man nicht die Funktionen echo oder print verwenden, um die Daten direkt auszugeben. Der Droplet Aufruf wird mittels <tt>return</tt> den Inhalt des PHP-Codes auf der Seite ersetzen.<br />
Beispiel <tt>[[HelloWorld]]</tt></p>
<br>
<p><span style="color: #ff0000;">Falscher Code:</span><pre> echo "Hello World";</pre></p>
<p><span style="color: #339966;">Richtiger code:</span><pre> return "Hello World";</pre></p>
<br>

<p>Droplets können den gesamten Inhalt der Seite verändern.</p>
<p>Wenn ein Droplet aufgerufen wird, wird eine Variable bereit gestellt ($wb_page_data) die den gesamten Inhalt der Seite beinhaltet.</p>
<p>Jeder Teil dieses Inhalts kann mittels PHP abgeändert werden, einfach indem Änderungen an diesem Inhalt vorgenommen werden. Dazu ist es nicht notwendig durch <tt>echo</tt> etwas vorher auszugeben, denn das Modul wird die Ausgabe nach der Verarbeitung selbständig ausführen.</p>
<br>
<p>Das Droplets Modul wird den Code Deines Droplets auf validität überprüfen.</p>
<p>Sollte der Code nicht richtig ausführen können, wird ein rot pulsierendes Droplet angezeigt. Wie hier: <i class="fa fa-tint blinking text-danger" title="Dieses Droplet enthält ungültigen PHP code"></i></p>

<h2>Weitere Hilfe</h2>
<p>Wenn Du weitere Hilfe benötigst, besuche bitte diesen <a href="https://forum.wbce.org/viewforum.php?id=36" target="_blank">Thread im WBCE Forum</a>.</p>
</section>