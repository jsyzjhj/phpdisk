<!--#
##
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.google.com
#
#	$Id: block_yesterday_down_file.tpl.php 121 2014-03-04 12:38:05Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
##
#-->
<?php !defined('IN_PHPDISK') && exit('[PHPDisk] Access Denied!'); ?>
<div class="fl_box">
<div class="tit2"><?=__('yesterday_down_file')?></div>
<ul>
<!--#
if(count($C[yesterday_down_file])){
	foreach($C[yesterday_down_file] as $v){
#-->
	<li>{$v['file_size']}<a href="{$v['a_viewfile']}" target="_blank">{$v[file_icon]}{$v['file_name']}</a></li>
<!--#
	}
}
#-->
</ul>
</div>
