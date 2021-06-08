<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (isset($arParams['ID_IBLOCK_CANNONICAL']) && (int)$arParams['ID_IBLOCK_CANNONICAL'] > 0)
{
	$bid = (int)$arParams['ID_IBLOCK_CANNONICAL'];
	$res = CIBlockElement::GetList(
		false,
		[
			'IBLOCK_ID' => $bid,
			'ACTIVE' => 'Y',
			'PROPERTY_NEW' => $arResult['ID']
		],
		false,
		[
			'nTopCount' => 1
		],
		[
			'ID', 'NAME'
		]
	);
	while ($item = $res->Fetch())
	{
		$this->getComponent()->SetResultCacheKeys([
			'CANONICAL'
		]);
		$arResult['CANONICAL'] = $item['NAME'];
	}
}