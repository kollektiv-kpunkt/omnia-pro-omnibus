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

use Jaybizzle\CrawlerDetect\CrawlerDetect;
$detect = new CrawlerDetect;
$host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

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

Router::get('/themen/{topic}', function($topic) use ($twig, $topics, $parsedown, $actual_link) {
    $slug = $topic;
    $topic = $topics[$slug];
    $content = $parsedown->text(file_get_contents(__DIR__ . "/../data/pages/themen/" . $slug . ".md"));
    $page = [
        "title" => $topic["title"],
        "description" => $topic["description"],
        "OG" => "/img/og/default.png",
        "menu_color" => "white"
    ];
    return $twig->render("topic.html" , ["page" => $page, "environment" => $_ENV, "topics" => $topics, "topic" => $topic, "content" => $content, "url" => $actual_link]);
});

Router::get('/kandis', function() use ($twig, $kandis, $topics) {
    $page = [
        "title" => "Unser Team für Zürich",
        "description" => "Wir setzen uns im ganzen Kanton Zürich für konsequent Linke Politik ein. Es gibt kein ruhiges Hinterland und noch zu wenig linke Stadtvertretungen! Daher am 12. Februar folgende JUSO’s auf der SP-Liste 2 wählen.",
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

Router::get('/themen', function() use ($twig, $topics) {
    $page = [
        "title" => "Unser Engagement für Zürich",
        "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora magnam ratione quos non voluptates est laborum perferendis, quibusdam, omnis modi debitis voluptate adipisci vero! Nulla nisi harum quaerat modi sint!",
        "OG" => "/img/og/default.png",
        "menu_color" => "white"
    ];
    return $twig->render("themen.html" , ["page" => $page, "environment" => $_ENV, "topics" => $topics]);
});

Router::get('/kandi/{slug}', function($slug) use ($detect, $kandis, $twig, $host) {
    if ($detect->isCrawler()) {
        $kandi = $kandis[$slug];
        $page = [
            "title" => "{$kandi["vorname"]} {$kandi["nachname"]}",
            "description" => $kandi["quote"],
            "OG" => "{$host}/img/kandis/{$kandi["id"]}-small.jpg"
        ];
        return $twig->render("kandi-OG.html" , ["page" => $page, "environment" => $_ENV]);
        exit;
    } else {
        header("Location: /kandis?kandi=" . $slug);
    }
});

$defaultLayouts = [
    "spenden" => [
        "title" => "Unterstütze unseren Wahlkampf",
        "description" => "Wahlkampf ist teuer. Wir brauchen deine Unterstützung, damit wir unsere Ziele erreichen können.",
    ],
    "kontakt" => [
        "title" => "Kontakt"
    ],
    "impressum" => [
        "title" => "Impressum"
    ],
    "datenschutz" => [
        "title" => "Datenschutz"
    ],
];

foreach ($defaultLayouts as $slug => $layout) {
    $content = $parsedown->text(file_get_contents(__DIR__ . "/../data/pages/" . $slug . ".md"));
    Router::get("/{$slug}", function() use ($twig, $layout, $content, $topics) {
        $page = [
            "title" => $layout["title"],
            "description" => (isset($layout["description"])) ? $layout["description"] : "",
            "OG" => "/img/og/default.png",
            "menu_color" => "white"
        ];
        return $twig->render("default.html" , ["page" => $page, "environment" => $_ENV, "content" => $content, "topics" => $topics]);
    });
}

Router::get('/material-bestellen', function() use ($twig, $topics) {
    $page = [
        "title" => "Material bestellen",
        "description" => "Unterstütze uns, indem du unser Material bestellst.",
        "OG" => "/img/og/default.png",
        "menu_color" => "white"
    ];
    ob_start();
    $config = "material-bestellen";
    include __DIR__ . "/../rendering/wh-form.php";
    $form = ob_get_clean();
    return $twig->render("material-bestellen.html" , ["page" => $page, "environment" => $_ENV, "topics" => $topics, "form" => $form]);
});