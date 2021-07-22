<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
const IBLOCK_ZALOB = 8;
if (isset($arResult['CANONICAL']))
{
	$APPLICATION->SetPageProperty('canonical', $arResult['CANONICAL']);
}
if ($_REQUEST['zal'] === '1' && ($newId = (int)$_REQUEST['id']))
{
	$name = session_id() . '_' . $newId;
	$res = CIBlockElement::GetList(
		false,
		[
			'IBLOCK_ID' => IBLOCK_ZALOB,
			'NAME' => $name,
			'ACTIVE' => 'Y',
		],
		false,
		[
			'nTopCount' => 1,
		],
		[
			'ID',
		]
	);
	if ($res->SelectedRowsCount())
	{
		$result = GetMessage('TRUE');
	} else
	{
		global $USER;
		if ($userId = $USER->GetID())
		{
			$propUser = $userId . ', ' . $USER->GetLogin() . ', ' . $USER->GetFullName();
		} else
		{
			$propUser = GetMessage('NOT_AUTH');
		}

		$el = new CIBlockElement;
		if ($newZaloba = $el->Add([
			'IBLOCK_ID' => IBLOCK_ZALOB,
			'NAME' => $name,
			'ACTIVE_FROM' => date('d.m.Y H:i:s', time()),
			'PROPERTY_VALUES' => [
				'USER' => $propUser,
				'NEW' => $newId,
			],
		]))
		{
			$result = GetMessage('FINISH') . $newZaloba;
		} else
		{
			$result = GetMessage('ERROR');
		}
	}

	if (isset($_REQUEST['ajax']))
	{
		$APPLICATION->RestartBuffer();
		die($result);
	} else
	{
		?>
		<script>
				BX('result_zalob').innerHTML = '<?=$result?>';
				BX.show(BX('result_zalob'));
		</script>
		<?php
	}
}