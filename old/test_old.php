<?php
ini_set( 'display_errors', 1 );
require_once('phpLib/phpQuery-onefile.php');
require_once('phpLib/Todo.php');
require_once('phpLib/WebScraping.php');

$WebScraping = new WebScraping();

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

    <div class="tab_wrapper">
      <div class="container">
        <input id="tab1" type="radio" name="tab_btn" checked>
        <input id="tab2" type="radio" name="tab_btn">
        <input id="tab3" type="radio" name="tab_btn">
      
        <div class="tab_area">
          <label class="tab_label active" for="tab1" data-id="panel1">tab1</label>
          <label class="tab_label" for="tab2" data-id="panel2">tab2</label>
          <label class="tab_label" for="tab3" data-id="panel3">tab3</label>
        </div>
      </div>
    </div>

    <?php 
    $test = $WebScraping->getContentQuery('https://headlines.yahoo.co.jp/hl?a=20190619-00000003-rorock-musi');
    var_dump($test);
    ?>

        <div class="clear"></div>
      </div>
    </div>

    <footer>News Scraping</footer>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="js/topic.js"></script>
  <script type="text/javascript">
  $(function(){
    $('.tab_label').click(function(){
      //タブの切り替え始まり
      var tabs = $('.tab_label');
      tabs.each(function(){
          $(this).removeClass('active');
      })
      $(this).addClass('active');
      //タブの切り替え終わり

      //パネルの切り替え始まり
      var PanelId = $(this).data('id');
      var Panels = $('.panel');

      Panels.each(function(){
        $(this).removeClass('show');
      })
      console.log(PanelId);
      $('#'+PanelId).addClass('show');
      //パネルの切り替え終わり
    })
  });
  </script>
  
  </body>
</html>
