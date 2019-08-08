<?php

set_time_limit(180);
ini_set('display_errors',1);

require_once(dirname(__DIR__).'/phpLib/config.php');
require_once(dirname(__DIR__).'/phpLib/WebScraping.php');
require_once(dirname(__DIR__).'/phpLib/searchWord.php');

$WordApp = new \MyWord\SearchWord();
$Words = $WordApp->getActiveAll();
$WebScraping = New WebScraping();

//search_word_listからstate=0のみ検索
foreach($Words as $Word){
    $searchWord =  $Word->word;
    $wordID = $Word->id;
    $URLs = $WebScraping->getEachNewsTopicURLList($searchWord);
    foreach($URLs as $URL){
      $link = pq($URL)->find('a')->attr('href');
      //title用のURLを取得
      $News_URL = $WebScraping->getTopNews($link);
      if(empty($News_URL)){
        $News_URL = $link;
      }

      $News_title = $WebScraping->getNewsTitle($News_URL);
      $resultCount = $WordApp->ExistNewsContent($searchWord,$News_title);
      
      if($resultCount){
        //データがある場合は何もしない
      }else{
        //データが存在しない場合はインサート
        $News_Array = $WebScraping->createNewsJson($News_URL);

        $News_content = $News_Array['content'];
        $News_img = $News_Array['img'];
        $News_DeliveryDate = $News_Array['DeliveryDate'];
        $WordApp->InsertEachNewsContent($searchWord,$wordID,$News_title, $News_content, $News_img,$News_URL, $News_DeliveryDate);
      } 
    }
}

//yahooのトップページから主要トピックスをDBにインサート
$TopURLs = $WebScraping->getNewsTopicURLList();
foreach($TopURLs as $TopURL){
    $Toplink = pq($TopURL)->find('a')->attr('href');
    $NextLink = $WebScraping->getTopNews($Toplink);
    if(empty($NextLink)){
        $NextLink = $Toplink;
    }
    $TopWord = 'TOPNEWS';
    $Top_title = $WebScraping->getNewsTitle($NextLink);

    $resultCount = $WordApp->ExistNewsContent($TopWord,$Top_title);
    if($resultCount){
    //データがある場合は何もしない
    }else{
        $Top_ID = 0;
        $Top_content = $WebScraping->getNewsContent($NextLink);
        $Top_img = $WebScraping->getImgSrc($NextLink);
        $Top_DeliveryDate = $WebScraping->getDeliveryDate($NextLink);
        $WordApp->InsertEachNewsContent($TopWord,$Top_ID,$Top_title, $Top_content, $Top_img,$NextLink, $Top_DeliveryDate);
    }
}