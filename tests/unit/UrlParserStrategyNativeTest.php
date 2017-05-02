<?php
/**
 * Created by PhpStorm.
 * User: tonikelope
 * Date: 28/04/17
 * Time: 18:47
 */

namespace DsimTest\tests\unit;

class UrlParserStrategyNativeTest extends UrlParserStrategyTest
{
    public static function setUpBeforeClass()
    {
        self::$parserStrategy = new \DsimTest\UrlParser\UrlParserStrategyNative();
    }
}
