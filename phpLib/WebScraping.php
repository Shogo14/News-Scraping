  <?php
set_time_limit(120);

require_once(__DIR__ . '/phpQuery-onefile.php');
require_once(__DIR__ . '/functions.php');

class WebScraping{
  public $yahooURL = 'https://news.yahoo.co.jp/';
  public $yahooURLStart = 'https://news.yahoo.co.jp/search/?p=';
  public $yahooURLEnd = '&ei=utf-8&fr=news_sw';
  
  //URLからドキュメント部分を取得
  public function getContentQuery($URL){
    $Contents = @file_get_contents($URL);
    if($http_response_header[0] == 'HTTP/1.1 404 Not Found'){
      print '404 Not Foundです。';
    }else{
      $Document = phpQuery::newDocument(mb_convert_encoding($Contents, 'HTML-ENTITIES', 'UTF-8'));
      return $Document;
    }
  }

  //yahooのトップページから主要トピックスのURLを取得する
  public function getNewsTopicURLList(){
    $TopDoc = $this->getContentQuery($this->yahooURL);
    $URLs = $TopDoc['ul.topicsList_main']->find('li');
    return $URLs;
  }

   //検索ワードのニュースのURLを取得する
   public function getEachNewsTopicURLList($w){
     $EachURL = EachWordURL($w);
    $TopDoc = $this->getContentQuery($EachURL);
    $URLs = $TopDoc['div#NSm']->find('h2');
    return $URLs;
  }

  //「続きを読む」のリンクURLを取得
  public function getTopNews($NextURL){
    $Doc = $this->getContentQuery($NextURL);
    $NextURL = $Doc['.tpcNews_detailLink']->find('a')->attr('href');
    return $NextURL;
  }

  //ニュースの題名を取得
  public function getNewsTitle($URL){
    $Doc = $this->getContentQuery($URL);
    $NewsTitle = $Doc['#ym_newsarticle']->find('.hd')->find('h1')->text();
    return $NewsTitle;
  }

  //ニュースの本文を取得
  public function getNewsContent($URL){
    $Doc = $this->getContentQuery($URL);
    $Body = $Doc['.yjDirectSLinkTarget']->text();
    if(empty($Body)){
      $Body = $Doc['.mod-content']->find('.columArticle')->find('p')->text();
    }
    if(empty($Body)){
    }
    return $Body;
  }

  //画像のリンクを取得
  public function getImgSrc($URL){
      $vowels = array("background-image: url('", "');");
      $imgDoc = $this->getContentQuery($URL);
      $ImgSrc = $imgDoc['#tpcHeader']->find('div.tpcHeader_thumb')->find('p')->attr("style");

        if(empty($ImgSrc)){
          $ImgSrc = $imgDoc['div#ym_newsarticle']->find('div.paragraph')->find('img')->attr("src");
          if(empty($ImgSrc)){
            $ImgSrc = $imgDoc['article#content-body']->find('div.articleMainImage')->find('img')->attr("src");
          }
        }
      $ImgSrc = str_replace($vowels, "", $ImgSrc);
      return $ImgSrc;
  }

  //配信日次を取得
  public function getDeliveryDate($URL){
    $replacement = '/[^0-9\/: ]/';
    $CurrentYear = date('Y');

    $DelDateDoc = $this->getContentQuery($URL);
    $strDeliveryDate = $DelDateDoc['div.hdLogoWrap']->find('.source')->text();
    $strDeliveryDate = preg_replace($replacement,'',$strDeliveryDate);
    $DeliveryDateTime = date('Y-m-d H:i:s',strtotime($CurrentYear.'/'.$strDeliveryDate));
     //$DeliveryDateTime = $CurrentYear.'/'.$strDeliveryDate;
    return $DeliveryDateTime;
  }

  //次へのリンクを取得
  public function getNextLink($BaseURL){
    $Doc = $this->getContentQuery($BaseURL);
    $NextHref = $Doc['.next']->find('a')->attr('href');
    if(empty($NextHref)){

    }else{
      if(false === strpos($NextHref , 'https://')){
        $NextHref = 'https://sports.yahoo.co.jp'.$NextHref;
      }
    }
    return $NextHref;
  }

  //記事本文全てを取得
  public function getAllNewsContents($URL){
    $SumContent = Null;
    $Content = Null;
    $Link = $URL;

    do{
      $Content = $this->getNewsContent($Link);
      $SumContent = $SumContent.$Content;
      $Link = $this->getNextLink($Link);
    }while($Link != "");

    return $SumContent;
  }

  //記事の枠組みを空白で返す
  public function createEmptyWindow($seq){
    $WindowHtml = Null;
    $WindowHtml .= '<div class="topic" data-seq="'.$seq.'"><article>';
    $WindowHtml .= '<img src="" alt="No Image"><h1></h1><a class="jump_link" href="">Jump link</a>';
    $WindowHtml .= '<section><p></p></section>';
    $WindowHtml .= '</article></div>';
    return $WindowHtml;
 }
 public function createTopNewsAndWindow($img,$title,$content,$URL){
  $WindowHtml = Null;
  $WindowHtml .= '<div class="topic"><article><img src="'.$img.'" alt="No Image">';
  $WindowHtml .= '<h1>'.$title.'</h1><a class="jump_link" href="'.$URL.'">Jump link</a>';
  $WindowHtml .= '<section><p>'.$content.'</p></section></article></div>';
  return $WindowHtml;
}

 //記事の題名、本文、画像を返す
 public function createNewsJson($BaseURL){
  $URL = $this->getTopNews($BaseURL);
  if(empty($URL)){
    $URL = $BaseURL;
  }
  $NewsTitle = $this->getNewsTitle($URL);
  $NewsContent = $this->getAllNewsContents($URL);
  $NewsImg = $this->getImgSrc($URL);
  $NewsDelDate = $this->getDeliveryDate($URL);

  $News_Array = [
    'title' => $NewsTitle,
    'content' => $NewsContent,
    'img' => $NewsImg,
    'DeliveryDate' => $NewsDelDate
  ];

  return $News_Array;
 }

 //タブラジオを作る
 public function tabRadioCreate($index,$wordID,$word){
   $tabRadioHtml = null;

   $tabRadioHtml .= '<input id="tab'.$index.'" type="radio" name="tab_btn" ';
   if($index == 0){
     $tabRadioHtml .= 'checked><label class="tab_label active" for="tab';
   }else{
     $tabRadioHtml .= '><label class="tab_label" for="tab';
   }
   $tabRadioHtml .= $index.'" data-id="panel-'.$wordID.'">'.$word.'</label>';

   return $tabRadioHtml;
 }
 
}
