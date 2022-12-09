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
$parsedown = new Parsedown();
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

Router::get('/themen/{topic}', function($topic) use ($twig, $topics, $parsedown) {
    $slug = $topic;
    $topic = $topics[$slug];
    $content = $parsedown->text(file_get_contents(__DIR__ . "/../data/pages/themen/" . $slug . ".md"));
    $page = [
        "title" => $topic["title"],
        "description" => $topic["description"],
        "OG" => "/img/og/default.png",
        "menu_color" => "white"
    ];
    return $twig->render("topic.html" , ["page" => $page, "environment" => $_ENV, "topics" => $topics, "topic" => $topic, "content" => $content]);
});

Router::get('/kandis', function() use ($twig, $kandis, $topics) {
    $page = [
        "title" => "Unser Team für Zürich",
        "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora magnam ratione quos non voluptates est laborum perferendis, quibusdam, omnis modi debitis voluptate adipisci vero! Nulla nisi harum quaerat modi sint!",
        "OG" => "/img/og/default.png",
        "menu_color" => "white"
    ];
    array_multisort(
        array_column($kandis, 'bisher'), SORT_DESC,
        array_column($kandis, 'wknr'), SORT_ASC,
        array_column($kandis, 'listenplatz'), SORT_ASC,
        $kandis);
    return $twig->render("kandis.html" , ["page" => $page, "environment" => $_ENV, "kandis" => $kandis, "topics" => $topics]);
});

Router::get('/kandi/{slug}', function($slug) {
    header("Location: /kandis?kandi=" . $slug);
});