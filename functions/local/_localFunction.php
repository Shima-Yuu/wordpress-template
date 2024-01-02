<?php

/***************************************************************************
 **
 ** ローカル専用の関数を定義
 **
 ****************************************************************************/
$this_func_trigger   = true; // true：処理を発火
$staticHTML_dir_path =  get_template_directory() . '/staticHTML/';

if ($this_func_trigger) {
  if (!file_exists($staticHTML_dir_path)) return false; // リスクヘッジで処理抜けを用意

  add_filter('the_content', function ($content) {
    global $post, $staticHTML_dir_path;

    // page(post) or post_type & 前者なら複数形に
    $dirType = filterTheDirType($post->post_type, $staticHTML_dir_path);
    // もしpost_typeだった場合はどれか探す。page(post)なら無視
    $dirCustomType = filterIfCustomType($dirType, $post->post_type, $staticHTML_dir_path);
    // HTMLまでのpathを確定
    $path_for_the_htmls = $staticHTML_dir_path . $dirType .  $dirCustomType;
    // この投稿が親持ちなら親探す(無限に親子生成可能) 
    $path_for_the_htmls = findTheParents($post, $path_for_the_htmls);
    // 同名HTMLを発見
    $the_html = findTheHtmlFile($post->post_name, $path_for_the_htmls);

    // 存在を確定した場合は下記
    if ($the_html) {
      $content = loadHtmlContent($the_html, $path_for_the_htmls);
      updateTheContentByHtml($post, $the_html, $path_for_the_htmls);
    }

    return  $content;
  });
}


function filterTheDirType($the_post_type, $staticHTML_dir_path)
{

  $staticHTMLs_dir = array_filter(glob($staticHTML_dir_path . '*'), 'is_dir');

  // page || post
  $the_post_type_dir =
    in_array($the_post_type, ['page', 'post']) ? $the_post_type . 's' : "post_type";

  foreach ($staticHTMLs_dir as $the_directory) {
    $the_directory_name = basename($the_directory);
    // ディレクトリ名と現在のスラッグが一致するか確認
    if ($the_directory_name === $the_post_type_dir) {
      return $the_directory_name;
    }
  }
}

function filterIfCustomType($dirType, $the_post_type, $staticHTML_dir_path)
{
  $ignoreTypes = ['pages', 'posts'];

  if (!in_array($dirType, $ignoreTypes)) {
    $postTypePath = $staticHTML_dir_path . $dirType . "/";
    $staticHTMLs_dir = array_filter(glob($postTypePath . '*'), 'is_dir');
    foreach ($staticHTMLs_dir as $directory) {
      $directory_name = basename($directory);
      if ($directory_name === $the_post_type) {
        return '/' . $directory_name;
      }
    }
  }
}


function findTheParents($post, $the_path)
{

  $ancestors = get_post_ancestors($post);
  $delimiter = "/";
  $children = get_pages(array(
    'child_of' => $post->ID,
  ));
  $hasChildren = count($children);
  $the_path = $the_path . $delimiter;

  $ancestors = get_post_ancestors($post);
  $ancestors = array_reverse($ancestors);

  foreach ($ancestors as $ancestor) {
    $ancestor_post = get_post($ancestor);
    $ancestor_slug = $ancestor_post->post_name;
    $the_path .= $ancestor_slug . '/';
  }

  if ($hasChildren > 0) {
    $this_slug_path =  $post->post_name . "/";
    $the_path = $the_path . $this_slug_path;
  }

  return $the_path;
}


function findTheHtmlFile($current_slug, $the_html_path)
{
  $all_files_in_this_dir = is_dir($the_html_path) ? scandir($the_html_path) : null;
  $the_html = null;

  if ($all_files_in_this_dir) {
    foreach ($all_files_in_this_dir as $file) {
      if ($file === '.' || $file === '..') {
        continue;
      }
      if (pathinfo($file, PATHINFO_EXTENSION) === 'html') {
        $file_name_without_extension = pathinfo($file, PATHINFO_FILENAME);
        if ($current_slug === $file_name_without_extension) {
          $the_html = $file;
        }
      }
    }
  }

  return $the_html;
}


function loadHtmlContent($html_file, $the_path)
{
  $html_file_path = $the_path . "/" . $html_file;
  return do_shortcode(file_get_contents($html_file_path));
}

function updateTheContentByHtml($post, $html_file, $the_path)
{
  $html_file_path = $the_path . "/" . $html_file;

  if ($post) {
    $updated_post = array(
      'ID'           => $post->ID,
      'post_content' => mb_convert_encoding(file_get_contents($html_file_path), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'),
    );
  }

  wp_update_post($updated_post);
}
