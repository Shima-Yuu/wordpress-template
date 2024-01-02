<?php

/***************************************************************************
 ** 
 ** 関数
 ** 
 ****************************************************************************/

/**
 * 対象文字列からbrタグを削除する
 * @author shimazaki
 * @param string $str 対象の文字列
 * @return string brを削除した文字列を返還
 */
function removeBrTag($str)
{
  return preg_replace('/<br\s*.*>/i', '', $str);
}

/**
 * 指定した固定ページのページ情報及び子階層のページ情報を取得する
 * @author shimazaki
 * @see https://onl.tw/12Km3tz 返還されるデータの例
 * @param string $parent_page_slug 親階層のページスラッグ
 * @return object 固定ページのページ情報及び子階層のページ情報をオブジェクトにて返却
 */
function getPageData($parent_page_slug)
{
  $parent_page = get_page_by_path($parent_page_slug);
  if (empty($parent_page)) return '指定したスラッグのページが存在しないようです。 ' . $parent_page_slug . 'が正しいかご確認ください。';
  $parent_id   = $parent_page->ID;
  $child_pages = get_pages('child_of=' . $parent_id . '');
  $pages_obj   = [];
  $pages_obj['archive'] = [ // 子階層のデータが存在する場合は、親階層のページを先頭に追加
    'id' => $parent_page->ID,
    'ttl' => removeBrTag($parent_page->post_title),
    'link' => get_permalink($parent_id),
  ];
  if ($child_pages) {
    foreach ($child_pages as $child_page) {
      $child_page_id   = $child_page->ID;
      $child_page_ttl  = $child_page->post_title;
      $child_page_slug = $child_page->post_name;
      $child_page_link = get_permalink($child_page_id);

      $pages_obj[$child_page_slug]  = [
        'id'   => $child_page_id,
        'ttl'  => removeBrTag($child_page_ttl),
        'link' => $child_page_link,
      ];
    }
  }
  return $pages_obj;
}

/**
 * 指定したカスタム投稿のページ情報を取得する
 * @author shimazaki
 * @see https://onl.tw/dxJ2AAg 返還されるデータの例
 * @param string $post_type_slug カスタム投稿のスラッグ
 * @return object getPageDataと同じフォーマットでデータを返却
 */
function getPostData($post_type_slug)
{
  $post_archive_obj = get_post_type_object($post_type_slug);
  if (empty($post_archive_obj)) return '指定したカスタム投稿タイプが存在しないようです。 ' . $post_type_slug . 'が正しいかご確認ください。';
  $posts = get_posts([
    'post_status'    => 'publish',
    'post_type'      => $post_type_slug,
    'posts_per_page' => -1,
  ]);
  $post_obj = [];
  $post_obj['archive'] = [
    'ttl' => $post_archive_obj->label,
    'link' => HOME . '/' . $post_archive_obj->name,
  ];
  if ($posts) {
    foreach ($posts as $post) {
      $post_id   = $post->ID;
      $post_ttl  = $post->post_title;
      $post_slug = $post->post_name;
      $post_link = get_permalink($post_id);

      $post_obj[$post_slug]  = [
        'ttl' => removeBrTag($post_ttl),
        'link' => $post_link,
      ];
    }
  }
  return $post_obj;
}

/**
 * 現在の投稿情報を取得
 * @author shimizu
 * @return object
 */
function get_post_type_register()
{
  global $opinfo_post_type;

  $opinfo_post_type = get_query_var('post_type');
  $post_type_object = get_post_type_object($opinfo_post_type);

  return $post_type_object;
}

/**
 * デバッグ用（WPログイン中のみconsole.logとして出力）
 * @author onda
 * @param int user_id 説明
 * @return string scriptタグを生成し、オブジェクトの中身をconsole.logで出力
 */
function console_log($data)
{
  if (is_user_logged_in()) {
    echo '<script class="debug">';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
  }
}

/**
 * ページネーション
 * @author shimazaki
 * @param  object $wp_query 対象になるqueryを引数として渡す
 * @return string ページネーションのHTML
 */
function pagination($wp_query)
{
  if (isset($wp_query)) {
    $total = $wp_query->max_num_pages;
    $prev = get_previous_posts_link('', $total);
    $next = get_next_posts_link('', $total);
    if ($prev || $next) {
      echo '<div class="">';
      $page_links = paginate_links([
        'format' => '?paged=%#%',
        'total' => $total,
        'prev_text' => __('前へ'),
        'next_text' => __('次へ'),
        'type' => 'array',
        'mid_size' => 1,
      ]);
      function addClass($pagination_el)
      {
        return str_replace('page-numbers', 'ユニークなクラス名', $pagination_el);
      }
      $page_links = array_map('addClass', $page_links);

      echo '
        <ul class="">
          <li class="">' . join('</li><li class="">', $page_links) . '
          </li>
        </ul>
      </div>';
    }
  }
}
