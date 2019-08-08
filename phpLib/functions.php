<?php



function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

//検索ワードを引数とし、YahooのニューストピックスURLを生成
function EachWordURL($w){
  $yahooURLStart = 'https://news.yahoo.co.jp/search/?p=';
  $yahooURLEnd = '&ei=utf-8&fr=news_sw';
  
  $URLEncode = urlencode($w);
  $YahooEachNewsURL = $yahooURLStart.$URLEncode.$yahooURLEnd;
  return $YahooEachNewsURL;
}
