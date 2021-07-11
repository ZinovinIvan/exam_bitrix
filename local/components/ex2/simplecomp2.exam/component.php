<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


use Bitrix\Main\Loader;


if (!isset($arParams["CACHE_TIME"]))
{
	$arParams["CACHE_TIME"] = 36000000;
}


global $USER;
if ($this->startResultCache(false, [$USER->GetGroups()]))
{
	if (!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		return;
	}

	$arSelectСlassifier = [
		"ID",
		"IBLOCK_ID",
		"NAME",
	];

	$arFilterСlassifier = [
		"IBLOCK_ID" => $arParams["FIRMS_IBLOCK_ID"],
		"CHECK_PERMISSIONS" => "Y",
		"ACTIVE" => "Y",
	];

	$arResult["CLASSIFIER"] = [];
	$rsElement_link = CIBlockElement::GetList(
		false,
		$arFilterСlassifier,
		false,
		false,
		$arSelectСlassifier
	);
	while ($arElement = $rsElement_link->GetNext())
	{
		$arResult["CLASSIFIER_ID"][] = $arElement["ID"];
		$arResult["CLASSIFIER"][$arElement["ID"]] = $arElement;
	}
	$arResult["COUNT_CLASS"] = count($arResult["CLASSIFIER"]);

	$arSelectElems = [
		"ID",
		"IBLOCK_ID",
		"IBLOCK_SECTION_ID",
		"NAME",
		"PREVIEW_TEXT",
		"CODE",
	];

	$arFilterElems = [
		"IBLOCK_ID" => $arParams["PRODUCT_IBLOCK_ID"],
		"CHECK_PERMISSIONS" => "Y",
		"PROPERTY_" . $arParams["PROPERTY_FIRMS_CODE"] => $arResult["CLASSIFIER_ID"],
		"ACTIVE" => "Y",
	];

	$arSortElems = [
		'NAME' => 'ASC', 'SORT' => 'ASC',
	];


	$arResult["ELEMENTS"] = [];

	$rsElement = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);

	while ($rsElem = $rsElement->GetNextElement())
	{
		$arEl = $rsElem->GetFields();
		$arEl["PROP"] = $rsElem->GetProperties();
		foreach ($arEl["PROP"][$arParams["PROPERTY_FIRMS_CODE"]]["VALUE"] as $val)
		{
			$arResult["CLASSIFIER"][$val]["ELEMENTS_ID"][] = $arEl["ID"];
		}

		$arResult["ELEMENTS"][$arEl["ID"]] = $arEl;
		if ($arParams["LINK_TEMPLATE"])
		{
			$arResult["ELEMENTS"][$arEl["ID"]]["LINK_TEMPLATE"] = str_replace(
				[
					"#SECTION_ID#",
					"#ELEMENT_CODE#",
					"#ELEMENT_ID#",
				],
				[
					$arEl["IBLOCK_SECTION_ID"],
					$arEl["CODE"],
					$arEl["ID"],
				],
				$arParams["LINK_TEMPLATE"]
			);
		}
	}
	$this->SetResultCacheKeys(["COUNT_CLASS"]);
	$this->includeComponentTemplate();
} else
{
	$this->abortResultCache();
}
$APPLICATION->SetTitle(GetMessage("COUNT_FIRMS_PRODUCT") . $arResult["COUNT_CLASS"]);