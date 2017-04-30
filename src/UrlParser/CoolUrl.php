<?php
/**
 * Created by PhpStorm.
 * User: tonikelope
 * Date: 29/04/17
 * Time: 17:47
 */

namespace DsimTest\UrlParser;

class CoolUrl
{
    private $url;
    private $parserStrategy;
    private $proto;
    private $sub;
    private $dom;
    private $tld;
    private $dirs;
    private $page;
    private $ext;
    private $params;

    /**
     * CoolUrl constructor.
     * @param $url
     * @param UrlParserInterface $parserStrategy
     */
    public function __construct($url, UrlParserInterface $parserStrategy)
    {
        $this->url = $url;
        $this->parserStrategy = $parserStrategy;
        $this->parseUrl();
    }

    private function parseUrl()
    {
        foreach ($this->parserStrategy->parse($this->url) as $key => $val) {
            $this->$key = $val;
        }
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        $this->parseUrl();
    }

    /**
     * @param UrlParserInterface $parserStrategy
     */
    public function setParserStrategy($parserStrategy)
    {
        $this->parserStrategy = $parserStrategy;
        $this->parseUrl();
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return UrlParserInterface
     */
    public function getParserStrategy()
    {
        return $this->parserStrategy;
    }

    /**
     * @return mixed
     */
    public function getProto()
    {
        return $this->proto;
    }

    /**
     * @return mixed
     */
    public function getSub()
    {
        return $this->sub;
    }

    /**
     * @return mixed
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @return mixed
     */
    public function getTld()
    {
        return $this->tld;
    }

    /**
     * @return mixed
     */
    public function getDirs()
    {
        return $this->dirs;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return mixed
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }
}
