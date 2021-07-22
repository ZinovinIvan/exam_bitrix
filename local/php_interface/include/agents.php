<?php
const MAIL_TEMPLATE = 32;
const GROUP_ADMIN = 1;

function checkUserCount()
{
	$lastUserId = COption::GetOptionInt('main', 'last_user_id', 0);
	$resUser = CUser::GetList(
		($by = 'id'),
		($order = 'desc'),
		[
			'>ID' => $lastUserId,
		],
		[
			'FIELDS' => [
				'ID',
			],
		]
	);
	if ($count_user = $resUser->SelectedRowsCount())
	{
		$arUser = $resUser->Fetch();
		$newLastUserId = $arUser['ID'];
		if ($timeCheckUser = COption::GetOptionInt('main', 'time_check_user', 0))
		{
			$days = round((time() - $timeCheckUser) / 86400);
			if (!$days)
			{
				$days = 1;
			}
		} else
		{
			$days = 1;
		}
		if ($days === 1)
		{
			$days .= GetMessage('DAY_1');
		} elseif ($days > 4)
		{
			$days .= GetMessage('DAY_5');
		} else
		{
			$days .= GetMessage('DAY_2');
		}

		$arFields = [
			'COUNT' => $count_user,
			'DAYS' => $days,
		];

		$resUser = CUser::GetList(
			($by = 'id'),
			($order = 'desc'),
			[
				'GROUPS_ID' => [
					GROUP_ADMIN,
				],
			],
			[
				'FIELDS' => [
					'ID',
					'EMAIL',
				],
			]
		);
		while ($userAdmin = $resUser->Fetch())
		{
			$arFields['EMAIL'] = $userAdmin['EMAIL'];
			CEvent::SendImmediate(
				'NEW_REGISTRATION',
				's1',
				$arFields,
				'N',
				MAIL_TEMPLATE
			);
		}
		COption::SetOptionInt('main', 'last_user_id', $newLastUserId);
	}
	COption::SetOptionInt('main', 'time_check_user', time());
	return 'CheckUserCount();';
}