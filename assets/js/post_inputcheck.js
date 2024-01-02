jQuery(function () {
  var url = location.search;
  var newPostURL = location.pathname;

  // ========================================================================
  // 投稿の編集画面だったら実行
  // ========================================================================
  if (url.indexOf("post") != -1 && url.indexOf("action=edit") != -1
    || newPostURL.indexOf("post-new.php") != -1) {
    jQuery("#post").on("submit", function (event) {
      var errFlg = false;
      var h1 = jQuery("h1").html();

      // 記事タイトルの入力チェック
      var jpTitle = jQuery('#title').val().trim();
      if (jpTitle == "") {
        alert("タイトルは必須項目です\n");
        errFlg = true;
      }

      // 施術カテゴリーがあったら
      var opinfo_categories = jQuery('#opinfo_category-all').val();

      if (undefined !== opinfo_categories) {
        // 美容外科Drのお悩み相談以外だったらチェック有効
        var tag_type = document.getElementById('taxonomy-tag_type');

        if (undefined === tag_type || null === tag_type) {

          if (!$('#opinfo_category-all input[type=checkbox]').is(':checked')) {
            alert("診療カテゴリーがチェックされていません\n");
            errFlg = true;
          }
        }

      }


      // 入力チェックエラーな場合は投稿させない
      if (errFlg) {
        event.preventDefault();
        event.stopPropagation();
      }
    });
  }
});
