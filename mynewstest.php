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
            <a href="index.php">TopNews</a>
            <a href="#">MyNews</a>
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
    <div class="news_modal_wrapper">
        <div class="modal">
            <div class="close-modal">
                <i class="fa fa-2x fa-times"></i>
            </div>
            <article>
            <img src="../img/sample1.jpeg" alt="No image">
            <h1>test</h1>
            <a href="">Jump Link</a>
            <section>
                <p>
                ［北京　２０日　ロイター］ - 北朝鮮を２日間の日程で訪問している中国の習近平国家主席は２０日、金正恩朝鮮労働党委員長に対し、北朝鮮の非核化に向けた取り組みを前向きに評価する姿勢を示し、北朝鮮が米国と対話を行い、対話が成功することを世界は望んでいると述べた。 中国首脳の訪朝は１４年ぶりで、習氏にとっては国家主席就任後、初めて。習主席は金委員長との公式会談で、中朝間の伝統的な友好関係を強化し、朝鮮半島問題の解決に向けた政治プロセスを促進するために訪問したと述べた。 国営テレビによると、習氏は朝鮮半島の安全と安定の保全、および非核化に向けた北朝鮮の取り組みを「前向きに評価」すると表明。また、「朝鮮半島情勢は地域の平和と安定に重要である」との見解を示し、「国際社会は北朝鮮と米国が対話を行い、結果が得られることを望んでいる」と述べた。 ２月にハノイで行われた２回目の米朝首脳会談が物別れに終わってから、北朝鮮は一部兵器実験を再開し、米国が一段と柔軟な姿勢を示さなければ「望まざる結果」を招くと警告。習主席は金委員長に対し、中国には北朝鮮の安全と発展に関する懸念の解消に向けた支援を行う用意があると述べた。 金委員長は習主席に対し、北朝鮮は過去１年ほどの間に緊張の高まりの回避に向けたさまざまな措置を取ってきたと指摘。「ただ、先方から前向きな反応は得られなかった。これは北朝鮮が望んでいたことではなかった」と述べた。その上で「北朝鮮には忍耐強く対応する用意がある。同時に、先方も北朝鮮に譲歩し、双方の懸念に対応する解決法を追求し、朝鮮半島問題を巡る対話プロセスを促進することを望んでいる」と話した。 習主席はトランプ米大統領と来週の大阪で開かれる２０カ国・地域（Ｇ２０）首脳会議に合わせて会談する予定。今回の中朝首脳会談はその直前に実現した。
                </p>
            </section>
            </article>
        </div>
    </div>
    <!-- modal end -->
    <footer>News Scraping</footer>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <!-- サイドバー用のプラグイン -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-sidebar/3.1.0/jquery.sidebar.min.js"></script>
    <script type="text/javascript" src="js/topic.js"></script>
    <script src="js/SearchWord.js"></script>
  </body>
</html>
