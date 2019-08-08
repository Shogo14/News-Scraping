<?php

set_time_limit(120);
session_start();

require_once('phpLib/config.php');
require_once('phpLib/functions.php');
require_once('phpLib/phpQuery-onefile.php');
require_once('phpLib/Todo.php');
require_once('phpLib/WebScraping.php');

// get todos
$todoApp = new \MyApp\Todo();
$NewsTopics = $todoApp->getActiveAll();


$WebScraping = new WebScraping();

?>

<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <title>NewsScraping</title>
  </head>
  <body>
    <header>
      <div class="container">
        <div class="header-title">
          <a class="site-title" href="#">NewsScraping</a>
        </div>
        <div class="menu-list">
            <a href="index.php">TopNews</a>
            <a href="news.php">MyNews</a>
            <a href="dataList.php">NewsWordList</a>
            <div class="header-login">ログイン</div>
          </div>  
      </div>
    </header>

      <!-- タブを作成 -->
      <ul id = "ulTab" class="nav nav-tabs">
          <?php foreach($NewsTopics as $NewsTopic):?>
            <?php $TopicId =  preg_replace("/( |　)/", "", $NewsTopic->word ); ?>
            <li class="tabClass nav-item menu_item" data-id="<?= $TopicId ?>">
              <a href="" class="nav-link navbar-default" data-toggle="tab">
                <?= h($NewsTopic->word)?>
              </a>
            </li>
          <?php endforeach; ?>
       </ul>


       <!--タブの中身-->
<div class="tab-content">
  <?php
  //Search wordの数だけ記事を取得してくる
  foreach($NewsTopics as $NewsTopic){
  $TopicId =  preg_replace("/( |　)/", "", $NewsTopic->word );
      if(empty(h($TopicId))){
        continue;
      }
        $YahooSearchNewsURL = CreateWordURL(h($NewsTopic->word));
        $HTMLData = file_get_contents($YahooSearchNewsURL);
        $doc = phpQuery::newDocument($HTMLData);
        $topicsLists = $doc['div#NSm']->find('h2');

        $TopicCount = count($topicsLists);
        $TopicIndex = 1;
        $TopicHtml = "";

        echo '<div class="content" id='.$TopicId.'>';

        //Wordごとのトピック全件をScrapingして記事を取得
          foreach ($topicsLists as $topic){
             $TopicHtml = $TopicHtml.$WebScraping->createCard($topic,$TopicIndex,$TopicCount);
             $TopicIndex =$TopicIndex +1;
          }
          if(isset($TopicHtml)){
            echo $TopicHtml;
          }

        echo '</div>';
  }?>
</div>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
    <script src="js/tab.js"></script>

  </body>
</html>
