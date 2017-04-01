# PHPWord-HTMLParser
This project is an alternative (a good one) to addHTML method from PHPWord package.

All you need to do is to include RAhtmlParser.php into your php code.

require_once("RAhtmlParser.php");

We just need to call

addCustomHTML($section, $htmlcode, $fullHTML = false);

where <br />
    $section is a PHPWord section <br />
    $htmlcode contain html to be added to our docx file
    $fullHTML set to true only when we have &lt;html&gt; and <body> tags inside the $htmlcode
