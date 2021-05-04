<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (isset($arResult['CANNONICAL_LINK'])){
	$APPLICATION->SetPageProperty('canonical',$arResult['CANNONICAL_LINK']);
}