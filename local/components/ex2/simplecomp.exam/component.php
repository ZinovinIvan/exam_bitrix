<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

global $USER;
global $APPLICATION;
if ($this->startResultCache(false, [$USER->GetGroups()]))
{
	if (!Loader::includeModule("iblock"))
	{
		ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
		return;
	}

	if (intval($arParams["PRODUCTS_IBLOCK_ID"]) > 0
		&& intval($arParams["NEWS_IBLOCK_ID"]) > 0
		&& !empty($arParams["CODE_PROPERTY_NEWS"]))
	{
		$news = [];
		$newsIds = [];
		$arSelectElems = [
			"ID",
			"ACTIVE_FROM",
			"NAME",
		];
		$arFilterElems = [
			"IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
			"ACTIVE" => "Y",
		];
		$arSortElems = [];

		$rsElements = CIBlockElement::GetList(
			$arSortElems,
			$arFilterElems,
			false,
			false,
			$arSelectElems
		);
		while ($arElement = $rsElements->Fetch())
		{
			$newsIds[] = $arElement['ID'];
			$news[$arElement['ID']] = $arElement;
		}


		$arSelectSect = [
			"ID",
			"IBLOCK_ID",
			"NAME",
			$arParams['CODE_PROPERTY_NEWS'],
		];
		$arFilterSect = [
			"IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
			"ACTIVE" => "Y",
			$arParams['CODE_PROPERTY_NEWS'] => $newsIds,
			'CNT_ACTIVE',
		];
		$arSortSect = [];
		$sectionIds = [];
		$sectionList = [];
		$rsSections = CIBlockSection::GetList(
			$arSortSect,
			$arFilterSect,
			true,
			$arSelectSect,
			false
		);
		while ($arSection = $rsSections->Fetch())
		{
			$sectionIds[] = $arSection['ID'];
			$sectionList[$arSection['ID']] = $arSection;
		}
		$arAllPrice = [];
		$products = \CIBlockElement::GetList(
			[],
			[
				'IBLOCK_ID' => $arParams['PRODUCT_IBLOCK_ID'],
				'ACTIVE' => 'Y',
				'SECTION_ID' => $sectionIds,
			],
			false,
			false,
			[
				'NAME',
				'IBLOCK_SECTION_ID',
				'ID',
				'IBLOCK_ID',
				'PROPERTY_ARTNUMBER',
				'PROPERTY_MATERIAL',
				'PROPERTY_PRICE',
			]
		);
		while ($product = $products->Fetch())
		{
			foreach ($sectionList[$product['IBLOCK_SECTION_ID']]['UF_NEWS_LINK'] as $newsId)
			{
				$news[$newsId]['PRODUCTS'][] = $product;
			}
			$arAllPrice[] = $product['PROPERTY_PRICE_VALUE'];
		}
		$arResult['PRODUCT_CNT'] = 0;
		foreach ($sectionList as $section)
		{
			$arResult['PRODUCT_CNT'] += $section['ELEMENT_CNT'];
			foreach ($section['UF_NEWS_LINK'] as $newsId)
			{
				$news[$newsId]['SECTIONS'][] = $section['NAME'];
			}
		}
		$arResult["MIN_PRICE"] = min($arAllPrice);

		$arResult["MAX_PRICE"] = max($arAllPrice);
		$APPLICATION->AddViewContent(
			"min_price",
			"Минимальная цена:" . $arResult["MIN_PRICE"]
		);
		$APPLICATION->AddViewContent(
			"max_price",
			"Максимальная цена:". $arResult["MAX_PRICE"]
		);
		$arResult['NEWS'] = $news;
		$this->SetResultCacheKeys(['PRODUCT_CNT']);
		$this->includeComponentTemplate();
	}
} else
{
	$this->abortResultCache();
}
$APPLICATION->SetTitle(GetMessage('COUNT_CATALOG_PRODUCT') . $arResult['PRODUCT_CNT']);
?>