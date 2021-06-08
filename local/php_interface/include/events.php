<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
const ID_IBLOCK_PRODUCT = 2;
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "OnBeforeIBlockElementUpdateHandler");
AddEventHandler("main", "OnEpilog", "OnEpilog");
function OnBeforeIBlockElementUpdateHandler(&$arFields)
{
	if ((int)$arFields['IBLOCK_ID'] === ID_IBLOCK_PRODUCT && $arFields['ACTIVE'] === 'N')
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
			if (!empty($ob))
			{
				global $APPLICATION;
				$APPLICATION->ThrowException(GetMessage('ERROR_DEACTIVE_PRODUCT', [
					'#COUNT#' => $ob['SHOW_COUNTER']
				]));
				return false;
			}
		}
	}
	return true;
}

function OnEpilog()
{
	if (defined('ERROR_404') && ERROR_404 === 'Y')
	{
		global $APPLICATION;
		$curUri = $APPLICATION->GetCurUri();
		CEventLog::Add(array(
			"SEVERITY" => "INFO",
			"AUDIT_TYPE_ID" => "ERROR_404",
			"MODULE_ID" => "main",
			"DESCRIPTION" => $curUri
		));
		$APPLICATION->RestartBuffer();
		include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH.'/header.php';
		include $_SERVER['DOCUMENT_ROOT'] . '/404.php';
		include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH.'/footer.php';

	}
}