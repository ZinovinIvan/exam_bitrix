<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент 3");
?><?$APPLICATION->IncludeComponent(
	"ex2:simplecomp3.exam",
	"",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"FIELD_AUTHOR_CODE" => "UF_AUTHOR_TYPE",
		"NEWS_IBLOCK_ID" => "1",
		"PROPERTY_AUTHOR_CODE" => "AUTHOR"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>