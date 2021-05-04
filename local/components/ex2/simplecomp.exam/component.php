<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if (!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}
if ($this->StartResultCache())
{
	if (!$iblockProd = (int)$arParams['PRODUCTS_IBLOCK_ID'])
	{
		return false;
	}
	if (!$iblockNews = (int)$arParams['NEWS_IBLOCK_ID'])
	{
		return false;
	}
	if (!$propCode = trim($arParams['PROPERTY_CODE']))
	{
		return false;
	}
	$arAllSection = [];
	$arIdNews = [];
	$resSection = CIBlockSection::GetList(
		false,
		[
			'IBLOCK_ID' => $iblockProd,
			'ACTIVE' => 'Y',
			'!' . $propCode => false
		],
		true,
		[
			'ID',
			'NAME',
			$propCode
		]
	);
	while ($arSection = $resSection->GetNext())
	{
		if ($arSection['ELEMENT_CNT'] > 0)
		{
			$arAllSection[$arSection['ID']] = [
				'NAME' => $arSection['NAME'],
				'NEWS' => $arSection[$propCode]
			];
			foreach ($arSection[$propCode] as $newsId)
			{
				if (!in_array($newsId, $arIdNews))
				{
					$arIdNews[] = $newsId;
				}
			}
		}
	}

	$arAllNews = [];
	if (!empty($arIdNews))
	{
		$resNews = CIBlockElement::GetList(
			false,
			[
				'IBLOCK_ID' => $iblockNews,
				'ACTIVE' => 'Y',
				'ID' => $arIdNews
			],
			false,
			false,
			[
				'ID',
				'NAME',
				'ACTIVE_FROM'
			]
		);
		while ($arNew = $resNews->GetNext())
		{
			$arAllNews[$arNew['ID']] = [
				'NAME' => $arNew['NAME'],
				'ACTIVE_FROM' => $arNew['ACTIVE_FROM'],
				'SECTIONS' => [],
				'PRODUCTS' => []
			];
		}
	}
	$minPrice = 999999999;
	$maxPrice = 0;
	$arAllProduct = [];
	if (!empty($arAllSection))
	{
		$resProducts = CIBlockElement::GetList(
			false,
			[
				'IBLOCK_ID' => $iblockProd,
				'ACTIVE' => 'Y',
				'SECTION_ID' => array_keys($arAllSection)
			],
			false,
			false,
			[
				'ID',
				'NAME',
				'IBLOCK_SECTION_ID',
				'PROPERTY_PRICE',
				'PROPERTY_ARTNUMBER',
				'PROPERTY_MATERIAL',
			]
		);
		while ($arProduct = $resProducts->GetNext())
		{
			$productId = $arProduct['ID'];
			$price = $arProduct['PROPERTY_PRICE_VALUE'];
			if ($price < $minPrice)
			{
				$minPrice = $price;
			}
			if ($price > $maxPrice)
			{
				$maxPrice = $price;
			}
			$arAllProduct[$productId] = [
				'NAME' => $arProduct['NAME'],
				'PRICE' => $price,
				'ARTNUMBER' => $arProduct['PROPERTY_ARTNUMBER_VALUE'],
				'MATERIAL' => $arProduct['PROPERTY_MATERIAL_VALUE'],

			];
			$IBLOCK_SECTION_ID = $arProduct['IBLOCK_SECTION_ID'];
			foreach ($arAllSection[$IBLOCK_SECTION_ID]['NEWS'] as $newsId)
			{
				$arAllNews[$newsId]['PRODUCTS'][] = $productId;
				if (!in_array($IBLOCK_SECTION_ID, $arAllNews[$newsId]['SECTIONS']))
				{
					$arAllNews[$newsId]['SECTIONS'][] = $IBLOCK_SECTION_ID;
				}
			}
		}
	}
	$arResult['ITEMS'] = $arAllNews;
	$arResult['ALL_PRODUCTS'] = $arAllProduct;
	$arResult['ALL_SECTIONS'] = $arAllSection;
	$arResult['COUNT_PRODUCTS'] = count($arAllProduct);
	$arResult['MIN_PRICE'] = $minPrice;
	$arResult['MAX_PRICE'] = $maxPrice;
	$this->SetResultCacheKeys([
		"COUNT_PRODUCTS",
		"MIN_PRICE",
		"MAX_PRICE"
	]);
	$this->includeComponentTemplate();
}
$APPLICATION->SetTitle(GetMessage('SET_TITLE') . $arResult['COUNT_PRODUCTS']);
?>