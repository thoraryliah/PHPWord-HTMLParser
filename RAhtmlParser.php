<?php
  
  /**
  *
  * Thor Aryliah HTML Parser for PHPWord
  * This library is an alternative for addHTML method from PHPWord.
  *
  */
  
  // the code
  
  /**
  *
  * This method try to find text formating tags as <strong>, <b>, <em>, <i>, <u>, <strike>, <sub>, <sup> and
  * compose an array of styles which is compatible with PHPWord Font Style.
  * In fact all the styles regard only Font Styles that are compatible with PHPWord Font Style
  * 
  * see http://phpword.readthedocs.io/en/latest/styles.html#font for further informations
  * 
  * @method retrieveStyles($parentNode, $cStyle)
  * @param $parentNode - a DOM node from DOMDocument
  * @param $cStyle = array() -- of styles
  * @return $cStyle, array()
  */
  
  function retrieveStyles($parentNode, $cStyle){
    // strong or b tags (bold text)
    if ( ($parentNode->nodeName == 'strong') || ($parentNode->nodeName == 'b') ) {
      $cStyle = array_merge($cStyle, array('bold' => true));
    }
    
    // em or i tags (italic text)
    if ( ($parentNode->nodeName == 'em') || ($parentNode->nodeName == 'i') ){
      $cStyle = array_merge($cStyle, array('italic' => true));
    }
    
    // u tag (underline text)
    if ($parentNode->nodeName == 'u'){
      $cStyle = array_merge($cStyle, array('underline' => 'single'));
    }
    
    // strike tag (strike text)
    if ($parentNode->nodeName == 'strike'){
      $cStyle = array_merge($cStyle, array('strikethrough' => true));
    }
    
    // subscript tag (subscript text)
    if ($parentNode->nodeName == 'sub'){
      $cStyle = array_merge($cStyle, array('subScript' => true));
    }
    
    // supersctipt tag (superscript text)
    if ($parentNode->nodeName == 'sup'){
      $cStyle = array_merge($cStyle, array('superScript' => true));
    }
    
    // if we have attributes we need them out
    if ($parentNode->hasAttributes()){
      $attributes = array();
      foreach ($parentNode->attributes as $attribute){
        if ($attribute->nodeName == 'style'){
          $styles = array();
          $styles = explode(';', $attribute->nodeValue);
    
          if ( empty($styles[ count($styles) - 1 ]) )
            unset($styles[count($styles) - 1]);
    
            ////////////////////////////// styles //////////////////////////
            foreach ($styles as $style){
              $line = explode(':',  $style);
    
              // $line[0] is the property
              // $line[1] is the value of property stored in $line[0]
              
              // we need only color and background-color from style attribute
              // 'course you can add more interpreters for font-size, font-name and so on.
              if ($line[0] == 'color'){
                $color = str_replace('#', '', $line[1]); // elimin caracterul # deoarece phpword are nevoie de culoare fara caracterul #
                $cStyle = array_merge($cStyle, array('color' => $color));
              }
    
              if ($line[0] == 'background-color'){
                $color = str_replace('#', '', $line[1]); // elimin caracterul # deoarece phpword are nevoie de culoare fara caracterul #
                $cStyle = array_merge($cStyle, array('bgcolor' => $color));
              }
            }
            ////////////////////////////// styles //////////////////////////
        } // if $attr->nodeName==style
      } // foreach
    }
    return $cStyle;
  }
  
  /**
  *
  * This recursive method search through parentNodes and retrieves all the styles and tags as well.
  * Most WYSIWYG editors are rending the styles as follow:
  *
  * <span style="color: red;"><strong><em>Text...</em></strong></span>
  *
  * As you can see, this html formatting style fit perfectly to bold-italic word text and of red color. 
  * 
  * This method doesn't know how to interpret font-weight, font-name (and others) because this issues are not implemented.
  * You can add this interpretation in retrieveStyles function described before.
  * 
  * @method searchForParentsStyles($node, $cStyle)
  * @param $node, DOM node form DOMDocument
  * @param $cStyle, array() of styles
  * @return $cStyle, array()
  */
  function searchForParentsStyles($node, $cStyle){
    while ($node->nodeValue == $node->parentNode->nodeValue){
      $parentNode = $node->parentNode;
      
      $cStyle = retrieveStyles($parentNode, $cStyle);
      $node = $node->parentNode;
    }
    return $cStyle;
  }
  
  /**
  * @method parseHTML($node, $textrun, $section) {
  * @param $node, DOM node from DOMDocument
  * @param @textrun, represent a PHPWord textrun which can put in our document excerpts of text with different styles and settings
  * @param @section, represent a PHPWord section
  * 
  */
  function parseHTML($node, $textrun, $section) {
    $pStyle = array(
        'alignment' => 'both', 'spaceAfter' => 0, 'spaceBefore' => 0, 'hanging' => 0
    );
    
    // if we have an <br /> node we must go to the next line
    if ($node->nodeName == 'br'){
      $textrun = $section->addTextRun($pStyle);
    }
    
    // again, we make the same operation for an <p> tag or we can modify this for a <div> tag too
    if ($node->nodeName == 'p'){
      $textrun = $section->addTextRun($pStyle);
    }
    
    if ($node->hasChildNodes()) {
      foreach ($node->childNodes as $childNode){
        if ($childNode->nodeType == XML_TEXT_NODE) {
          $cStyle = array();
          $parentNode = $childNode->parentNode; 
          
          // we need to do this kind of search because of XML_TEXT_NODE step
          // in fact on every step, in this if statement, we have XML_TEXT_NODE nodeType
          $cStyle = retrieveStyles($parentNode, $cStyle); // this line can be commented
          $cStyle = searchForParentsStyles($parentNode, $cStyle);
          
          $textrun->addText($childNode->nodeValue, $cStyle, $pStyle);
        }
        parseHTML($childNode, $textrun, $section);
      }
    }
  }
  
  /**
  * @method addCustomHTML($section, $html, $fullHTML = false)
  * @param $secion, is a PHPWord section
  * @param $html represent the html code that we want to convert and insert into docx file.
  * @fullHTML = false is required only if we have an entire full html document
  * 
  */
  function addCustomHTML($section, $html, $fullHTML = false){
    if (!$fullHTML) {
      $html = '<html><body>'.$html.'</body></html>';
    }
    
    $html = str_replace(array("\n", "\r"), '', $html);
    $html = str_replace(array('&lt;', '&gt;', '&amp;'), array('_lt_', '_gt_', '_amp_'), $html);
    $html = html_entity_decode($html, ENT_QUOTES);
    $html = str_replace('&', '&amp;', $html);
    $html = str_replace(array('_lt_', '_gt_', '_amp_'), array('&lt;', '&gt;', '&amp;'), $html);
    
    $dom = new DOMDocument();
    // we need to retrieve text from html document with unicode characters
    $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
    $dom->preserveWhiteSpace = true;
    
    $textrun = $section->addTextRun(['alignment' => 'both', 'spaceAfter' => 0, 'spaceBefore' => 0, 'hanging' => 0]);
    
    parseHTML($dom->getElementsByTagName('*')->item(1), $textrun, $section);
    
    $section->addText("", [], ['alignment' => 'both', 'spaceAfter' => 0, 'spaceBefore' => 0, 'hanging' => 0]);
  }
  
?>
