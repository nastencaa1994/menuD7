<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule('iblock')) {
    return;
}

$arIBlockType = CIBlockParameters::GetIBlockTypes();
$arInfoBlocks = array();

if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilter['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}

// если уже выбран тип инфоблока, выбираем инфоблоки только этого типа
if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilterInfoBlocks['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}
// метод выборки информационных блоков
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y'));
// перебираем и выводим в адмику доступные информационные блоки
while ($obIBlock = $rsIBlock->Fetch()) {
//    $arInfoBlocksCode[$obIBlock['CODE']] = '[' . $obIBlock['ID'] . '] ' . $obIBlock['NAME'];
    $arInfoBlocksID[$obIBlock['ID']] = '[' . $obIBlock['ID'] . '] ' . $obIBlock['NAME'];
}
// настройки компонента, формируем массив $arParams
$arComponentParameters = array(
    "GROUPS" => array(),
    'PARAMETERS' => array(
        // выбор типа инфоблока
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Выберите инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlocksID,
            'REFRESH' => 'Y',
        ),
        'CACHE_TIME' => array(
            'DEFAULT' => 360000
        ),
        "TITLE_MENU"=>array(
            'PARENT' => 'BASE',
            'NAME' => 'Заголовок блока',
            'TYPE' => 'STRING',
            'VALUES' => 'Меню',
        ),
    ),
);
