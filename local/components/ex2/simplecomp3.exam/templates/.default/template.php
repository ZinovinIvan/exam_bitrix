<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?=GetMessage("SIMPLECOMP_EXAM2_CAT_TIME")?><?echo time();?><br>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<ul>
	<?foreach ($arResult['AUTHORS'] as $id => $author):?>
		<li>
			[<?=$id?>] - <?=$author['LOGIN']?>
			<ul>
				<?foreach ($author['NEWS'] as $news):?>
					<li>
						- <?=$news['NAME']?>
					</li>
				<?endforeach;?>
			</ul>
		</li>
	<?endforeach;?>
</ul>