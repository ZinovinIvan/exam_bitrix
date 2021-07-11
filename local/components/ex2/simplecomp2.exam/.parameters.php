<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentParameters = [
	"PARAMETERS" => [
		"PRODUCT_IBLOCK_ID" => [
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_PRODUCT_IBLOCK"),
			"TYPE" => "STRING",
		],
		"FIRMS_IBLOCK_ID" => [
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_FIRMS_IBLOCK"),
			"TYPE" => "STRING",
		],
		"PROPERTY_FIRMS_CODE" => [
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_PROPERTY_FIRMS"),
			"TYPE" => "STRING",
		],
		"CACHE_TIME" => ["DEFAULT" => 3600],
	],
];