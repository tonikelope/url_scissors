<?php

/**
 * Created by PhpStorm.
 * User: tonikelope
 * Date: 27/04/17
 * Time: 16:49
 */

namespace DsimTest\UrlParser;

class UrlParserStrategyNative implements UrlParserInterface
{
    public function parse($url)
    {
        $parsed=[
            'proto' => null,
            'sub' => null,
            'dom' => null,
            'tld' => null,
            'dirs' => null,
            'page' => null,
            'ext' => null,
            'params' => null];

        $phpParsedUrl = parse_url($url);

        $parsed['proto'] = $phpParsedUrl['scheme'];

        if (filter_var($phpParsedUrl['host'], FILTER_VALIDATE_IP) === false) {
            $names = explode('.', $phpParsedUrl['host']);

            if (count($names) > 1) {
                $parsed['tld'] = array_pop($names);

                $parsed['dom'] = array_pop($names);

                if (!empty($names)) {
                    $parsed['sub'] = $names;
                }
            }
        }

        if (!empty($phpParsedUrl['path'])) {
            if ($this->endsWith($phpParsedUrl['path'], '/')) {
                $dirs = trim($phpParsedUrl['path'], '/');

                if (!empty($dirs)) {
                    $parsed['dirs'] = explode('/', $dirs);
                }
            } else {
                $puntoPos = strrpos($phpParsedUrl['path'], '.');

                $barraPos = strrpos($phpParsedUrl['path'], '/');

                if ($puntoPos !== false && $puntoPos > $barraPos && $puntoPos < strlen($phpParsedUrl['path'])-1
                    && $puntoPos - $barraPos > 1) {
                    $parsed['page'] = substr($phpParsedUrl['path'], $barraPos+1, $puntoPos-$barraPos-1);

                    $parsed['ext'] = substr($phpParsedUrl['path'], $puntoPos+1);

                    $dirs = trim(dirname($phpParsedUrl['path']), '/');

                    if (!empty($dirs)) {
                        $parsed['dirs'] = explode('/', $dirs);
                    }
                } else {
                    $dirs = trim($phpParsedUrl['path'], '/');

                    if (!empty($dirs)) {
                        $parsed['dirs'] = explode('/', $dirs);
                    }
                }
            }
        }

        if (!empty($phpParsedUrl['query'])) {
            parse_str($phpParsedUrl['query'], $parsed['params']);
        }

        return $parsed;
    }

    private function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return ($length == 0 || (substr($haystack, -$length) === $needle));
    }
}
