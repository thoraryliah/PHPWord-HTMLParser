# PHPWord-HTMLParser
This project is an alternative (a good one) to addHTML method from PHPWord package.

All you need to do is to include RAhtmlParser.php into your php code.

<code>require_once("RAhtmlParser.php");</code>

We just need to call

<code>addCustomHTML($section, $htmlcode, $fullHTML = false);</code>

<span style="color: red;">where</span> <br />
    `$section` is a PHPWord section <br />
    `$htmlcode` contain html to be added to our docx file
    $fullHTML set to true only when we have &lt;html&gt; and &lt;body&gt; tags inside the `$htmlcode`
