<?php

namespace MyTodo;

require_once('phpLib/config.php');

class Todo {
  private $_db;

  
  public function __construct() {
    $this->_createToken();

    try {
      $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
      $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo mb_convert_encoding($e->getMessage(), "UTF-8", "Shift-JIS");
      echo " test";
      exit;
    }
  }

  private function _createToken(){
    if(!isset($_SESSION['token'])){
      $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    }
  }

  public function getAll() {
    $stmt = $this->_db->query("select * from search_word_list order by id desc");
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function getActiveAll() {
    $stmt = $this->_db->query("select * from search_word_list where state = 0 order by id desc");
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  public function post(){
    $this->_validateToken();

    if(!isset($_POST['mode'])){
      throw new \Exception('mode not set!');
    }
    switch ($_POST['mode']) {
      case 'update':
        return $this->_update();
      case 'create':
        return $this->_create();
      case 'delete':
        return $this->_delete();
    }
  }

  private function _validateToken(){
    if(!isset($_SESSION['token'])){
      throw new \Exception('not session token!!');
    }elseif(!isset($_POST['token'])){
      throw new \Exception('not post token!!');
    }elseif($_SESSION['token'] !== $_POST['token']){
      throw new \Exception('not equals token!!');
    }
  }

  private function _update(){
    if(!isset($_POST['id'])){
      throw new \Exception('[update] id not set!');
    }

    $this->_db->beginTransaction();

    $sql = sprintf("update search_word_list set state = (state + 1) %% 2 where id = %d", $_POST['id']);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute();

    $sql =sprintf("select state from search_word_list where id = %d",$_POST['id']);
    $stmt = $this->_db->query($sql);
    $state = $stmt->fetchColumn();

    $this->_db->commit();

    return[
      'state' => $state
    ];
  }

  private function _create(){
    if(!isset($_POST['word'])  || $_POST['word'] === '' ){
      throw new \Exception('[create] word not set!');
    }

    $sql = "insert into search_word_list (word) values (:word)";
    $stmt = $this->_db->prepare($sql);
    $stmt->execute([':word' => $_POST['word']]);

    return[
      'id' => $this->_db->lastInsertId()
    ];
  }

  private function _delete(){
    if(!isset($_POST['id'])){
      throw new \Exception('[delete] id not set!');
    }

    $sql = sprintf("delete from search_word_list where id = %d", $_POST['id']);
    $stmt = $this->_db->prepare($sql);
    $stmt->execute();

    return[];
  }
}
