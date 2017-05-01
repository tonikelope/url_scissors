<?php
/**
 * Created by PhpStorm.
 * User: tonikelope
 * Date: 28/04/17
 * Time: 18:07
 */

namespace DsimTest\tests\unit;

use PHPUnit\Framework\TestCase;

abstract class UrlParserStrategyTest extends TestCase
{
    protected static $parserStrategy;

    public function parseProvider()
    {
        return [
            ['http://www.doctorsim.com/dsim/test/', [
                'proto' => 'http',
                'sub' => ['www'],
                'dom' => 'doctorsim',
                'tld' => 'com',
                'dirs' => ['dsim', 'test'],
                'page' => null,
                'ext' => null,
                'params' => null
            ]],
            ['http://www.doctorsim.com/dsim/test', [
                'proto' => 'http',
                'sub' => ['www'],
                'dom' => 'doctorsim',
                'tld' => 'com',
                'dirs' => ['dsim', 'test'],
                'page' => null,
                'ext' => null,
                'params' => null
            ]],
            ['https://www.doctorsim.com/', [
                'proto' => 'https',
                'sub' => ['www'],
                'dom' => 'doctorsim',
                'tld' => 'com',
                'dirs' => null,
                'page' => null,
                'ext' => null,
                'params' => null
            ]],
            ['http://aaa.www.doctorsim.com', [
                'proto' => 'http',
                'sub' => ['aaa','www'],
                'dom' => 'doctorsim',
                'tld' => 'com',
                'dirs' => null,
                'page' => null,
                'ext' => null,
                'params' => null
            ]],
            ['http://doctorsim.com/dsim/test/', [
                'proto' => 'http',
                'sub' => null,
                'dom' => 'doctorsim',
                'tld' => 'com',
                'dirs' => ['dsim', 'test'],
                'page' => null,
                'ext' => null,
                'params' => null
            ]],
            ['http://localhost:3333/dsim/test/prueba/', [
                'proto' => 'http',
                'sub' => null,
                'dom' => null,
                'tld' => null,
                'dirs' => ['dsim', 'test', 'prueba'],
                'page' => null,
                'ext' => null,
                'params' => null
            ]],
            ['http://127.0.0.1:1337/dsim/test/?v=4&p=4rc3g7c37x4n7g', [
                'proto' => 'http',
                'sub' => null,
                'dom' => null,
                'tld' => null,
                'dirs' => ['dsim', 'test'],
                'page' => null,
                'ext' => null,
                'params' => ['v' => '4', 'p' => '4rc3g7c37x4n7g']
            ]],
            ['http://www.doctorsim.com/?v=4&p=4rc3g7c37x4n7g', [
                'proto' => 'http',
                'sub' => ['www'],
                'dom' => 'doctorsim',
                'tld' => 'com',
                'dirs' => null,
                'page' => null,
                'ext' => null,
                'params' => ['v' => '4', 'p' => '4rc3g7c37x4n7g']
            ]],
            ['http://www.doctorsim.com/dsim/test.html', [
                'proto' => 'http',
                'sub' => ['www'],
                'dom' => 'doctorsim',
                'tld' => 'com',
                'dirs' => ['dsim'],
                'page' => 'test',
                'ext' => 'html',
                'params' => null
            ]],
            ['http://www.doctorsim.com:80/dsim/test.php?par=2&impar=%223%22', [
                'proto' => 'http',
                'sub' => ['www'],
                'dom' => 'doctorsim',
                'tld' => 'com',
                'dirs' => ['dsim'],
                'page' => 'test',
                'ext' => 'php',
                'params' => ['par' => '2', 'impar' => '"3"']
            ]],
            ['http://www.doctorsim.com/?v=4&p=4rc3g7c37x4n7g&c[]=1&c[]=2', [
                'proto' => 'http',
                'sub' => ['www'],
                'dom' => 'doctorsim',
                'tld' => 'com',
                'dirs' => null,
                'page' => null,
                'ext' => null,
                'params' => ['v' => '4', 'p' => '4rc3g7c37x4n7g', 'c' => ['1', '2']]
            ]]
        ];
    }

    /**
     * @dataProvider parseProvider
     * @param $url
     * @param $expected
     */
    public function testParse($url, $expected)
    {
        $this->assertEquals($expected, self::$parserStrategy->parse($url));
    }
}
