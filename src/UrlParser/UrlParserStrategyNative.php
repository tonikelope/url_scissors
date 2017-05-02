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
    /**
     * All tokens are OPTIONAL (in this strategy REGEX are not used for any stuff)
     *
     * @param $url - Url to parse
     * @return array - Parsed URL
     */
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

        if (!empty($phpParsedUrl['scheme'])) {
            $parsed['proto'] = $phpParsedUrl['scheme'];
        }

        if (!empty($phpParsedUrl['host']) && filter_var($phpParsedUrl['host'], FILTER_VALIDATE_IP) === false) {
            //If host IS NOT an IP address

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
            $barraPos = strrpos($phpParsedUrl['path'], '/');

            if ($barraPos == strlen($phpParsedUrl['path'])-1) {
                //If path ends with '/' there is NOT page and/or ext

                $dirs = trim($phpParsedUrl['path'], '/');

                if (!empty($dirs)) {
                    $parsed['dirs'] = explode('/', $dirs);
                }
            } else {
                $puntoPos = strrpos($phpParsedUrl['path'], '.');

                if ($puntoPos !== false && $puntoPos > $barraPos && $puntoPos < strlen($phpParsedUrl['path'])-1
                    && $puntoPos - $barraPos > 1) {
                    //Page and ext are present

                    $parsed['page'] = substr($phpParsedUrl['path'], $barraPos+1, $puntoPos-$barraPos-1);

                    $parsed['ext'] = substr($phpParsedUrl['path'], $puntoPos+1);

                    $dirs = trim(dirname($phpParsedUrl['path']), '/');

                    if (!empty($dirs)) {
                        $parsed['dirs'] = explode('/', $dirs);
                    }
                } else {
                    /*
                        Example1: http://www.foo.com/one/two/.php (in this case .php is considered a hidden FILE/DIR)
                        Example2: http://www.foo.com/one/two/page.php/ (in this case page.php is considered a DIR)
                    */

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
}
