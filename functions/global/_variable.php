<?php

/***************************************************************************
 ** 
 ** 定数・グローバル変数
 ** 
 ****************************************************************************/

/**
 * 定数
 */
define('HOME', home_url());
define('TEMP', get_stylesheet_directory_uri() . '/assets');

define('URL', isset($_SERVER['HTTPS']) ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
define('URL_PATH', $_SERVER['REQUEST_URI']);
define('DOMAIN', $_SERVER['HTTP_HOST']);

define("TEST_FLAG", "/something/"); // テスト環境と本番環境を判別するためのフラグ

// サイト内共通項目のショートコードと定数を定義
function registerCustomMenuGroup($arrays)
{
  foreach ($arrays as $constantName) {
    $option = strtoupper($constantName);
    $constantValue = get_option($option);
    define($option, $constantValue);

    //クロージャ内で外部変数や定数を参照できるようにするためuse使用
    add_shortcode($constantName, function ($atts = null) use ($constantValue) {
      return $constantValue;
    });
  }
}
$register_custom_arrays = ['', '',];
// registerCustomMenuGroup($register_custom_arrays);

/**
 * グルーバル変数
 */
global $head_GTM_01, $head_GTM_02;
