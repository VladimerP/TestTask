<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/local/components/custom.calendar/class.php');
\CModule::IncludeModule("iblock");
use \Bitrix\Main\Error,
    \Bitrix\Main\Application,
    \Bitrix\Main\ErrorCollection,
    \Bitrix\Main\Engine\Response\AjaxJson,
    CustomCalendar;

    $request = Application::getInstance()
    ->getContext()
    ->getRequest();

if($request->get('activity') == 'saveNote') {
    $el = new CIBlockElement;
    $dateTimestamp = strtotime($request->get('DATE_EVENT'));
    $formattedDate = date('d.m.Y', $dateTimestamp);
    $arLoadProductArray = Array(
        "MODIFIED_BY" => $USER->GetID(), 
        "IBLOCK_SECTION_ID" => false, 
        "IBLOCK_ID" => $request->get('iBlockId'),
        "PROPERTY_VALUES"=> [
            'DATE_EVENT'=> $formattedDate,
            'TITLE_EVENT'=> $request->get('TITLE_EVENT'),
            'DETAIL_EVENT'=> $request->get('DETAIL_EVENT'),
        ],
        "NAME" => "Запись ".$USER->GetFullName(),
    );

    if($PRODUCT_ID = $el->Add($arLoadProductArray))
        $response = AjaxJson::createSuccess(['id'=>$PRODUCT_ID]);
    else
        $response = AjaxJson::createError(new ErrorCollection([new Error("Some thing wrong")]));

    $response->send();
}

elseif($request->get('activity') == 'reloadTable') {
    $calendar = new CustomCalendar('01.'.$request->get('date'), $request->get('iBlockId'));
    $arResult['FIRST_DAY_WEEK'] = $calendar->dayWeak();
    $arResult['DAY_IN_MONTHS'] = $calendar->dayInMonths();
    $arResult['EVENT'] = $calendar->getEvent();
    $response = AjaxJson::createSuccess($arResult);
    $response->send();
}

elseif($request->get('activity') == 'getNote') {
    $calendar = new CustomCalendar('', $request->get('iBlockId'));
    $arResult['EVENT'] = $calendar->getNote($request->get('ID'));
    $response = AjaxJson::createSuccess($arResult);
    $response->send();
}