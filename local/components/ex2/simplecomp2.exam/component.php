<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


use Bitrix\Main\Loader;


if (!isset($arParams["CACHE_TIME"]))
{
	$arParams["CACHE_TIME"] = 36000000;
}

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$bFilter = false;
if ($request->get('F'))
{
	$bFilter = true;
}

global $USER;
if ($this->startResultCache(false, [$USER->GetGroups()], $bFilter))
{
	if (!Loader::includeModule("iblock"))
	{
		$this->abortResultCache();
		return;
	}

	if ($USER->IsAuthorized() && CModule::includeModule("iblock"))
	{
		$arButtons = CIBlock::GetPanelButtons($arParams["PRODUCT_IBLOCK_ID"]);
		$this->AddIncludeAreaIcons(
			[
				[
					"ID" => "linkIb",
					"TITLE" => "ИБ в админке",
					"URL" => $arButtons['submenu']['element_list']['ACTION_URL'],
					"IN_PARAMS_MENU" => true,
				],
			]
		);
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

	if ($bFilter)
	{
		$arFilterElems[] = [
			"LOGIC" => "OR",
			[
				"<=PROPERTY_PRICE" => "1700",
				"PROPERTY_MATERIAL" => "Дерево, ткань",
			],
			[
				"<PROPERTY_PRICE" => "1500",
				"PROPERTY_MATERIAL" => "Металл, пластик",
			],
		];
	}
	$arSortElems = [
		'NAME' => 'ASC', 'SORT' => 'ASC',
	];


	$arResult["ELEMENTS"] = [];

	$rsElement = CIBlockElement::GetList(
		$arSortElems,
		$arFilterElems,
		false,
		false,
		$arSelectElems);

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