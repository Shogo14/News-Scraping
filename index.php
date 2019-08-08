<?php

require_once('phpLib/phpQuery-onefile.php');
require_once('phpLib/Todo.php');
require_once('phpLib/WebScraping.php');
require_once('phpLib/searchWord.php');

$YahooURL = 'https://news.yahoo.co.jp/';
$yahooURLStart = 'https://news.yahoo.co.jp/search/?p=';
$yahooURLEnd = '&ei=utf-8&fr=news_sw';

$WebScraping = new WebScraping();
$SearchWord = new \MyWord\SearchWord();
$Words = $SearchWord->getActiveAll();
$picks = $SearchWord->getAll();

?>

<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <title>NewsScraping</title>
  </head>
  <body>
    <!--メニューを作成-->
    <header>
      <div class="container">
        <div class="header-title">
          <a class="site-title" href="#">NewsScraping</a>
        </div>
        <div class="menu-list">
            <a href="#">TopNews</a>
            <a href="MyNews.php">MyNews</a>
            <div class="header-login">ログイン</div>
          </div>  
      </div>
    </header>
    <!-- Yahoo news からのスクレイピング-->
    <!-- top-wrapper start -->
    <div class="top-wrapper">
      <div class="container">
        <?php 
        $TopWord = 'TOPNEWS';
        $TopNewsList = $SearchWord->getWordNews($TopWord);
        foreach($TopNewsList as $TopNews){
          $Top_img = $TopNews['imgsrc'];
          $Top_title = $TopNews['title'];
          $Top_content = $TopNews['content'];
          $Top_URL = $TopNews['URL'];

          $TopHTML = $WebScraping->createTopNewsAndWindow($Top_img,$Top_title,$Top_content,$Top_URL);
          echo $TopHTML;
        }
        ?>
      </div>
      <div class="clear">
      </div>
      <!-- サイドバー start-->
      <div class="sidebar left">
        <h1 class="list_title">Pick Word List</h1>
        <form action="" id="new_word_form">
            <input type="text" id="new_word" placeholder="ワードを登録">
        </form>
        <ul id ="words">
          <?php foreach($picks as $pick): ?>
            <li id="word_<?= h($pick->id); ?>" data-id="<?= h($pick->id); ?>">
              <input type="checkbox" class="update_word" <?php if($pick->state === '1'){echo 'checked';}?>>
              <span class="word_title <?php if($pick->state ==='0'){ echo 'hide';} ?>"><?= h($pick->word); ?></span>
              <span>
                <i class="fas fa-times-circle delete_word"></i>
              </span>
            </li>
          <div class="clear"></div>
          <?php endforeach; ?>
          <li id="word_template" data-id="">
            <input type="checkbox" class="update_word" checked>
            <span class="word_title"></span>
            <span>
              <i class="fas fa-times-circle delete_word"></i>
            </span>
          </li>
        </ul>
        <i id="side-bar" class="fas fa-chevron-circle-right fa-2x barOpen btn" data-action="toggle" data-side="left"></i>
      </div>
      <!-- サイドバー end -->
    </div>
    <!-- top-wrapper end -->
 <footer>News Scraping</footer>
  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
  <!-- サイドバー用のプラグイン -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-sidebar/3.1.0/jquery.sidebar.min.js"></script>
  <script type="text/javascript" src="js/topic.js"></script>  
  <script src="js/SearchWord.js"></script>
  </body>
</html>
