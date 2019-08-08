$(function(){
    'use strict';

    //単語の追加
  $('#new_word_form').on('submit', function() {
         var SeachWord = $('#new_word').val();
        $.post('_ajaxWord.php',{
            word: SeachWord,
            mode: 'create'
        },function(res){
          if(!res){
            alert('「'+SeachWord+'」は既に登録済みです。')
            return false;
          }
          //liを追加
          var $li = $('#word_template').clone();
          $li
            .attr('id','word_' + res.id)
            .data('id',res.id)
            .find('.word_title').text(SeachWord);
          $('#words').prepend($li.fadeIn(800));
          $('#new_word').val('').focus();
        });
        //画面遷移をなくすためにFalse
        return false;
    });

    //単語の削除
  $('#words').on('click', '.delete_word', function() {
        var id = $(this).parents('li').data('id');
        $.post('_ajaxWord.php',{
            id: id,
            mode: 'delete'
        },function(){
            $('#word_' + id).fadeOut(800);
        })
    });

  //単語の反映更新
  $('#words').on('click', '.update_word', function() {
      // idを取得
      var id = $(this).parents('li').data('id');
      // ajax処理
      $.post('_ajaxWord.php', {
        id: id,
        mode: 'update'
      }, function(res) {
        if (res.state === '1') {
          $('#word_' + id).find('.word_title').removeClass('hide');
        } else {
          $('#word_' + id).find('.word_title').addClass('hide');
        }
      })
    });

});