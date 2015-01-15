<?php

class hkindeditor {

    public function __construct() {
        
    }

    //编辑器
    function edit($content = '', $textareaid = 'content', $textareaname = 'content', $textwidth = '200', $textheight = '300', $showtextarea = 'mini') {
        $str = '<textarea name="' . $textareaname . '" style="width:' . $textwidth . 'px;height:' . $textheight . 'px;visibility:hidden;">' . $content . '</textarea><link rel="stylesheet" href="views/kindeditor/themes/default/default.css" />
	<link rel="stylesheet" href="views/kindeditor/plugins/code/prettify.css" />
	<script charset="utf-8" src="views/kindeditor/kindeditor.js"></script>
	<script charset="utf-8" src="views/kindeditor/lang/zh_CN.js"></script>
	<script charset="utf-8" src="views/kindeditor/plugins/code/prettify.js"></script>
	<script>
		KindEditor.ready(function(K) {
			var editor1 = K.create(\'textarea[name="' . $textareaname . '"]\', {
				cssPath : \'views/kindeditor/plugins/code/prettify.css\',
				uploadJson : \'views/kindeditor/php/upload_json.php\',
				fileManagerJson : \'views/kindeditor/php/file_manager_json.php\',
				allowFileManager : true,
				resizeType : 1,
				items : [
						\'fontname\', \'fontsize\', \'|\', \'forecolor\', \'hilitecolor\', \'bold\', \'italic\', \'underline\',
						\'removeformat\', \'|\', \'justifyleft\', \'justifycenter\', \'justifyright\', \'insertorderedlist\',
						\'insertunorderedlist\', \'|\', \'emoticons\', \'image\', \'link\']
			});
			prettyPrint();
		});
	</script>';
        return $str;
    }

}

?>
