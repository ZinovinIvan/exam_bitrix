<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if (!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

global $USER;
$currentUser = $USER->GetID();
if (!$currentUser)
{
	return false;
}
if ($this->StartResultCache(false, array($currentUser)))
{
	if (!$iblockNews = (int)$arParams['PRODUCTS_IBLOCK_ID'])
	{
		return false;
	}
	if (!$iblockProperty = trim($arParams['IBLOCK_PROPERTY']))
	{
		return false;
	}
	if (!$userProperty = trim($arParams['USER_PROPERTY']))
	{
		return false;
	}
	$iblockProperty = 'PROPERTY_' . $iblockProperty;
	$rsUser = CUser::GetByID($currentUser);
	$arUser = $rsUser->Fetch();
	$currentType = $arUser[$userProperty];
	$arItems = [];
	$rsUsers = CUser::GetList(
		($by = 'personal_country'),
		($order = 'desc'),
		[
			$userProperty => $currentType,
			'ACTIVE' => 'Y'
		],
		[
			'FIELDS' => [
				'ID', 'LOGIN'
			]
		]
	);
	while ($arUser = $rsUsers->Fetch())
	{
		$arItems[$arUser['ID']] = [
			'LOGIN' => $arUser['LOGIN'],
			'NEWS' => []
		];
	}
	$arAllNews = [];
	$resNews = CIBlockElement::GetList(
		false,
		[
			'IBLOCK_ID' => $iblockNews,
			'ACTIVE' => 'Y',
			$iblockProperty => array_keys($arItems)
		],
		false,
		false,
		[
			'ID', 'NAME', 'ACTIVE_FROM', $iblockProperty
		]
	);
	while ($arNew = $resNews->Fetch())
	{
		$newId = $arNew['ID'];
		if (!isset($arAllNews[$newId]))
		{
			$arAllNews[$newId] = [
				'NAME' => $arNew['NAME'],
				'ACTIVE_FROM' => $arNew['ACTIVE_FROM'],
				'AUTHORS' => [
					$arNew[$iblockProperty . '_VALUE']
				]
			];
		} else
		{
			$arAllNews[$newId]['AUTHORS'][] = $arNew[$iblockProperty . '_VALUE'];
		}
	}
	foreach ($arAllNews as $key => $new)
	{
		if (in_array($currentUser, $new['AUTHORS']))
		{
			unset($arAllNews[$key]);
		} else
		{
			foreach ($new['AUTHORS'] as $author)
			{
				$arItems[$author]['NEWS'][] = $key;
			}
		}
	}
	unset($arItems[$currentUser]);
	foreach ($arItems as $key => $item)
	{
		if (!count($item['NEWS']))
		{
			unset($arItems[$key]);
		}
	}
	$arResult['ITEMS'] = $arItems;
	$arResult['ALL_NEWS'] = $arAllNews;
	$arResult['COUNT_NEWS'] = count($arAllNews);

	$this->setResultCacheKeys(['COUNT_NEWS']);
	$this->includeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage('SET_TITLE') . $arResult['COUNT_NEWS']);
?>