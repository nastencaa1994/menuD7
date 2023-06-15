<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\ORM\PropertyValue;

\Bitrix\Main\Loader::includeModule('iblock');

class services_index_list extends CBitrixComponent
{
//    public $test;

    protected function checkModules()
    {
        if (!Loader::includeModule('boubad')) {
            ShowError(Loc::getMessage('PROPERTY_TEXT_MODULE_NOT_INSTALLED'));
            return false;
        }
        if (!Loader::includeModule('iblock')) {
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
        }
        return true;
    }

    public function executeComponent()
    {
        $this->includeComponentLang('class.php');

        if ($this->checkModules()) {
            $this->getResult();
            global $USER;
        }

    }

    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }
    public function sectionInner($idSection,$idIBlock, $depthLevel){
        $rsSectionLevalNext = \Bitrix\Iblock\SectionTable::getList(array(
            'order'=>array('SORT' => 'ASC'),
            'filter' => array(
                'IBLOCK_ID' => $idIBlock,
                "DEPTH_LEVEL"=>$depthLevel,
                'IBLOCK_SECTION_ID'=>$idSection,
                'ACTIVE' => 'Y',
                'GLOBAL_ACTIVE' => 'Y',

            ),
            'select' => array('ID', 'CODE', 'NAME','SORT','DEPTH_LEVEL'),

        ))->fetchAll();
        foreach ($rsSectionLevalNext as $items){
            $resSection[]=[
                'name_section'=>$items['NAME'],
                'url'=>'/services/'.$items['CODE'].'/',
                'id'=>$items['ID'],
                'depth_level'=>$items['DEPTH_LEVEL'],
                'child_section'=> $this->sectionInner($items['ID'],$this->arParams['IBLOCK_ID'],$items['DEPTH_LEVEL']+1),
                'element'=>$this->elementInner($this->arParams['IBLOCK_ID'], $items['ID'],$items['CODE'])
            ];
        }
        return $resSection;
    }
    public  function elementInner($idBlock, $idSection,$sectionCode){

        $dbItems = \Bitrix\Iblock\Elements\ElementServicesNewTable::getList(array(
            'order' => array('SORT' => 'ASC'), // сортировка
            'select' => array('ID', 'NAME','CODE','TITLE_MENU_'=>'TITLE_MENU'),
            'filter' => array('IBLOCK_SECTION_ID'=>$idSection,'ACTIVE' => 'Y'),
            'count_total' => 1,
        ))->fetchAll();
        foreach ($dbItems as $el){
            if($sectionCode!=$el['CODE']){
                $url='/services/'.$sectionCode.'/'.$el['CODE'].'/';
                $resElement[]=[
                    'name'=>$el['TITLE_MENU_VALUE']!='' ? $el['TITLE_MENU_VALUE'] : $el['NAME'] ,
                    'url'=>$url,
                ];
            }
        }
        return $resElement;
    }
    public function getResult()
    {
        if ($this->arParams['IBLOCK_ID'] != '') {
            if ($this->startResultCache()) {
                $entity = \Bitrix\Iblock\Model\Section::compileEntityByIblock($this->arParams['IBLOCK_ID']);
                $rsSectionLevalOne = $entity::getList(
                    array(
                        'order'=>array('SORT' => 'ASC'),
                        'select' => array('ID', 'NAME', 'CODE', 'SORT','DEPTH_LEVEL','PICTURE','DESCRIPTION','UF_TITLE'),
                        'filter' => array('=IBLOCK_ID' => $this->arParams['IBLOCK_ID'], 'DEPTH_LEVEL' => 1)
                    )
                )->fetchAll();
                foreach ($rsSectionLevalOne as $index=>$sectionOne){
                    $this->arResult[$index] = [
                        'img'=>$sectionOne['PICTURE'],
                        'uf_title'=>$sectionOne['UF_TITLE'],
                        'description'=>$sectionOne['DESCRIPTION'],
                        'name_section'=>$sectionOne['NAME'],
                        'url'=>'/services/'.$sectionOne['CODE'].'/',
                        'id'=>$sectionOne['ID'],
                        'depth_level'=>$sectionOne['DEPTH_LEVEL'],
                        'child_section'=>$this->sectionInner($sectionOne['ID'],$this->arParams['IBLOCK_ID'],$sectionOne['DEPTH_LEVEL']+1),
                        'element'=>$this->elementInner($this->arParams['IBLOCK_ID'], $sectionOne['ID'],$sectionOne['CODE'])
                    ];
                }

                if (isset($this->arResult)) {
                    $this->SetResultCacheKeys(
                        array()
                    );
                    $this->IncludeComponentTemplate();
                } else {
                    $this->AbortResultCache();
                    \Bitrix\Iblock\Component\Tools::process404(
                        Loc::getMessage('PAGE_NOT_FOUND'),
                        true,
                        true
                    );
                }
            }


        } else {
            echo '<h2 style="color: #7d1515">Ошибка. Настройте все параметры. Символьный код API Инфоблока должен быть заполнен. Так же доступ через REST должен быть влючен.</h2>';
        }

    }


}

;