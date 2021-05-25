<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
echo '<b>' . GetMessage('AUTHOR_I_NEWS') . '</b><br/>';
echo '<ul>';
foreach ($arResult['ITEMS'] as $userId => $item)
{
	echo '<li>[' . $userId . '] - ' . $item['LOGIN'];
	echo '<ul>';
	foreach ($item['NEWS'] as $newId)
	{
		$arNews = $arResult['ALL_NEWS'][$newId];
		echo '<li>'.$arNews['NAME'].'</li>';
	}
	echo '</ul>';
	echo '</li>';
}
echo '</ul>';