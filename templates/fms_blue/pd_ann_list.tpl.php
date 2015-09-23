<!--#
##
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.google.com
#
#	$Id: pd_ann_list.tpl.php 121 2014-03-04 12:38:05Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
##
#-->
<?php !defined('IN_PHPDISK') && exit('[PHPDisk] Access Denied!'); ?>
<div class="layout_box">
<!--#include sub/block_adv_middle#-->
<div class="l">
<!--#include sub/block_ann_list#-->
</div>
<div class="r">
<div class="file_box">
	<h2 class="file_tit">{$nav_title}</h2>
<div class="ann_box">
<!--#
if(count($ann_list)){
	foreach($ann_list as $k => $v){
		$color = ($k%2 ==0) ? 'color1' :'color4';
#-->
<div class="tit2"><img src="images/icon_nav.gif" align="absmiddle" border="0" />{$v[subject]}</div>
<div class="inbox {$color}">
{$v[content]}
<br />
<br />
<div align="right">{$v['in_time']}</div>
</div>
<!--#		
	}
	unset($ann_list);
}else{	
#-->
<div align="center"><?=__('announce_not_found')?></div>
<!--#
}
#-->
<!--#if($page_nav){#-->
<br />
<div align="right" class="clear">{$page_nav}</div>
<br />
<!--#}#-->
</div>

</div>
</div>
<div class="clear"></div>
</div>

