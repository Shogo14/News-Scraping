<?php

session_start();

require_once('phpLib/config.php');
require_once('phpLib/functions.php');
require_once('phpLib/Todo.php');
require_once('phpLib/searchWord.php');

$todoApp = new \MyApp\Todo();
$SearchWord = new \MyWord\SearchWord();

try{
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $res = $todoApp->post();
    header('Content-Type: application/json');
      echo json_encode($res);
    exit;
  }elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
    $seq = $_GET['seq'];
    $res = $SearchWord->createNewsFromDB($seq);
    header('Content-Type: application/json');
      echo json_encode($res);
    exit;
  }
}catch(Exception $e){
  header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
  echo $e->getMessage();
  exit;
}

