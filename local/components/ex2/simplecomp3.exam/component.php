<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


use Bitrix\Main\Loader;


if (!isset($arParams["CACHE_TIME"]))
{
	$arParams["CACHE_TIME"] = 36000000;
}

if (!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}


global $USER;
$arResult["COUNT"] = 0;
if ($USER->IsAuthorized())
{
	\CModule::IncludeModule("iblock");
	$currentUserId = $USER->GetId();

	$currentUserType = \CUser::GetList(
		($by = "id"),
		($order = "asc"),
		["ID" => $USER->GetId()],
		["SELECT" => [$arParams["FIELD_AUTHOR_CODE"]]]
	)->Fetch()[$arParams["FIELD_AUTHOR_CODE"]];
	global $CACHE_MANAGER;
	if ($this->startResultCache(false, $currentUserType) && !empty($currentUserType))
	{
		$CACHE_MANAGER->RegisterTag('iblock_id_3');
		$users = \CUser::GetList(
			($by = "id"),
			($order = "desc"),
			[
				$arParams["FIELD_AUTHOR_CODE"] => $currentUserType,
				"!ID" => $currentUserId,
			],
			["SELECT" => ["LOGIN", "ID"]]
		);
		while ($user = $users->Fetch())
		{
			$userList[$user["ID"]] = ["LOGIN" => $user["LOGIN"]];
			$userIds[] = $user["ID"];
		}
		if (!empty($userIds))
		{
			$news = \CIBlockElement::GetList(
				[],
				[
					"IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
					"!PROPERTY_" . $arParams["PROPERTY_AUTHOR_CODE"] => $currentUserId,
					"PROPERTY_" . $arParams["PROPERTY_AUTHOR_CODE"] => $userIds,
				],
				false, false,
				["NAME", "ACTIVE_FROM", "ID", "IBLOCK_ID", "PROPERTY_" . $arParams["PROPERTY_AUTHOR_CODE"]]
			);

			while ($newsItem = $news->Fetch())
			{
				if (empty($newsList[$newsItem["ID"]]))
				{
					$newsList[$newsItem["ID"]] = $newsItem;
				}
				$newsList[$newsItem["ID"]]["AUTHORS"][] = $newsItem["PROPERTY_" . $arParams["PROPERTY_AUTHOR_CODE"] . "_VALUE"];
			};
			$newsCount = count($newsList);
			foreach ($newsList as $key => $value)
			{
				foreach ($value["AUTHORS"] as $authorId)
				{
					$userList[$authorId]["NEWS"][] = $value;
				}
			}
			$arResult["AUTHORS"] = $userList;
			$arResult["COUNT"] = $newsCount;
			$this->SetResultCacheKeys(["COUNT"]);
			$this->IncludeComponentTemplate();
			$this->endResultCache();
		}
	} else
	{
		$this->abortResultCache();
	}
}
$APPLICATION->SetTitle(GetMessage("COUNT_NEWS") . $arResult["COUNT"]);