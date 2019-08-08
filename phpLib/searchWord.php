<?php

namespace MyWord;

class SearchWord{
    private $_db;

    public function __construct(){
        try {
            $this->_db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
            $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
          } catch (\PDOException $e) {
            echo mb_convert_encoding($e->getMessage(), "UTF-8", "Shift-JIS");
            exit;
          }
    }

    public function post(){
      switch ($_POST['mode']) {
        case 'update':
          return $this->_update();
        case 'create':
          return $this->_create();
        case 'delete':
          return $this->_delete();
      }
    }

    private function _create(){
        if(!isset($_POST['word'])  || $_POST['word'] === '' ){
          throw new \Exception('[create] word not set!');
        }
        //存在チェック
        $sql = sprintf("select count(*) as ExistFlag from search_word_list where word = '%s'",$_POST['word']);
        $stmt = $this->_db->query($sql);
        $Exist = $stmt->fetchColumn();
        if($Exist){
          return false;
        }
    
        $sql = "insert into search_word_list (word) values (:word)";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([':word' => $_POST['word']]);
    
        return[
          'id' => $this->_db->lastInsertId()
        ];
    }

    private function _delete(){
        if(!isset($_POST['id'])  || $_POST['id'] === '' ){
          throw new \Exception('[create] word not set!');
        }

        $sql = "delete from search_word_list where id = (:id)";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([':id' => $_POST['id']]);

        $sql = "delete from each_news_contents where wordID = (:id)";
        $stmt = $this->_db->prepare($sql);
        $stmt->execute([':id' => $_POST['id']]);

        return[];
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

    public function getActiveAll() {
      $stmt = $this->_db->query("select * from search_word_list where state = 1 order by id Asc");
      return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getAll() {
      $stmt = $this->_db->query("select * from search_word_list order by state desc , id asc");
      return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

     //記事をインサートする
    public function InsertEachNewsContent($searchWord,$wordID,$News_title, $News_content, $News_img,$News_URL, $News_DeliveryDate){
      
      $sql = "insert into each_news_contents (word,wordID,title,content,imgsrc,URL,delivery_date) 
                                        values(:word,:wordID,:title,:content,:imgsrc,:URL,:delivery_date)";
      $stmt = $this->_db->prepare($sql);
      $stmt->execute([':word' => $searchWord,
                      ':wordID' => $wordID,
                      ':title' => $News_title,
                      ':content' => $News_content,
                      ':imgsrc' => $News_img,
                      ':URL' => $News_URL,
                      ':delivery_date' => $News_DeliveryDate
                      ]);
    }

    //記事が既に存在するかを確認する。
    public function ExistNewsContent($searchWord,$News_title){
      $sql =sprintf("select count(*) as DataCount from each_news_contents where word = '%s' and title = '%s'",$searchWord,$News_title);
      $stmt = $this->_db->query($sql);
      $result = $stmt->fetchColumn();
      return $result;
     }  

     //最新記事のトップ８を取得
     public function getWordNews($searchWord){
      $sql =sprintf("select * from each_news_contents where word = '%s' order by UNIX_TIMESTAMP(ins_date) DESC limit 8",$searchWord);
      $stmt = $this->_db->query($sql);
      $result = $stmt->fetchAll();
      return $result;
     }

    //DBから記事を取得
    public function createNewsFromDB($seq){
      $sql =sprintf("select * from each_news_contents where seq = '%d' order by UNIX_TIMESTAMP(ins_date) DESC limit 8",$seq);
      $stmt = $this->_db->query($sql);
      $result = $stmt->fetchAll();
      $result = $result['0'];
      return $result;
    }
}