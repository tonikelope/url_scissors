<?php
/**
 * Created by PhpStorm.
 * User: tonikelope
 * Date: 29/04/17
 * Time: 22:52
 */

namespace DsimTest\tests\unit;

use DsimTest\UrlParser\UrlParserInterface;
use PHPUnit\Framework\TestCase;

class CoolUrlTest extends TestCase
{
    public function testConstructor()
    {
        $url = '*****************';

        $parsed = [
            'proto' => 'http',
            'sub' => ['www'],
            'dom' => 'doctorsim',
            'tld' => 'com',
            'dirs' => ['dsim'],
            'page' => 'test',
            'ext' => 'php',
            'params' => ['par' => '2', 'impar' => '3']
        ];

        $parserStrategy = $this->createMock(UrlParserInterface::class);

        $parserStrategy->method('parse')->willReturn($parsed);

        $coolUrl = new \DsimTest\UrlParser\CoolUrl($url, $parserStrategy);

        $this->assertEquals($url, $coolUrl->getUrl());

        $this->assertEquals($parserStrategy, $coolUrl->getParserStrategy());

        foreach ($parsed as $key => $val) {
            $this->assertEquals($val, call_user_func(array($coolUrl, 'get'.ucwords($key))));
        }
    }

    public function testSetters()
    {
        $url = '*****************';

        $parsed = [
            'proto' => 'http',
            'sub' => ['www'],
            'dom' => 'doctorsim',
            'tld' => 'com',
            'dirs' => ['dsim'],
            'page' => 'test',
            'ext' => 'php',
            'params' => ['par' => '2', 'impar' => '3']
        ];

        $parserStrategy = $this->createMock(UrlParserInterface::class);

        $parserStrategy->method('parse')->willReturn($parsed);

        $coolUrl = new \DsimTest\UrlParser\CoolUrl($url, $parserStrategy);

        $url = '+++++++++++++++++++';

        $coolUrl->setUrl($url);

        $parsed = [
            'proto' => 'http',
            'sub' => null,
            'dom' => 'doctorsim',
            'tld' => 'com',
            'dirs' => ['dsim'],
            'page' => 'test',
            'ext' => 'php',
            'params' => ['par' => '2', 'impar' => '3']
        ];

        $parserStrategy = $this->createMock(UrlParserInterface::class);

        $parserStrategy->method('parse')->willReturn($parsed);

        $coolUrl->setParserStrategy($parserStrategy);

        $this->assertEquals($url, $coolUrl->getUrl());

        $this->assertEquals($parsed, $coolUrl->getParserStrategy()->parse($url));
    }
}
