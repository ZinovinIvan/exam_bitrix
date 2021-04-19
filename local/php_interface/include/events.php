<?php
AddEventHandler("main", "OnBeforeEventAdd", "OnBeforeEventAddHandler");

function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
{
	if ($event === 'FEEDBACK_FORM')
	{
		global $USER;
		if ($userId = $USER->GetID())
		{
			$rsUser = CUser::GetByID($userId);
			$arUser = $rsUser->Fetch();
			$arFields['AUTHOR'] = GetMessage('AUTH', [
					'#ID#' => $arUser['ID'],
					'#LOGIN#' => $arUser['LOGIN'],
					'#NAME#' => $arUser['LAST_NAME'] . ' ' . $arUser['NAME'] . ' ' . $arUser['SECOND_NAME']
				]) . $arFields['AUTHOR'];
		} else
		{
			$arFields['AUTHOR'] = GetMessage('NOT_AUTH') . $arFields['AUTHOR'];
		}
		CEventLog::Add(array(
			"SEVERITY" => "INFO",
			"AUDIT_TYPE_ID" => "FEEDBACK_FORM",
			"MODULE_ID" => "main",
			"DESCRIPTION" => GetMessage('DESCRIPTION').$arFields['AUTHOR'],
		));
	}
}