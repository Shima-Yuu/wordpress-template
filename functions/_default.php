<?php

/***************************************************************************
 **
 ** デフォルトの設定
 **
 ****************************************************************************/

/**
 * 自動で読み込まれる不要なCSS、JSを削除
 */
function disable_css_js()
{
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'disable_css_js');


/**
 * 似たようなページに勝手にリダイレクトしない
 */
function disable_redirect_canonical($redirect_url)
{
  if (is_404()) {
    return false;
  }
  return $redirect_url;
}
add_filter('redirect_canonical', 'disable_redirect_canonical');


/**
 * フロントの body に class を追加
 */
add_filter('admin_body_class', 'add_admin_body_class');
function add_admin_body_class($classes)
{
  global $host_product, $host_development;

  if (strpos($_SERVER['HTTP_HOST'], $host_product) === 0) {
    $body_class = ' --host_product';
  } else {
    $body_class = ' --host_development';
  }
  $classes .= $body_class;
  return $classes;
}


/**
 * All In One SEO Schema無効
 */
if (has_filter('aioseo_schema_disable', '__return_true')) {
  add_filter('aioseo_schema_disable', '__return_true');
}
