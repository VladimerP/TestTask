<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true){die();}
\CModule::IncludeModule("iblock");

class CustomCalendar
{
    protected $date;
    protected $iBlockEvent;

    function __construct($date = '', $iBlockEvent) {
        $this->date = $date;
        $this->iBlockEvent = $iBlockEvent;
    }
    public function getEvent() {
        $arFilter =[
            'IBLOCK_ID' => $this->iBlockEvent,
            '>=PROPERTY_DATE_EVENT' => date('01'.$this->date),
            '<=PROPERTY_DATE_EVENT' => date('t').'.'.date($this->date),
        ];
        $eventElement = \CIBlockElement::GetList([], $arFilter, false, false, ['ID','PROPERTY_DATE_EVENT', 'PROPERTY_TITLE_EVENT']);
        $events = [];
        while($event = $eventElement->fetch())
        {
            $events[$event['PROPERTY_DATE_EVENT_VALUE']][] = $event;
        }

        return $events;
    }

    public function getNote($id) {
        $arFilter =[
            'IBLOCK_ID' => $this->iBlockEvent,
            'ID' => $id,
        ];
        $eventElement = \CIBlockElement::GetList([], $arFilter, false, false, ['PROPERTY_DATE_EVENT', 'PROPERTY_TITLE_EVENT', 'PROPERTY_DETAIL_EVENT'])->fetch();

        return $eventElement;
    }

    public function dayWeak() {
        return (int)date('N', strtotime( date('01.m.Y', strtotime($this->date))));
    }

    public function dayInMonths() {
        $timestamp = strtotime($this->date);
        return cal_days_in_month(CAL_GREGORIAN, date('m', $timestamp), date('Y', $timestamp));
    }
}