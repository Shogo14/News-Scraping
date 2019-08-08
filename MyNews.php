<?php
ini_set( 'display_errors', 1 );
require_once('phpLib/phpQuery-onefile.php');
require_once('phpLib/Todo.php');
require_once('phpLib/WebScraping.php');
require_once('phpLib/searchWord.php');

$WebScraping = new WebScraping();
$WordApp = new \MyWord\SearchWord();
$Words = $WordApp->getActiveAll();
$picks = $WordApp->getAll();

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
            <!-- <a href="index.php">TopNews</a>
            <a href="#">MyNews</a> -->
            <!-- <a href="dataList.php">NewsWordList</a> -->
            <div class="header-login">ログイン</div>
          </div>  
      </div>
    </header>

    <!-- タブの作成 -->
    <div class="tab_wrapper">
      <div class="container">
        <div class="tab_area">
          <?php
          //TOPNEWS
          echo $WebScraping->tabRadioCreate(0,0,'TOPNEWS');
          // タブの作成
          $index = 1;
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
    <!-- top-wrapper start -->
    <div class="top-wrapper">
      <div class="container">
        <?php 
        //TOPのパネル
        echo '<div id="panel-0" class="panel show">';
        
        $TOPWords = $WordApp->getWordNews('TOPNEWS');

        foreach($TOPWords as $TOPWord){
          $seq = $TOPWord['seq'];
          $Content = $WebScraping->createEmptyWindow($seq);
          echo $Content;
        } 
        
        echo '</div>';
          //パネルの作成
          $index = 1;
          foreach($Words as $Word){
            $word = $Word->word;
            $wordID = $Word->id;
            $topicWords = $WordApp->getWordNews($word);

            echo '<div id="panel-'.$wordID.'" class="panel">';
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
        <!-- サイドバー -->
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
            <!-- template start -->
            <li id="word_template" data-id="">
              <input type="checkbox" class="update_word" checked>
              <span class="word_title"></span>
              <span>
                <i class="fas fa-times-circle delete_word"></i>
              </span>
            </li>
            <!-- template end -->
          </ul>
        </div>
        <!-- side bar -->
        <i id="side-bar" class="fas fa-chevron-circle-right fa-2x barOpen btn" data-action="toggle" data-side="left"></i>
      </div>
      <!-- conteiner end -->
    </div>
    <!-- top-wrapper end -->

    <!-- modal start -->
    <!-- <div class="news_modal">
      <article>
        <img src="" alt="">
        <h1>test</h1>
        <a href="img/sample1.jpeg"></a>
        <section>
          <p>
          testetsetsetetwewewefwewetwe
          </p>
        </section>
      </article>
    </div> -->
    <!-- modal end -->
    <footer>News Scraping</footer>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <!-- サイドバー用のプラグイン -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-sidebar/3.1.0/jquery.sidebar.min.js"></script>
    <script type="text/javascript" src="js/topic.js"></script>
    <script src="js/SearchWord.js"></script>
  </body>
</html>
