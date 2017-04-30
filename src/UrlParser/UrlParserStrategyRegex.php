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
    public function parse($url)
    {
        preg_match(
            '/^(?P<proto>.*?):\/\/(?P<host>[^\:\/]+)(?:\:[0-9]+)?(?P<path>(?:\/[^\/\?]*)+)?(?P<query>\?.*)?$/i',
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

        $parsed['proto'] = $matches['proto'];

        if (filter_var($matches['host'], FILTER_VALIDATE_IP) === false) {
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
                $dirs = trim($matchesPath['dirs'], '/');

                if (!empty($dirs)) {
                    $parsed['dirs'] = explode('/', $dirs);
                }

                $parsed['page'] = $matchesPath['page'];

                $parsed['ext'] = $matchesPath['ext'];
            } else {
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

            $params = [];

            foreach ($matchesPath as $match) {
                $params[$match['var']] = urldecode($match['val']);
            }

            $parsed['params'] = $params;
        }

        return $parsed;
    }
}
