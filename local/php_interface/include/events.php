<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
const ID_IBLOCK_PRODUCT = 2;
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "OnBeforeIBlockElementUpdateHandler");

function OnBeforeIBlockElementUpdateHandler(&$arFields)
{
	if ((int)$arFields['IBLOCK_ID'] === ID_IBLOCK_PRODUCT && $arFields['ACTIVE']==='N')
	{
		$idElement = $arFields['ID'];
		$res = CIBlockElement::GetList(
			[],
			[
				'IBLOCK_ID' => $arFields['IBLOCK_ID'],
				'ID' => $idElement,
				'ACTIVE' => 'Y',
				'>SHOW_COUNTER' => 2
			],
			false,
			[],
			[
				'ID',
				'SHOW_COUNTER'
			]
		);
		while ($ob = $res->Fetch())
		{
			if (!empty($ob)){
				global $APPLICATION;
				$APPLICATION->ThrowException(GetMessage('ERROR_DEACTIVE_PRODUCT',[
					'#COUNT#'=>$ob['SHOW_COUNTER']
				]));
				return false;
			}
		}
	}
	return true;
}