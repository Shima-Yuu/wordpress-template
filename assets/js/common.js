
/*!--------------------------------------------------------------------------*
*　グローバル
*--------------------------------------------------------------------------*/
const $window = $(window);
const scroll_speed = 550;

$(function () {
  /*!--------------------------------------------------------------------------*
  * SPヘッダーメニュー
  *--------------------------------------------------------------------------*/
  const drawer_active_class = 'is-drawer_open';
  const $drawer_btn = $(".js--drawer_btn");
  const $drawer_cont = $(".js--drawer_cont");
  const $body = $("body");

  $drawer_btn.on('click', function () {
    if ($(this).hasClass(drawer_active_class)) { // ドロワーは閉じる時の処理
      $drawer_cont.add(this).removeClass(drawer_active_class);
      $body.removeClass(drawer_active_class).css({ "top": 0 });
    } else { // ドロワーは開く時の処理
      $drawer_cont.add(this).addClass(drawer_active_class);
      $body.addClass(drawer_active_class);
    }
  });

  /*!--------------------------------------------------------------------------*
  * アコーディオン処理
  *   アコーディオンのトリガーとなる要素・・・js--accordion-trigger
  *   アコーディオンのコンテンツ要素・・・js--accordion-open
  *--------------------------------------------------------------------------*/
  $('.js--accordion-trigger').on('click', function () {
    $(this).next().slideToggle();
    $(this).toggleClass('is-open');
  });

  /*!--------------------------------------------------------------------------*
  * タブ切り替え
  *   タブ切り替えのボタン及びコンテンツ要素を囲う親要素・・・js--tab-1
  *   タブ切り替えのボタン要素全て・・・js--tab-btn
  *   タブ切り替えコンテンツ要素・・・js--tab-cont
  *--------------------------------------------------------------------------*/
  const tabSwitching = (unique_class) => {
    $('.' + unique_class + ' .js--tab-btn').click(function () {
      const current_index = $('.' + unique_class + ' .js--tab-btn').index(this);
      $('.' + unique_class + ' .js--tab-btn,' + '.' + unique_class + ' .js--tab-cont').removeClass('is-active');
      $(this).addClass('is-active');
      $('.' + unique_class + ' .js--tab-cont').eq(current_index).addClass('is-active');
    });
  };
  for (let i = 0; i < 5; i++) {
    const trigger_class = 'js--tab-' + i;
    tabSwitching(trigger_class);
  };

  /*!--------------------------------------------------------------------------*
  * スムーズスクロール
  *--------------------------------------------------------------------------*/
  let header_height = ($(".js--header").length) ? $(".js--header").height() : 0;
  $('a[href^="#"]:not([href^="#tab_"])').click(function () {
    const href = $(this).attr("href");
    const target = $(href == "#" || href == "" ? 'html' : href);
    const position = target.offset().top - header_height;
    $("html, body").animate({ scrollTop: position }, scroll_speed, "swing");
    return false;
  });

  /*!--------------------------------------------------------------------------*
  * 要素をスクロールに応じてフェードで表示する
  *--------------------------------------------------------------------------*/
  const scrollAnimationClass = 'sa';
  const scrollAnimationShowClass = 'show';
  const triggerMarginDefault = 200; //スクロール量＞画面の下と要素の位置の差

  const scrollAnimationElm = document.querySelectorAll('.' + scrollAnimationClass);
  const scrollAnimationFunc = function () {
    const dataMargin = scrollAnimationClass + '_margin';
    const dataTrigger = scrollAnimationClass + '_trigger';
    const dataDelay = scrollAnimationClass + '_delay';
    for (var i = 0; i < scrollAnimationElm.length; i++) {
      let triggerMargin = triggerMarginDefault;
      const elm = scrollAnimationElm[i];
      let showPos = 0;
      if (elm.dataset[dataMargin] != null) {
        triggerMargin = parseInt(elm.dataset[dataMargin]);
      }
      if (elm.dataset[dataTrigger]) {
        showPos = document.querySelector(elm.dataset[dataTrigger]).getBoundingClientRect().top + triggerMargin;
      } else {
        showPos = elm.getBoundingClientRect().top + triggerMargin;
      }
      if (window.innerHeight > showPos) {
        var delay = (elm.dataset[dataDelay]) ? elm.dataset[dataDelay] : 0;
        setTimeout(function (index) {
          scrollAnimationElm[index].classList.add(scrollAnimationShowClass);
        }.bind(null, i), delay);
      }
    }
  }
  window.addEventListener('load', scrollAnimationFunc);
  window.addEventListener('scroll', scrollAnimationFunc);

  /*!--------------------------------------------------------------------------*
  *	 動画の遅延読み込み
  *--------------------------------------------------------------------------*/
  document.addEventListener('DOMContentLoaded', function () {
    var lazyVideos = [].slice.call(document.querySelectorAll('video.js--lazy'));

    if ('IntersectionObserver' in window) {
      var lazyVideoObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (video) {
          if (video.isIntersecting) {
            for (var source in video.target.children) {
              var videoSource = video.target.children[source];
              if (typeof videoSource.tagName === 'string' && videoSource.tagName === 'SOURCE') {
                videoSource.src = videoSource.dataset.src;
              }
            }

            video.target.load();
            video.target.classList.remove('lazy');
            lazyVideoObserver.unobserve(video.target);
          }
        });
      });

      lazyVideos.forEach(function (lazyVideo) {
        lazyVideoObserver.observe(lazyVideo);
      });
    }
  });

  /*!--------------------------------------------------------------------------*
  * ページ上部にスクロール(TOPに戻るボタン)
  *--------------------------------------------------------------------------*/
  const $btn_scroll_top = $('#js--btn-top');
  $btn_scroll_top.on('click', function () {
    $('body,html').animate(
      {
        scrollTop: 0,
      },
      scroll_speed
    );
  });

  /*!--------------------------------------------------------------------------*
  *	スクロールヒント
  *--------------------------------------------------------------------------*/
  if ($('.js--scroll-hint').length) {
    new ScrollHint('.js--scroll-hint', {
      i18n: {
        scrollable: 'スクロールできます'
      }
    });
  }
});


$window.on('load', function () {
  /*!--------------------------------------------------------------------------*
   * ページ遷移時にスムーススクロール (header分の高さを調整)
   *--------------------------------------------------------------------------*/
  let header_height = $('.js--header').length ? $('.js--header').height() : 0;
  const hash = $(location).attr('hash');
  if (hash) {
    const target_pos = $(hash).offset().top - header_height;
    $('html,body').animate({ scrollTop: target_pos }, scroll_speed);
  }
});


$window.on('scroll', function () {
  /*!--------------------------------------------------------------------------*
   * 追従エリア及びヘッダーのフェードイン
   *--------------------------------------------------------------------------*/
  const $btn_scroll_top = $('#js--btn-top');
  const fadeIn_trigger_pos = 200;
  if ($(this).scrollTop() > fadeIn_trigger_pos) {
    $btn_scroll_top.addClass('is-fadeIn');
  } else {
    $btn_scroll_top.removeClass('is-fadeIn');
  }
});


/*!--------------------------------------------------------------------------*
  *	指定した要素の高さを揃える
  *--------------------------------------------------------------------------*/
const timerID = {};
timerID.AH = undefined;
const autoH = '.js--autoH_';

const resizeAutoHeight = () => {
  if (timerID.AH) clearTimeout(timerID.AH);

  timerID.AH = setTimeout(() => {
    let n = 1;
    while (n < 10) {
      const elements = $(autoH + n);

      if (elements.length > 1) {
        let H = 0;

        elements.height("auto").each(function () {
          H = Math.max($(this).height(), H);
        }).height(H);
      }

      n++;
    }
  }, 50);
}

$window.on('load resize', resizeAutoHeight);