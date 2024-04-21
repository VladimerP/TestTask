<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
\CModule::IncludeModule("iblock");
use CustomCalendar;
if($arParams['ID_IBLOCK']) {
    $calendar = new CustomCalendar(date('d.m.Y'), $arParams['ID_IBLOCK']);
    $arResult['DATE_CHECK'] = date('m.Y');
    $arResult['FIRST_DAY_WEEK'] = $calendar->dayWeak();
    $arResult['DAY_IN_MONTHS'] = $calendar->dayInMonths();
    $arResult['EVENT'] = $calendar->getEvent();
} else {
    $arResult['ERROR'] = 'Отсутствует код инфоблока. Настройте пожалуйста компонент.';
}
$this->IncludeComponentTemplate();
