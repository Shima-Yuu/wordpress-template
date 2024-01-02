<?php

/***************************************************************************
 ** 
 ** Wordpressの管理画面内に影響する処理を定義
 ** 
 ****************************************************************************/
/**
 * WP内のHTMLタグ内のショートコード有効
 */
add_filter('wp_kses_allowed_html', function ($allowedposttags, $context) {
  if ($context == 'post') {
    $allowedposttags['input']['value']  = 1;
    $allowedposttags['link']['href']  = 1;
    $allowedposttags['script']['src']  = 1;
    $allowedposttags['source']['srcset']  = 1;
  }
  return $allowedposttags;
}, 10, 2);

/**
 * 固定ページ一覧にスラッグ表示
 */
function add_columns_slug($columns)
{
  $columns['slug'] = "スラッグ";
  echo '<style>.fixed .column-slug {width: 10%;}</style>';
  return $columns;
}
function add_column_row_slug($column_name, $post_id)
{
  if ($column_name == 'slug') {
    $post = get_post($post_id);
    $slug = $post->post_name;
    echo esc_attr($slug);
  }
}
add_filter('manage_pages_columns', 'add_columns_slug');
add_action('manage_pages_custom_column', 'add_column_row_slug', 10, 2);

/**
 * 管理画面にタグを表示
 * @author shimizu
 * @see https://junpei-sugiyama.com/wp-term-display/
 */

// 共通処理
function add_custom_column_id($column_name, $id)
{
  $terms = get_the_terms($id, $column_name);
  if ($terms && !is_wp_error($terms)) {
    $news_links = [];
    foreach ($terms as $term) {
      $news_links[] = $term->name;
    }
    echo join(", ", $news_links);
  }

  if ($column_name === 'slug') {
    $post = get_post($id);
    if ($post && !is_wp_error($post)) {
      echo $post->post_name;
    }
  }
}

// テンプレ
// add_filter('manage_{カスタム投稿名}_posts_columns', function ($defaults) {
//   $defaults['タクソノミー名'] = '見出しのタイトル';
//   return $defaults;
// });
// add_action('manage_{カスタム投稿名}_posts_custom_column', 'add_custom_column_id', 10, 2);


/**
 * 管理画面1ページあたりの記事表示件数
 */
function my_edit_posts_per_page($posts_per_page)
{
  return 50;
}
add_filter('edit_posts_per_page', 'my_edit_posts_per_page');

/**
 * 投稿編集画面で画像（メディア）を追加する際に挿入されるURLを「[homeurl]+相対URL」にする
 */
function convert_url_to_relative($html, $id, $attachment)
{
  $html_new = preg_replace_callback(
    '@(href|src)="(https?://[^\s"]+)"@i',
    function ($matches) {
      $relativeurl = wp_make_link_relative($matches[2]);
      return $matches[1] . '="[homeurl]' . $relativeurl . '"';
    },
    $html
  );
  return $html_new;
}
add_filter('media_send_to_editor', 'convert_url_to_relative', 10, 3);

/**
 * the_content() の自動整形を無効化
 */
remove_filter('the_content', 'wpautop');


/**
 * 画像のlazy属性の自動付与を解除
 */
add_filter('wp_lazy_loading_enabled', '__return_false');

/**
 * 管理画面にcss/jsを追加
 */
function mytheme_admin_enqueue()
{
  wp_enqueue_style('admin_style', TEMP . '/css/wp-admin/admin.css');

  wp_enqueue_script('admin_script_post_inputcheck', TEMP . '/js/post_inputcheck.js');
  wp_enqueue_script('admin_script', TEMP . '/js/wp-admin/admin.js');
}
add_action('admin_enqueue_scripts', 'mytheme_admin_enqueue');

/**
 * 管理画面 メディアのアップロードディレクトリをカスタム投稿タイプごとに指定
 */
add_filter('upload_dir', 'change_upload_dir');
function change_upload_dir($upload)
{
  if (!empty($_REQUEST['post_id'])) {
    $id = intval($_REQUEST['post_id']);
    $post_type = !empty($id) ? get_post_type($id) : "";
    if (!empty($post_type)) {
      $upload['path'] = $upload['basedir'] . "/" . $post_type;
      $upload['url'] = $upload['baseurl'] . "/" . $post_type;
    }
  }
  return $upload;
}


/**
 * 管理画面 不要なメニューを非表示
 */
function wpqw_hide_admin_submenus()
{
  remove_menu_page('edit.php'); // 投稿
  remove_menu_page('edit-comments.php'); // コメント

  /* サブメニュー -------------------- */
  // ダッシュボード
  // remove_submenu_page( 'index.php', 'index.php' ); // ホーム
  remove_submenu_page('index.php', 'update-core.php'); // 更新

  // 外観
  // remove_submenu_page('themes.php', 'themes.php'); // テーマ
  // remove_submenu_page( 'themes.php', 'widgets.php' ); // ウィジェット
  remove_submenu_page('themes.php', 'nav-menus.php'); // メニュー
  // remove_submenu_page( 'themes.php', 'theme-editor.php' ); // テーマエディター	

  // プラグイン
  /*
	remove_submenu_page( 'plugins.php', 'plugins.php' ); // インストール済みプラグイン
	remove_submenu_page( 'plugins.php', 'plugin-install.php' ); // 新規追加
	remove_submenu_page( 'plugins.php', 'plugin-editor.php' ); // プラグイン編集
	*/

  // 投稿
  remove_submenu_page('edit.php', 'edit.php'); // 投稿一覧
  remove_submenu_page('edit.php', 'post-new.php'); // 新規追加
  remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category'); // カテゴリー
  remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag'); // タグ

  //コラム投稿
  remove_submenu_page('edit.php?post_type=column', 'edit-tags.php?taxonomy=column_category&amp;post_type=column'); //コラムカテゴリーを削除

  // メディア
  remove_submenu_page('upload.php', 'media-new.php'); // 新規追加

}
add_action('admin_menu', 'wpqw_hide_admin_submenus');

/**
 * スラッグの日本語禁止
 * 投稿タイプのスラッグ-記事ID
 */
function auto_post_slug($slug, $post_ID, $post_status, $post_type)
{
  if (preg_match('/(%[0-9a-f]{2})+/', $slug)) {
    $slug = utf8_uri_encode($post_type) . '-' . $post_ID;
  }
  return $slug;
}
add_filter('wp_unique_post_slug', 'auto_post_slug', 10, 4);

/**
 * WP内にオリジナルのメニューを作成
 * @author shimazaki
 */
// add_action('admin_menu', 'common_item_in_site');
function common_item_in_site()
{
  add_menu_page('サイト内共通項目', 'サイト内共通項目', 'manage_options', 'common_item_in_site', 'add_common_item_in_site', 'dashicons-admin-post', 2);
  add_action('admin_init', 'register_custom_setting');
}
function add_common_item_in_site()
{
?>
  <div class="wrap">
    <h2>サイト内共通項目</h2>
    <form method="post" action="options.php" enctype="multipart/form-data" encoding="multipart/form-data">
      <?php
      settings_fields('custom-menu-group');
      do_settings_sections('custom-menu-group'); ?>
      <div class="metabox-holder">
        <div class="postbox ">
          <h3 class='hndle'><span>xxxx</span></h3>
          <div class="inside">
            <div class="main">
              <p><input type="text" id="xxxx" name="xxxx" value="<?php echo get_option('xxxx'); ?>"></p>
              <p>ショートコードで記述する場合：<code>[xxxx]</code></p>
              <p>PHP内にて記述する場合：<code>&lt;?= xxxx ?&gt;</code></p>
            </div>
          </div>
        </div>
      </div>
      <?php submit_button(); ?>
    </form>
  </div>
<?php
}
function register_custom_setting()
{
  // register_setting('custom-menu-group', 'xxxx');
}
