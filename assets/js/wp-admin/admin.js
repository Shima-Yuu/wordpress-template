/*!--------------------------------------------------------------------------*
*	
*	汎用的な機能を定義
*	
*--------------------------------------------------------------------------*/
var FUNC = FUNC || {};

(function () {
  var _ = FUNC;

  /*!--------------------------------------------------------------------------*
  *	タブ切り替え｜処理
  *--------------------------------------------------------------------------*/
  const tabBtnItemClass = 'js--tab-btn-item';
  const tabBtnActiveClass = 'js-tab-btn-active';
  const tabContItemClass = 'js--tab-cont-item'
  const tabContActiveClass = 'js-tab-cont-active';
  _.tab = (e) => {
    const btnElementClass = `.${tabBtnItemClass}`;
    const btnElements = Array.from(e.querySelectorAll(btnElementClass));
    const contElementClass = `.${tabContItemClass}`;
    const contElements = Array.from(e.nextElementSibling.querySelectorAll(contElementClass));

    const switchTabBtn = (e) => {
      const _ = e.target;
      let _index = btnElements.findIndex(btnElement => btnElement === _);
      btnElements.forEach(el => el.classList.remove(tabBtnActiveClass));
      btnElements[_index].classList.add(tabBtnActiveClass);

      return _index;
    }
    const switchTabCont = (_i) => {
      const _ = contElements[_i];
      contElements.forEach(el => el.classList.remove(tabContActiveClass));
      _.classList.add(tabContActiveClass);
    }
    const switchTabFunc = (e) => {
      if (e.target.classList.contains(tabBtnActiveClass)) return false;
      const _index = switchTabBtn(e);
      switchTabCont(_index);
    }
    for (let i = 0, len = btnElements.length; i < len; i++) {
      btnElements[i].addEventListener('click', (e) => switchTabFunc(e));
    }
  };

  /*!--------------------------------------------------------------------------*
  *	アコーディオン｜処理
  *--------------------------------------------------------------------------*/
  const accordionOpenClass = 'js--accordion-open';
  _.accordion = (e) => {
    const openClass = accordionOpenClass;
    const animateSpeed = 300;
    const toggleBtnClass = (e) => {
      const _ = e.target;
      _.classList.toggle(openClass);
    }
    const slideOpenAnimate = (e) => {
      e.style.height = 'auto';
      const e_height = e.offsetHeight;
      e.animate([
        { height: 0 },
        { height: e_height + 'px' },
      ], animateSpeed)
    }
    const slideCloseAnimate = (e) => {
      const e_height = e.offsetHeight;
      e.animate([
        { height: e_height + 'px' },
        { height: 0 },
      ], animateSpeed)
      e.style.height = 0;
    }
    const toggleContAnimation = (e) => {
      const _ = e.target.nextElementSibling;
      _.classList.toggle(openClass);
      if (_.classList.contains(openClass)) {
        slideOpenAnimate(_);
      } else {
        slideCloseAnimate(_)
      }
    }
    const toggleAccordion = (e) => {
      toggleBtnClass(e)
      toggleContAnimation(e)
    }
    e.addEventListener('click', (el) => toggleAccordion(el));
  }

  /*!--------------------------------------------------------------------------*
  *	スムーススクロール｜処理
  *--------------------------------------------------------------------------*/
  const headerElementClass = 'js--header-el';
  const headerElement = `.${headerElementClass}`;
  const headerHeight = document.querySelector(headerElement) != null ? document.querySelector(headerElement).offsetHeight : 0;
  _.scrollAnimate = {
    anchor: function (e) {
      const scrollAnimation = (e) => {
        const _ = e.target;
        e.preventDefault();

        const _href = _.getAttribute('href');
        const targetElement = _href === "" || _href === "#" ? '' : document.getElementById(_href.replace('#', ''));
        const targetPos = targetElement === '' ? 0 : window.pageYOffset + targetElement.getBoundingClientRect().top - headerHeight;
        window.scroll({
          top: targetPos,
          behavior: 'smooth'
        });
      }
      e.addEventListener('click', (el) => scrollAnimation(el));
    },
    urlHash: function (hash) {
      setTimeout(function () {
        window.scrollTo({ top: 0 }, 0);
      })
      setTimeout(function () {
        const targetElement = document.getElementById(hash.replace('#', ''));
        const targetPosition = window.pageYOffset + targetElement.getBoundingClientRect().top - headerHeight;
        window.scroll({
          top: targetPosition,
          behavior: 'smooth'
        });
      }, 200);
    }
  }

  /*!--------------------------------------------------------------------------*
  *	ショートコード表示｜処理
  *--------------------------------------------------------------------------*/
  _.shortcodeTrigger = () => {
    const executeShortcodeTriggerElement = document.getElementById(`js--execute-shortcode`);
    const url = new URL(window.location.href);
    executeShortcodeTirggerElement.addEventListener('click', function () {
      const executeShortcodeInputElement = document.getElementById(`mv`);
      const shortcode_access_url = `${url.origin}/wp-content/themes/twentytwentyone/functions/settings/_menu_shortcodePage_fetchAPI.php?get_shortcode_html="${encodeURIComponent(executeShortcodeInputElement.value)}"`;
      const fetchData = async (url) => {
        const response = await fetch(url, { method: 'get' });
        if (response.ok) {
          const json = await response.json();
          return json;
        } else {
          throw new Error('エラーが発生しました');
        }
      };

      fetchData(shortcode_access_url)
        .then((data) => {
          const html = data['html'];

          const executeShortcodeTargetElement = document.getElementById('js--execute-shortcode-target');
          executeShortcodeTargetElement.innerHTML = html;
        })
        .catch((err) => {
          console.log(err);
        });
    });
  }
})();


window.addEventListener('DOMContentLoaded', () => {
  /*!--------------------------------------------------------------------------*
  *	スムーススクロール｜発火
  *--------------------------------------------------------------------------*/
  // const locationHash = location.hash;
  // if (locationHash) FUNC.scrollAnimate.urlHash(locationHash);
  // const anchorElements = document.querySelectorAll('a[href^="#"]');
  // anchorElements.forEach((e) => FUNC.scrollAnimate.anchor(e));

  /*!--------------------------------------------------------------------------*
  *	タブ切り替え｜発火
  *--------------------------------------------------------------------------*/
  const tabBtnClass = 'js--tab-btn';
  const tabTargetElement = document.querySelectorAll(`.${tabBtnClass}`);
  tabTargetElement.forEach((e) => FUNC.tab(e));

  /*!--------------------------------------------------------------------------*
  *	アコーディオン｜発火
  *--------------------------------------------------------------------------*/
  const accordionBtnClass = 'js--accordion-btn';
  const accordionTargetElement = document.querySelectorAll(`.${accordionBtnClass}`);
  accordionTargetElement.forEach((e) => FUNC.accordion(e));

  /*!--------------------------------------------------------------------------*
  *	ショートコード表示｜発火
  *--------------------------------------------------------------------------*/
  FUNC.shortcodeTrigger();
});

