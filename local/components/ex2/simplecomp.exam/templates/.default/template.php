<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<? $this->AddEditAction('iblock_'.$arResult["IBLOCK_ID"], $arResult['ADD_ELEMENT_LINK'], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_ADD")); ?>
<ul id="<?=$this->GetEditAreaId('iblock_'.$arResult["IBLOCK_ID"])?>">
	<?foreach ($arResult['NEWS'] as $newsId=>$news):?>
		<li>
			<b><?=$news['NAME']?></b> - <?=$news['ACTIVE_FROM']?> (<?=implode(', ', $news['SECTIONS'])?>)
			<ul>
				<?foreach ($news['PRODUCTS'] as $product):?>
				<?
				$ermitId =$newsId.'_'.$product['ID'];
					$this->AddEditAction($ermitId, $product['EDIT_LINK'], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($ermitId, $product['DELETE_LINK'], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
					<li id="<?=$this->GetEditAreaId($ermitId)?>">
						<?=$product['NAME']?> - <?=$product['PROPERTY_PRICE_VALUE']?> - <?=$product['PROPERTY_MATERIAL_VALUE']?> -  <?=$product['PROPERTY_ARTNUMBER_VALUE']?>
					</li>
				<?endforeach;?>
			</ul>
		</li>
	<?endforeach;?>
</ul>