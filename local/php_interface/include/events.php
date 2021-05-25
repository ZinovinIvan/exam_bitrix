<?php
const MANAGER_GROUP = 5;
AddEventHandler('main', 'OnBuildGlobalMenu', 'onBuildGlobalMenuHandler', 1);
function onBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu)
{
	global $USER;
	if (in_array(MANAGER_GROUP, $USER->GetUserGroupArray()))
	{
		foreach ($aGlobalMenu as $key => $item)
		{
			if ($key !== 'global_menu_content')
			{
				unset($aGlobalMenu[$key]);
			}
		}
		foreach ($aModuleMenu as $key => $item)
		{
			if ($item['items_id'] !== 'menu_iblock_/news')
			{
				unset($aModuleMenu[$key]);
			}
		}
	}
}
