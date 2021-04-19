<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
echo '---<br/><br/>';
echo '<b>' . GetMessage('CATALOG') . '</b><br/>';
echo '<ul>';
foreach ($arResult['ITEMS'] as $item)
{
	$str = '';
	foreach ($item['SECTIONS'] as $sectionId)
	{
		$str .= ', ' . $arResult['ALL_SECTIONS'][$sectionId]['NAME'];
	}
	echo '<li><b>' . $item['NAME'] . '</b> - ' . $item['ACTIVE_FROM'] . ' (' . substr($str, 2) . ')';
	echo '<ul>';
	foreach ($item['PRODUCTS'] as $productId)
	{
		$arProduct = $arResult['ALL_PRODUCTS'][$productId];
		echo '<li>'.$arProduct['NAME'].' - '.$arProduct['PRICE'].' - '.$arProduct['ARTNUMBER'].' - '.$arProduct['MATERIAL'].'</li>';
	}
	echo '</ul>';
	echo '</li>';
}
echo '</ul>';