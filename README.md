# PHPWord-HTMLParser
This project is an alternative (a good one) to addHTML method from PHPWord package.

You have to include <b>RAhtmlParser.php</b> into your code,

<i><code>require_once("RAhtmlParser.php");</code></i>

And then just call

<i><code>addCustomHTML($section, $htmlcode, $fullHTML = false);</code></i>

<span style="color: red;">where</span> <br />
    `$section` is a PHPWord section <br />
    `$htmlcode` contain html to be added to our docx file <br />
    `$fullHTML` set to true only when we have &lt;html&gt; and &lt;body&gt; tags inside the `$htmlcode`


<p style="color: blue;">
If you are use CKEditor you must start it with the follow config.js settings
	<code>config.htmlEncodeOutput = false;<br />
    config.entities = false;</code>
</p>
