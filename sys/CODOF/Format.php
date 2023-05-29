<?php

namespace CODOF;

use \JBBCode\Parser as BBCodeParser;
use \JBBCode\CodeDefinitionBuilder as BBCodeBuilder;

class Format
{

    public static function message($mesg)
    {

        //convert relative path back to absolute url
        $mesg = str_replace("CODOF_RURI_" . UID . "_", NRURI, $mesg);
        return str_replace("CODOF_DURI_" . UID . "_", DURI, $mesg);
    }

    /**
     *
     * @param string $mesg
     * @return []
     */
    public static function excerpt($mesg)
    {
        $html = mb_convert_encoding($mesg, 'HTML-ENTITIES', "UTF-8");

        $doc = new \DOMDocument();
        @$doc->loadHTML($html);

        $maxNoCharacters = 300;
        if (Util::optionExists('max_excerpt_chars')) {
            $maxNoCharacters = Util::get_opt('max_excerpt_chars');
        }

        $img = '';//self::getFirstImage($doc);
        $link = self::getFirstLink($doc);
        $table = self::getFirstTable($doc, $maxNoCharacters);

        $txt = $doc->saveHTML();
        if ($img != '') {
            $img = "<br>" . $img;
        } else if ($link != '') {
            $link = "<br>" . $link;
        }

        return array('message' => $txt . $table . $img,  'oembed_video' => $link);
    }

    public static function imessage($mesg)
    {
        return $mesg;
    }

    public static function title($mesg)
    {
        $mesg = htmlentities($mesg, ENT_NOQUOTES, "UTF-8");
        return $mesg;
    }

    public static function parseBBCode($str)
    {
        $parser = new BBCodeParser();

        $parser->addCodeDefinitionSet(new \JBBCode\DefaultCodeDefinitionSet());

        /* [s] <strike> */
        $builder = new BBCodeBuilder('s', '<strike>{param}</strike>');
        $parser->addCodeDefinition($builder->build());
        /* [pre] <pre> */
        $builder = new BBCodeBuilder('pre', '<pre>{param}</pre>');
        $parser->addCodeDefinition($builder->build());
        /* [pre] <pre> */
        $builder = new BBCodeBuilder('sup', '<sup>{param}</sup>');
        $parser->addCodeDefinition($builder->build());
        /* [pre] <pre> */
        $builder = new BBCodeBuilder('sub', '<sub>{param}</sub>');
        $parser->addCodeDefinition($builder->build());

        /* [li] <li> */
        $builder = new BBCodeBuilder('li', '<li>{param}</li>');
        $parser->addCodeDefinition($builder->build());

        $builder = new BBCodeBuilder('list', '<ul>{param}</ul>');
        $builder->setUseOption(false)->setParseContent(true);
        $parser->addCodeDefinition($builder->build());

        $builder = new BBCodeBuilder('list', '<ol>{param}</ol>');
        $builder->setUseOption(true)->setParseContent(true);
        $parser->addCodeDefinition($builder->build());

        /* [move] <marquee> */
        $builder = new BBCodeBuilder('move', '<marquee>{param}</marquee>');
        $parser->addCodeDefinition($builder->build());

        /* [center] <div text-align: center> */
        $builder = new BBCodeBuilder('center', '<div style="text-align: center">{param}</div>');
        $parser->addCodeDefinition($builder->build());
        /* [left] <div text-align: left> */
        $builder = new BBCodeBuilder('left', '<div style="text-align: left">{param}</div>');
        $parser->addCodeDefinition($builder->build());
        /* [right] <div text-align: right> */
        $builder = new BBCodeBuilder('right', '<div style="text-align: right">{param}</div>');
        $parser->addCodeDefinition($builder->build());

        $builder = new BBCodeBuilder('right', '<div style="text-align: right">{param}</div>');
        $parser->addCodeDefinition($builder->build());

        $builder = new BBCodeBuilder('font', '<span style="font-family: {option}">{param}</span>');
        $builder->setUseOption(true);
        $parser->addCodeDefinition($builder->build());

        $builder = new BBCodeBuilder('email', '<a href="mailto: {option}">{param}</a>');
        $builder->setUseOption(true);
        $parser->addCodeDefinition($builder->build());

        $builder = new BBCodeBuilder('email', '<a href="mailto: {param}">{param}</a>');
        $parser->addCodeDefinition($builder->build());

        $builder = new BBCodeBuilder('spoiler', '<details class="codo_spoiler"><summary>{option}</summary> {param}</details>');
        $parser->addCodeDefinition($builder->build());

        $builder = new BBCodeBuilder('user', '{param}');
        $builder->setUseOption(true);
        $parser->addCodeDefinition($builder->build());

        $parser->parse($str);

        return $parser->getAsHTML();
    }

    public static function br2nl($string)
    {

        $breaks = array("<br />", "<br>", "<br/>", "<br />", "&lt;br /&gt;", "&lt;br/&gt;", "&lt;br&gt;");
        return str_ireplace($breaks, "\r\n", $string);
    }

    public static function nl2p($string)
    {
        $paragraphs = '';

        foreach (explode("\n", $string) as $line) {
            if (trim($line)) {
                $paragraphs .= '<p>' . $line . '</p>';
            }
        }

        return $paragraphs;
    }

    public static function omessage($mesg)
    {

        $html = new \Ext\Html();
        //$imesg; no escaping required
        $mesg = $html->filter(str_replace("STARTCODOTAG", "<", $mesg));

        return $mesg;
    }

    /**
     * @param $doc
     * @return string
     */
    private static function getFirstImage($doc)
    {
        $tags = $doc->getElementsByTagName('img');
        $img = '';
        foreach ($tags as $tag) {
            //smiley is not that important, so any other image than smiley accept it.
            if (strpos($tag->getAttribute('src'), SMILEY_PATH) === FALSE) {

                $img = $tag->C14N();
                break;
            }
        }
        return $img;
    }

    /**
     * @param \DOMDocument $doc
     * @param int $maxLetters
     * @return string
     */
    private static function getFirstTable(\DOMDocument $doc, int $maxLetters)
    {
        $tags = $doc->getElementsByTagName('table');

        if ($tags->length == 0) return '';
        $numLetters = 0;
        $body = $doc->getElementsByTagName('body')->item(0);
        foreach ($body->childNodes as $childNode) {
            if ($numLetters > $maxLetters || $childNode->nodeName == 'table') {
                break;
            }
            if ($childNode->nodeName == 'p') {
                $numLetters += strlen($childNode->nodeValue);
            }
        }

        if ($numLetters > $maxLetters) return '';
        $table = '';
        foreach ($tags as $tag) {
            $table = $tag->C14N();
            $tag->nodeValue = '';
            break;
        }
        return $table;
    }

    /**
     * @param $doc
     * @return string
     */
    private static function getFirstLink(\DOMDocument $doc)
    {
        if (Util::get_opt('insert_oembed_videos') === 'no')
            return '';

        $link = '';
        $tags = $doc->getElementsByTagName('a');
        foreach ($tags as $tag) {
            //Only accept links with abs paths
            if (strpos($tag->getAttribute('href'), "serve/") === FALSE &&
                strpos($tag->getAttribute('href'), "user/profile") === FALSE &&
                strpos($tag->getAttribute('href'), "youtube.com") !== FALSE) {
                $link = $tag->C14N();
                $tag->parentNode->removeChild($tag);
                break;
            }
        }
        return $link;
    }

}
