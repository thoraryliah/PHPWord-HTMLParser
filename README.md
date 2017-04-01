# PHPWord-HTMLParser
This project is an alternative (a good one) to addHTML method from PHPWord package.

You have to include <b>RAhtmlParser.php</b> into your code,

<i><code>require_once("RAhtmlParser.php");</code></i>

And then just call

<i><code>RAhtmlParser::addCustomHTML($section, $htmlcode, $fullHTML = false);</code></i>

<span style="color: red;">where</span> <br />
    `$section` is a PHPWord section <br />
    `$htmlcode` contain html to be added to our docx file <br />
    `$fullHTML` set to true only when we have &lt;html&gt; and &lt;body&gt; tags inside the `$htmlcode`


If you are use <b>CKEditor</b> you must start it with the follow config.js settings<br />
<code>config.htmlEncodeOutput = false;</code><br />
<code>config.entities = false;</code>

all you have to do is to add this line right after <i>`CKEDITOR.editorConfig = function( config ) {`</i> line or you can start CKEditor with a preconfigured instance like this:

<code>
<script>
CKEDITOR.replace('articlecontent', /* which is an example of field that can contain html text */ { htmlEncodeOutput: false, entities: false });
</script>
</code>
