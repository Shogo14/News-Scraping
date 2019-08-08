<?php
ini_set( 'display_errors', 1 );
require_once('phpLib/phpQuery-onefile.php');
require_once('phpLib/Todo.php');
require_once('phpLib/WebScraping.php');
require_once('phpLib/searchWord.php');

$WebScraping = new WebScraping();
$WordApp = new \MyWord\SearchWord();
$Words = $WordApp->getActiveAll();

?>

<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/responsive.css">
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
            <a href="index.php">TopNews</a>
            <a href="news.php">MyNews</a>
            <a href="dataList.php">NewsWordList</a>
            <div class="header-login">ログイン</div>
          </div>  
      </div>
    </header>
    <!-- タブの作成 -->
    <div class="tab_wrapper">
      <div class="container">
        <div class="tab_area">
        <?php
        // タブの作成
        $index = 0;
        foreach($Words as $Word){
          $wordID = $Word->id;
          $word = $Word->word;
          echo $WebScraping->tabRadioCreate($index,$wordID,$word);
          $index++;
        } 
        ?>
        </div>
      </div>
    </div>
  <div class="top-wrapper">
      <div class="container">
      <?php 
      //パネルの作成
      $index = 0;
        foreach($Words as $Word){
        $word = $Word->word;
        $wordID = $Word->id;

          if($index == 0){
            echo '<div id="panel-'.$wordID.'" class="panel show">';
          }else{
            echo '<div id="panel-'.$wordID.'" class="panel">';
          }
          $topicWords = $WordApp->getWordNews($word);

          foreach($topicWords as $topicWord){
            $seq = $topicWord['seq'];
            $Content = $WebScraping->createEmptyWindow($seq);
            echo $Content;
          } 
            echo '</div>';
            $index++;
      }
      ?>
        <div class="clear"></div>
      </div>
    </div>
    <footer>News Scraping</footer>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/topic.js"></script>
  </body>
</html>
