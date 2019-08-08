$(function() {
    //ajaxで記事を取得
    $('.topic').each(function(){
        var $seq = $(this).data('seq');
        var $imgDom = $(this).find('img');
        var $titleDom = $(this).find('h1');
        var $contentDom = $(this).find('section').find('p');
        var $jumpLink = $(this).find('a.jump_link');
        
        // ajax処理
        $.get('_ajax2.php', {
        seq: $seq
        }, function(res) {
            $imgDom.attr('src',res['imgsrc']);
            $titleDom.text(res['title']);
            $contentDom.text(res['content']);
            $jumpLink.attr('href',res['URL']);
        })
    })

    //タブをクリックした時にパネルを切り替える
    $('.tab_label').click(function(){
        var tabs = $('.tab_label');
        tabs.each(function(){
            $(this).removeClass('active');
        })
        $(this).addClass('active');

        var PanelId = $(this).data('id');
        var Panels = $('.container').find('.panel');
  
        Panels.each(function(){
          $(this).removeClass('show');
        })
        $('#'+PanelId).addClass('show');
      })


    //サイドバーの展開
    $(document).click(function (event) {
        
    if(!$(event.target).closest('.sidebar').length) {
    console.log('外側がクリックされました。');
        var $Othis = $('#side-bar');
        var Oaction = $Othis.attr("data-action");
        var Osidebar = $(".sidebar.left");
        if(Osidebar.hasClass('open')){
            Osidebar.toggleClass('open');
            Osidebar.trigger("sidebar:" + Oaction);
            $('#side-bar').toggleClass('swithOpen');
        return false;
        }else{
        }
    } else {
    console.log('内側がクリックされました。');
    }

    });
    // 向き
    var sides = ["left", "top", "right", "bottom"];

    // サイドバーの初期化
    for (var i = 0; i < sides.length; ++i) {
        var cSide = sides[i];
        $(".sidebar." + cSide).sidebar({side: cSide});
    }

    // ボタンのクリックにより...
    $(".btn[data-action]").on("click", function () {
        var $this = $('#side-bar');
        var action = $this.attr("data-action");
        var sidebar = $(".sidebar.left");

        sidebar.toggleClass('open');
        sidebar.trigger("sidebar:" + action);
        $('#side-bar').toggleClass('swithOpen');
        return false;
    });

    //jump_linkをクリックした時新規タブで開く
    $('.jump_link').on('click',function(e){
        e.preventDefault();
        $URL = $(this).attr('href');
        window.open($URL, '_blank');
    });

    //モーダルウインドウを閉じる
    $('.close-modal').click(function(){
    $('.news_modal_wrapper').fadeOut();
  });
});