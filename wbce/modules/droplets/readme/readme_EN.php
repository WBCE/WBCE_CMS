<section class="help">
    <h1><img src="<?=get_url_from_path(dirname(__DIR__))?>/img/droplets_logo.png" alt="Droplets"></h1>
    <div class="authors">
        <p><span>Developed by</span></p>
             <p><b>Original authors:</b> Ruud <a href="https://dev4me.com/">Dev4me</a> and John (PCWacht)<br>
           <b>Subsequent development:</b> Bianka Martinovic (WebBird), cwsoft, NorHei, Bernd, <br>Colinax, Christian M. Stefan (Stefek)<br>
        </p>
    </div> 
    
<h2>Introduction</h2> 
<p>Droplets are small chunks of php code (just like the code2 module) that can be included in your template or any other content section.</p>

<h2>Including a droplet call</h2> 
<p>Including a droplet is done by encapsulating the droplet name in double square brackets.</p>
<p>I.e. if you want to use the droplet &quot;ModifiedWhen&quot; (to show the last modified date and time of the current page) you only need to add:</p> 
<pre>[[ModifiedWhen]] <i style="color: #f1b04d">    // a Droplet call</i></pre>
<p>to your template or WYSIWYG contentpage.</p>

<h2>Blocking the execution of a Droplet call</h2> 
<p>If you want to block the execution of a Droplet (temporarily), place the # symbol between the opening square brackets, like this:</p>
<pre>[<b>#</b>[ModifiedWhen]] <i style="color: #f1b04d">    // commenting a Droplet</i></pre>
<p>This will make sure that the Droplet code is not executed and nothing is rendered. </p>


<h2>Coding Droplets</h2> 
<p>You are encouraged to create your own droplets and share them with the community.</p>
<p>Droplets run in PHP mode, so <span style="color: #ff0000; ">&lt;?php</span> or <span style="color: #ff0000; ">?&gt;</span> is neither necessary nor allowed in the code! If any of these tags are found in the code they will be removed. Your code will not run as expected.</p>
<p>The droplet code can NOT echo or print data to the output stream directly. The Droplet name is replaced by the return value of the PHP code.<br />
 Example: [[HelloWorld]]</p>
<br>
<p><span style="color: #ff0000;">Wrong code:</span><pre> echo &quot;Hello World&quot;;</pre></p>
<p><span style="color: #339966;">Correct code:</span><pre> return &quot;Hello World&quot;;</pre></p>
<br>
<p>Droplets can modify the complete page content.</p>
<p>When the Droplet is called, an extra variable ($wb_page_data) is made available.</p>
<p>This variable holds all the content of your current generated webpage.</p>
<p>You can modify any part of this content simply by replacing it in the variable. There is no need to return this variable, the Droplet code will process changed content automatically.</p>
<br>
<p>Droplets will check the PHP code you have saved for validity.</p>
<p>When the code will not execute correctly a red flashing icon will apear in the backend Droplets list.</p>
<p>The standard blue icon is no guarantee that the Droplets does what you would expect it to do, it will just tell you if the code is valid PHP code.</p>
<br>
<p>Droplets do not need to return any data. When you end your code with</p>
<pre>return true;</pre>
<p>there will not be an errormessage. The processed Droplet tag will be removed.</p>

<h2>Getting help</h2> 
<p>If you run into issues with Droplets, have a look at the <a href="https://forum.wbce.org/viewforum.php?id=36" target="_blank">WBCE Forum</a>.</p>
</section>