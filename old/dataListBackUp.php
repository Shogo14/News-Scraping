<?php

session_start();

require_once('phpLib/config.php');
require_once('phpLib/functions.php');
require_once('phpLib/Todo.php');

// get todos
$todoApp = new \MyApp\Todo();
$todos = $todoApp->getAll();
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
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark mt-3 mb-3">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav4" aria-controls="navbarNav4" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">NewsScraping</a>
        <div class="collapse navbar-collapse" id="navbarNav4">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">TopNews</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="news.php">MyNews</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search.php">Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">DataList</a>
                </li>
            </ul>
        </div>
      </nav>

      <div id="container">
        <h1>Todos</h1>
        <!-- Searchワード登録インプット-->
        <form action="" id="new_todo_form">
          <input type="text" id="new_todo" placeholder="What needs to be done?">
        </form>
        <!-- Searchワードリスト表示-->
        <ul id="todos">
          <?php foreach($todos as $todo): ?>
          <li id="todo_<?= h($todo->id); ?>" data-id="<?= h($todo->id); ?>">
            <input type="checkbox" class="update_todo" <?php if($todo->state === '1'){echo 'checked';}?>>
            <span class="todo_title <?php if($todo->state ==='1'){ echo 'done';} ?>"><?= h($todo->word); ?></span>
            <div class="delete_todo">x</div>
          </li>
        <?php endforeach; ?>

          <li id="todo_template" data-id="">
            <input type="checkbox" class="update_todo">
            <span class="todo_title"></span>
            <div class="delete_todo">x</div>
          </li>

        </ul>
      </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <input type="hidden" id="token" value="<?= h($_SESSION['token']); ?>">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="js/todo.js"></script>
  </body>
</html>
