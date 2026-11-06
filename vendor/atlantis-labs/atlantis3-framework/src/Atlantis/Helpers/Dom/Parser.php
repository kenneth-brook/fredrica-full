<?php

namespace Atlantis\Helpers\Dom;

class Parser
{

    public $document;
    public $items;
    private $dataPatternID = 'data-pattern-id';
    private $dataPatternName = 'data-pattern-name';
    private $dataPatternFunc = 'data-pattern-func';
    private $dataStorage = 'data-storage';
    private $dataNoMobile = 'data-nomobile';
    private $dataRawHTML = 'data-raw-html';
    private $dataResponsive = 'data-responsive';
    private $config;
    private $pattern;
    private $tools;
    private $wasEdited = false;

    public function __construct($doc, \Atlantis\Models\Repositories\PatternRepository $pattern, \Atlantis\Helpers\Tools $tools, $config)
    {

        $this->config = $config;

        $this->document = new \DOMDocument();

        @$this->document->loadHTML(mb_convert_encoding($doc, 'HTML-ENTITIES', 'UTF-8'));

        $this->document->formatOutput = true;

        $this->items = $this->getAllElements();

        $this->pattern = $pattern;

        $this->tools = $tools;
    }

    public function getAllElements()
    {

        return $this->document->getElementsByTagName('*');
    }

    public function process()
    {

        $p = new $this->pattern();
        $storagePath = rtrim($this->tools->getFilePath(false), '/');

        $this->wasEdited = false;

        for ($i = 0; $i < $this->items->length; $i++) {
            if ($this->items->item($i)->hasAttribute($this->dataPatternID)) {
                $id = $this->items->item($i)->getAttribute($this->dataPatternID);

                $pattern = head($p->processPatterns([$id], 'id'));

                if ($pattern) {
                    //dd($this->items->item($i));
                    //$this->items->item($i)->nodeValue = $pattern;

                    $this->items->item($i)->textContent = $pattern;

                } else {
                    //abort(404, 'Pattern id not found');
                }

                if ($this->items->item($i)->removeAttribute($this->dataPatternID)) {
                    $this->wasEdited = true;
                }
            } elseif ($this->items->item($i)->hasAttribute($this->dataPatternName)) {
                $name = $this->items->item($i)->getAttribute($this->dataPatternName);

                $pattern = head($p->processPatterns([$name], 'name'));

                if ($pattern) {
                    //$this->items->item($i)->nodeValue = $pattern;
                    $this->items->item($i)->textContent = $pattern;
                } else {
                    //abort(404, 'Pattern name not found');
                }

                if ($this->items->item($i)->removeAttribute($this->dataPatternName)) {
                    $this->wasEdited = true;
                }
            } elseif ($this->items->item($i)->hasAttribute($this->dataPatternFunc)) {
                $aAttr = array();

                foreach ($this->items->item($i)->attributes as $attr) {
                    $aAttr[$attr->nodeName] = $attr->nodeValue;
                }

                unset($aAttr[$this->dataPatternFunc]);

                //dd($aAttr);

                //$this->items->item($i)->nodeValue = $this->tools->makeAppCallFromString($this->items->item($i)->getAttribute($this->dataPatternFunc), $aAttr);
                $this->items->item($i)->textContent  = $this->tools->makeAppCallFromString($this->items->item($i)->getAttribute($this->dataPatternFunc), $aAttr);

                if ($this->items->item($i)->removeAttribute($this->dataPatternFunc)) {
                    $this->wasEdited = true;
                }
            } elseif ($this->items->item($i)->hasAttribute($this->dataStorage) && $this->items->item($i)->hasAttribute($this->dataStorage)) {
                if($this->items->item($i)->nodeName == 'link') {
                    $this->items->item($i)->setAttribute('href', $storagePath . $this->items->item($i)->getAttribute('href'));
                } else {
                    $this->items->item($i)->setAttribute('src', $storagePath . $this->items->item($i)->getAttribute('src'));
                }

                if ($this->items->item($i)->removeAttribute($this->dataStorage)) {
                    $this->wasEdited = true;
                }
            } elseif ($this->items->item($i)->hasAttribute($this->dataNoMobile)) {
                $device = \App::make('MobileDetect');
                if ($device->isMobile()) {
                    $this->items->item($i)->parentNode->removeChild($this->items->item($i));
                    $this->wasEdited = true;
                }
            } elseif ($this->items->item($i)->hasAttribute($this->dataRawHTML)) {
                $innerHTML = "";
                $children = $this->items->item($i)->childNodes;
                foreach ($children as $child) {
                    $innerHTML .= $this->items->item($i)->ownerDocument->saveHTML($child);
                }

                if ($this->items->item($i)->getAttribute($this->dataRawHTML) != 'checked') {
                    $innerHTML = htmlentities($innerHTML, ENT_QUOTES, 'UTF-8');
                }

                $this->items->item($i)->textContent = $innerHTML;

                $this->items->item($i)->setAttribute($this->dataRawHTML, 'checked');
            } elseif ($this->items->item($i)->hasAttribute($this->dataResponsive)) {
                $this->items->item($i)->removeAttribute($this->dataResponsive);

                $src = $this->items->item($i)->getAttribute('src');

                $filename = basename($src);

                if (!empty($filename)) {
                    $media = \Atlantis\Helpers\Media\MediaTools::findByName($filename);

                    if (!empty($media) && !empty($media->tablet_name) && !empty($media->phone_name) && isset($this->config['atlantis']['responsive_breakpoints']['large']) && isset($this->config['atlantis']['responsive_breakpoints']['medium'])) {
                        $imgTag = clone $this->items->item($i);

                        $picTag = $this->document->createElement('picture');

                        $sourceTagSmall = $this->document->createElement('source');
                        $sourceTagSmall->setAttribute('media', '(max-width: ' . $this->config['atlantis']['responsive_breakpoints']['medium'] . 'px)');
                        $sourceTagSmall->setAttribute('srcset', $media->phone_name . ', ' . $media->tablet_name . ' 2x');

                        $sourceTagMedium = $this->document->createElement('source');
                        $sourceTagMedium->setAttribute('media', '(min-width: ' . $this->config['atlantis']['responsive_breakpoints']['medium'] . 'px)');
                        $sourceTagMedium->setAttribute('srcset', $media->tablet_name . ', ' . $media->original_filename . ' 2x');

                        $sourceTagLarge = $this->document->createElement('source');
                        $sourceTagLarge->setAttribute('media', '(min-width: ' . $this->config['atlantis']['responsive_breakpoints']['large'] . 'px)');
                        $sourceTagLarge->setAttribute('srcset', $media->original_filename . ', ' . $media->original_filename . ' 2x');

                        $picTag->appendChild($sourceTagSmall);
                        $picTag->appendChild($sourceTagMedium);
                        $picTag->appendChild($sourceTagLarge);
                        $picTag->appendChild($imgTag);
                        $this->items->item($i)->parentNode->replaceChild($picTag, $this->items->item($i));
                    }
                }
            }
        }

        return $this->output();
    }

    public function output()
    {

        if ($this->wasEdited) {
            $doc = html_entity_decode($this->document->saveHTML(), ENT_QUOTES, 'UTF-8');
            $this->document = new \DOMDocument();
            @$this->document->loadHTML(mb_convert_encoding($doc, 'HTML-ENTITIES', 'UTF-8'));
            $this->document->formatOutput = true;
            $this->items = $this->getAllElements();
            return $this->process();
        }

        $xpath = new \DOMXPath($this->document);
        $xpathComm = $xpath->query('//comment()');

        foreach ($xpathComm as $comment) {
            /**
            * uncomment if you want to add all comments in <comment> tag
            $partDoc = new \DOMDocument("1.0", "UTF-8");

            $frag = $partDoc->createDocumentFragment();
            
            $frag->appendChild($partDoc->createComment($comment->textContent));

            $partDoc->appendChild($frag);

            $fix = htmlentities(substr($partDoc->saveXML(), strpos($partDoc->saveXML(), '?' . '>') + 2));

            $comm = $this->document->createElement('comment', $fix);

            $comment->parentNode->insertBefore($comm, $comment);
            **/

            $comment->parentNode->removeChild($comment);
        }

        if ($this->document->hasChildNodes() && $this->document->nodeName == '#document') {
            /** remove <!DOCTYPE */
            $this->document->removeChild($this->document->firstChild);

            //this is a bug now because it expects the first child to be 'html' but its 'comment' in this case
            // so the html and body tags don't get removed properly
            if ($this->document->hasChildNodes()) {
                $html = $this->document->getElementsByTagName("html")->item(0);

                if ($html != null) {
                    $fragment = $this->document->createDocumentFragment();
                    while ($html->childNodes->length > 0) {
                        $fragment->appendChild($html->childNodes->item(0));
                    }
                    $html->parentNode->replaceChild($fragment, $html);
                }
                
                $head = $this->document->getElementsByTagName("head")->item(0);

                if ($head != null) {
                    $fragment = $this->document->createDocumentFragment();
                    while ($head->childNodes->length > 0) {
                        $fragment->appendChild($head->childNodes->item(0));
                    }
                    $head->parentNode->replaceChild($fragment, $head);
                }

                $body = $this->document->getElementsByTagName("body")->item(0);
       
                if ($body != null) {
                    $fragment = $this->document->createDocumentFragment();
                    while ($body->childNodes->length > 0) {
                        $fragment->appendChild($body->childNodes->item(0));
                    }
                    $body->parentNode->replaceChild($fragment, $body);
                }
            }
        }

        /** save new document without <body> */
        $out = $this->document->saveHTML();
        return html_entity_decode($out, ENT_QUOTES, 'UTF-8');
    }
}
