<?php

class DOMService
{
    private $domImplement;

    public function __construct()
    {
        $this->domImplement = new DOMDocument();
    }

    public function loadHtml($html)
    {
        return @$this->domImplement->loadHTMLFile($html);
    }

    public function getDom()
    {
        return $this->domImplement;
    }

    public function findAllHerfsInGivenArray($array)
    {
        $links = [];

        foreach ($array as $item) { // find all links
            $anchor = $item->nodeValue;
            $alt = $item->getAttribute('alt');
            $rel = $item->getAttribute('rel');
            $href = $item->getAttribute('href');
            $src = $item->getAttribute('src');
            $links[] = [
                'anchor' => $anchor,
                'href' => $href,
                'alt' => $alt,
                'rel' => $rel,
                'src' => $src
            ];
        }

        return $links;
    }

    public function findAllImagesInGivenArray($array)
    {
        $imgs = [];

        foreach ($array as $item) { // find all img
            $anchor = $item->nodeValue;
            $alt = $item->getAttribute('alt');
            $rel = $item->getAttribute('rel');
            $href = $item->getAttribute('href');
            $src = $item->getAttribute('src');
            $imgs[] = [
                'anchor' => $anchor,
                'href' => $href,
                'alt' => $alt,
                'rel' => $rel,
                'src' => $src
            ];
        }

        return $imgs;
    }

    public function findInGivenArrayHrefsAndImgs($array)
    {

    }

}