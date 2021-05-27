<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
echo '<select onchange="location.href=this.value">';
foreach ($arResult['SITES'] as $site){
	echo '<option value="'.$site['DIR'].'" '.($site['CURRENT']=='Y'?' selected ':'').'>'.$site['LANG'].'</option>';
}

echo '</select>';