<?php

session_start();

require_once('phpLib/config.php');
require_once('phpLib/functions.php');
require_once('phpLib/Todo.php');
require_once('phpLib/searchWord.php');

$searchWord = new \MyWord\SearchWord();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $res = $searchWord->post();
    header('Content-Type: application/json');
      echo json_encode($res);
    exit;
  } catch (Exception $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo $e->getMessage();
    exit;
  }
}
