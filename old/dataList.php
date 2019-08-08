<?php

session_start();

require_once('phpLib/config.php');
require_once('phpLib/functions.php');
require_once('phpLib/Todo.php');

// get todos
$todoApp = new \MyTodo\Todo();
$todos = $todoApp->getAll();
?>

<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/styles.css">
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

    <div class="top-wrapper">
      <div class="container">
        <div id="container">
          <h1>Pick Word List</h1>
          <!-- Searchワード登録インプット-->
          <form action="" id="new_word_form">
            <input type="text" id="new_word" placeholder="気になるワードを登録してください">
          </form>
          <!-- Searchワードリスト表示-->
          <ul id="words">
            <?php foreach($todos as $todo): ?>
            <li id="word_<?= h($todo->id); ?>" data-id="<?= h($todo->id); ?>">
              <input type="checkbox" class="update_word" <?php if($todo->state === '1'){echo 'checked';}?>>
              <span class="word_title <?php if($todo->state ==='1'){ echo 'done';} ?>"><?= h($todo->word); ?></span>
              <div class="delete_word">x</div>
            </li>
          <?php endforeach; ?>
  
            <li id="todo_template" data-id="">
              <input type="checkbox" class="update_todo">
              <span class="todo_title"></span>
              <div class="delete_todo">x</div>
            </li>
  
          </ul>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <!-- <script src="js/todo.js"></script> -->
    <script src="js/SearchWord.js"></script>
  </body>
</html>
