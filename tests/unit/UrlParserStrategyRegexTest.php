<?php
/**
 * Created by PhpStorm.
 * User: tonikelope
 * Date: 28/04/17
 * Time: 12:50
 */

namespace DsimTest\tests\unit;

class UrlParserStrategyRegexTest extends UrlParserStrategyTest
{
    public static function setUpBeforeClass()
    {
        self::$parserStrategy = new \DsimTest\UrlParser\UrlParserStrategyRegex();
    }
}
