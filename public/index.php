<?php

/**
 * Created by PhpStorm.
 * User: tonikelope
 * Date: 28/04/17
 * Time: 21:09
 */

define('VERSION', '0.0.4');
define('PHP_WEB_SERVER_DEV', true);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \DsimTest\UrlParser\CoolUrl;
use \DsimTest\UrlParser\UrlParserInterface;

require __DIR__.'/../vendor/autoload.php';

if (PHP_WEB_SERVER_DEV &&
    file_exists($_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"]) &&
    !preg_match('/\.php|\/+$/i', $_SERVER["REQUEST_URI"])) {
    return false;
}

function render(\Slim\Container $slimCont, Request $request, Response $response, UrlParserInterface $parserStrategy)
{
    $url = $request->getUri()->getScheme().'://'.$request->getUri()->getHost().$_SERVER['REQUEST_URI'];

    $parsingStartTime = microtime(true);

    $coolUrl = new CoolUrl($url, $parserStrategy);

    $parsingEndTime = microtime(true);

    return $slimCont->view->render(
        $response,
        'index.html',
        ['time' => ($parsingEndTime - $parsingStartTime)*1000,
            'url'=> $url,
            'parserStrategy' => get_class($parserStrategy),
            'proto' => $coolUrl->getProto(),
            'sub' => !empty($coolUrl->getSub())?implode('.', $coolUrl->getSub()):null,
            'dom' => $coolUrl->getDom(),
            'tld' => $coolUrl->getTld(),
            'dirs' => !empty($coolUrl->getDirs())?$coolUrl->getDirs():null,
            'page' => $coolUrl->getPage(),
            'ext' => $coolUrl->getExt(),
            'params' => $coolUrl->getParams()
        ]
    );
}

$app = new \Slim\App;

$app->getContainer()['view'] = function () {

    $view = new \Slim\Views\Twig(__DIR__.'/../templates', ['cache' => false]);

    return $view;
};

$app->get('[/{params:.*}]', function (Request $request, Response $response) {

    return render($this, $request, $response, new \DsimTest\UrlParser\UrlParserStrategyRegex());
});

$app->post('[/{params:.*}]', function (Request $request, Response $response) {

    $postData = $request->getParsedBody();

    $parserStrategyClass = '\DsimTest\UrlParser\UrlParserStrategy'.ucwords($postData['radioStrategy']);

    return render($this, $request, $response, new $parserStrategyClass());
});

$app->run();
