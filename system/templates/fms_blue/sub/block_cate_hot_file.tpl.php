<?php 
// This is PHPDISK auto-generated file. Do NOT modify me.

// Cache Time:2015-10-27 17:28:22

!defined('IN_PHPDISK') && exit('[PHPDisk] Access Denied');

?>
<?php 
##
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.google.com
#
#	$Id: block_cate_hot_file.tpl.php 121 2014-03-04 12:38:05Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
##
 ?>
<?php !defined('IN_PHPDISK') && exit('[PHPDisk] Access Denied!'); ?>
<div class="panel panel-default">
	<div class="panel-heading"><?=__('cate_hot_file')?></div>
	<ul class="list-group">
	<?php 
	if(count($C[cate_hot_file])){
		foreach($C[cate_hot_file] as $v){
	 ?>
		<li class="list-group-item"><?=$v['file_size']?><a href="<?=$v['a_viewfile']?>" target="_blank"><?=$v['file_name']?></a></li>
	<?php 
		}
	}
	 ?>
	</ul>
</div>
