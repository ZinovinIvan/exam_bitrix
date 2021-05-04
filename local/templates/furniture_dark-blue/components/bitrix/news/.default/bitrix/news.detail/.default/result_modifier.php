<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (isset($arParams['IBLOCK_CANNONICAL']) && (int)$arParams['IBLOCK_CANNONICAL'] > 0)
{
	$bid = (int)$arParams['IBLOCK_CANNONICAL'];
	$res = CIBlockElement::GetList(
		false,
		[
			'IBLOCK_ID' => $bid,
			'ACTIVE' => 'Y',
			'PROPERTY_NEW' => $arResult['ID']
		],
		false,
		['nTopCount' => 1],
		['ID', 'NAME']
	);
	if ($fields = $res->Fetch())
	{
		$this->getComponent()->setResultCacheKeys(['CANNONICAL_LINK']);
		$arResult['CANNONICAL_LINK'] = $fields['NAME'];
	}
}