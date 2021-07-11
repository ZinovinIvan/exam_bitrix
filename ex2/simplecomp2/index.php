<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Мой компонент2");
?><?$APPLICATION->IncludeComponent(
	"ex2:simplecomp2.exam", 
	".default", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"FIRMS_IBLOCK_ID" => "7",
		"LINK_TEMPLATE" => "catalog_exam/#SECTION_ID#/#ELEMENT_CODE#",
		"PRODUCT_IBLOCK_ID" => "2",
		"PROPERTY_FIRMS_CODE" => "FIRMS",
		"COMPONENT_TEMPLATE" => ".default",
		"COUNT_ELS" => "2"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>