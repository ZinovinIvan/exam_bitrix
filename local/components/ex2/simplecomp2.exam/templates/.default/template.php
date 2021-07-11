<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<p><b><?= GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE") ?></b></p>
<ul>
	<?
	foreach ($arResult["CLASSIFIER"] as $section)
	{
		if (!empty($section["NAME"]))
		{
			?>
			<li><b><?= $section["NAME"] ?></b></li>
			<ul>
				<?
				if (is_array($section["ELEMENTS_ID"]) && (count($section["ELEMENTS_ID"]) > 0))
				{
				foreach ($section["ELEMENTS_ID"] as $elem)
				{
				?>
				<li><?= $arResult["ELEMENTS"][$elem]["NAME"] ?> - <?= $arResult["ELEMENTS"][$elem]["PROP"]["PRICE"]["VALUE"] ?>
					- <?= $arResult["ELEMENTS"][$elem]["PROP"]["MATERIAL"]["VALUE"] ?>
					(<?= $arResult["ELEMENTS"][$elem]["LINK_TEMPLATE"] ?>)<?
					}
					}
					?>
				</li>
			</ul>
			<?
		}
	}
	?>
</ul>
<b><?= GetMessage("NAVY") ?></b>
<? echo $arResult["NAV_STRING"] ?>
<? $sCurPage = $APPLICATION->GetCurPage() . "?F=Y"; ?>
Тест фильтра: <a href="<?= $sCurPage ?>"><?= $sCurPage ?></a>