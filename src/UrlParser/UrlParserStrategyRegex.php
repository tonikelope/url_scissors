<?php

/**
 * Created by PhpStorm.
 * User: tonikelope
 * Date: 27/04/17
 * Time: 16:46
 */

namespace DsimTest\UrlParser;

class UrlParserStrategyRegex implements UrlParserInterface
{
    /**
     * @param $url - Url to parse
     * @desc All tokens are OPTIONAL
     * @return array - Parsed URL
     */
    public function parse($url)
    {
        preg_match(
            '/^(?:(?P<proto>.*?)\:\/\/|\/\/)?(?:(?:[^\:]+\:[^@]+@)?(?P<host>[^\:\/]+)(?:\:[0-9]+)?)?'.
            '(?P<path>(?:\/[^\/\?]*)+)?(?:(?P<query>\?[^\#]+)(?:\#.*)?)?$/i',
            trim($url),
            $matches
        );

        $parsed=[
            'proto' => null,
            'sub' => null,
            'dom' => null,
            'tld' => null,
            'dirs' => null,
            'page' => null,
            'ext' => null,
            'params' => null];

        if (!empty($matches['proto'])) {
            $parsed['proto'] = $matches['proto'];
        }

        if (!empty($matches['host']) && filter_var($matches['host'], FILTER_VALIDATE_IP) === false) {
            //If host IS NOT an IP address

            $names = explode('.', $matches['host']);

            if (count($names) > 1) {
                $parsed['tld'] = array_pop($names);

                $parsed['dom'] = array_pop($names);

                if (!empty($names)) {
                    $parsed['sub'] = $names;
                }
            }
        }

        if (!empty($matches['path'])) {
            if (preg_match('/^(?P<dirs>.*\/)(?P<page>[^\/]+)\.(?P<ext>[^\.\/]+)$/i', $matches['path'], $matchesPath)) {
                //Page and ext are present

                $dirs = trim($matchesPath['dirs'], '/');

                if (!empty($dirs)) {
                    $parsed['dirs'] = explode('/', $dirs);
                }

                $parsed['page'] = $matchesPath['page'];

                $parsed['ext'] = $matchesPath['ext'];
            } else {
                /*
                    Example1: http://www.foo.com/one/two/.php (in this case .php is considered a DIRECTORY)
                    Example2: http://www.foo.com/one/two/page.php/ (in this case page.php is considered a DIRECTORY)
                */

                $dirs = trim($matches['path'], '/');

                if (!empty($dirs)) {
                    $parsed['dirs'] = explode('/', $dirs);
                }
            }
        }

        if (!empty($matches['query'])) {
            preg_match_all(
                '/(?P<var>[^\=\&]+)\=(?P<val>[^\=\&]*)/i',
                ltrim($matches['query'], '?'),
                $matchesPath,
                PREG_SET_ORDER
            );

            $parsed['params'] = [];

            foreach ($matchesPath as $match) {
                if (preg_match('/^(.+)\[\]$/', $match['var'], $matchVar)) {
                    //Array parameter

                    if (!isset($parsed['params'][$matchVar[1]])) {
                        $parsed['params'][$matchVar[1]] = [urldecode($match['val'])];
                    } else {
                        $parsed['params'][$matchVar[1]][]=urldecode($match['val']);
                    }
                } else {
                    //Non array parameter

                    $parsed['params'][$match['var']] = urldecode($match['val']);
                }
            }
        }

        return $parsed;
    }
}
