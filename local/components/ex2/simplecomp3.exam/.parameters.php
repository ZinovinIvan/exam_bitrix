<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentParameters = [
	"PARAMETERS" => [
		"NEWS_IBLOCK_ID" => [
			"NAME" => GetMessage("NEWS_IBLOCK_ID"),
			"TYPE" => "STRING",
		],
		"PROPERTY_AUTHOR_CODE" => [
			"NAME" => GetMessage("PROPERTY_AUTHOR_CODE"),
			"TYPE" => "STRING",
		],
		"FIELD_AUTHOR_CODE" => [
			"NAME" => GetMessage("FIELD_AUTHOR_CODE"),
			"TYPE" => "STRING",
		],
		"CACHE_TIME" => ["DEFAULT" => 3600],
	],
];