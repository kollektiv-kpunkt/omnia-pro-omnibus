<?php
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twigParams = [
    'cache' => __DIR__ . '/../compilation_cache'
];

if (!isset($_ENV['production']) || $_ENV['production'] == "0") {
    $twigParams['debug'] = true;
    $_ENV["version"] = time();
}
$twig = new \Twig\Environment($loader, $twigParams);
$twig->addExtension(new \Twig\Extension\DebugExtension());

use Pecee\SimpleRouter\SimpleRouter as Router;

$kandis = json_decode(file_get_contents(__DIR__ . "/../data/kandis.json"), true);
$topics = json_decode(file_get_contents(__DIR__ . "/../data/topics.json"), true);


Router::get('/', function() use ($twig, $kandis, $topics) {
    $page = [
        "title" => "Alles für alle!",
        "description" => "Der Kanton Zürich vor grossen Herausforderungen, die wir nur gemeinsam meistern können. Deshalb braucht es starke, junge und linke Stimmen, die sich gegen die Bürgerlichen in unserem Kanton wehren.",
        "OG" => "/img/og/default.png"
    ];
    shuffle($kandis);
    return $twig->render("frontpage.html" , ["page" => $page, "environment" => $_ENV, "kandis" => array_slice($kandis, 0,8), "topics" => $topics]);
});