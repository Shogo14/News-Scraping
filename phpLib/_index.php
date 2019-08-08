<?php
require_once("phpQuery-onefile.php");

$YahooURL = 'https://news.yahoo.co.jp/';
$yahooURLStart = 'https://news.yahoo.co.jp/search/?p=';
$yahooURLEnd = '&ei=utf-8&fr=news_sw';
?>

<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>NewsScraping</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark mt-3 mb-3">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav4" aria-controls="navbarNav4" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">NewsScraping</a>
        <div class="collapse navbar-collapse" id="navbarNav4">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="news.php">News</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search.php">Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dataList.php">DataList</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Export
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">サマリーデータ出力</a>
                        <a class="dropdown-item" href="#">明細データ出力</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Yahoo news からのスクレイピング-->
    <?php
    $context = stream_context_create(
      array(
        'http'      => array(
          'follow_location'   => true,
          'ignore_errors'     => true
        ),
        'https'      => array(
          'follow_location'   => true,
          'ignore_errors'     => true
        )
      )
    );

    $HTMLData = file_get_contents($YahooURL,false, $context);
    $doc = phpQuery::newDocument(mb_convert_encoding($HTMLData, 'HTML-ENTITIES', 'UTF-8'));
    $topicLists = $doc['ul.topics_list']->find('li');
    ?>
    <?php foreach ($topicLists as $value):
       $topic = pq($value)->find('a')->text();
       $topic = preg_replace('/new\s*$/i', '', $topic);
       $LinkURL = pq($value)->find('a')->attr("href");
    ?>
       <p><a href="<?= $LinkURL?>"><?= $topic; ?></a></p>

    <?php  endforeach ?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
