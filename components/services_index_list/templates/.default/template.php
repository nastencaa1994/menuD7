<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

function el($elAr)
{
    foreach ($elAr as $el) {
        ?>
        <div class="a_list">
            <a href="<?= $el['url'] ?>"><?= $el['name'] ?></a>
        </div>
        <?php
    }
}

function section($arSection)
{
    $two_section_inner=['217','208','169', '225'];
    $free_section_inner=['213','171','172'];
    $margin_top_minus=['168'];
    foreach ($arSection as $childSection) {
        ?>
        <div class="block_section_list <?= in_array($childSection['id'],$margin_top_minus) ? 'margin_top_minus' : '' ?><?=in_array($childSection['id'],$two_section_inner) ? 'two_section_inner': ''?><?=in_array($childSection['id'],$free_section_inner) ? 'free_section_inner': ''?>">
            <div class="title_section_min">
                <a href="<?= $childSection['url'] ?>"><span><?= $childSection['name_section'] ?></span></a>
            </div>
            <?php

            if ($childSection['element'] != '') {
                el($childSection['element']);
            }
            if ($childSection['child_section'] != '') {
                section($childSection['child_section']);
            }
            ?>
        </div>
        <?
    }
}
$crutchForLayout=['189','152','153','154','156','207', '150'];
?>
<div class="services_new_list">
    <div class="container">
        <div class="title_block">
            <h2><?= $arParams['~TITLE_MENU'] ?></h2>
        </div>
        <div class="services_new_list_inner">
            <? foreach ($arResult as $items): ?>
                <div class="section_block_main">
                    <div class="main_title">
                        <div class="img" style="background-image: url('<?= CFile::getPath($items['img']) ?>')"></div>
                        <div class="text">
                            <div class="section_title_main"><a
                                        href="<?= $items['url'] ?>"><?= $items['name_section'] ?></a></div>
                            <div class="text_main"><?= $items['description'] ?>
                            </div>
                        </div>
                    </div>
                    <div class="list_services">
                        <? if (!in_array($items['id'],$crutchForLayout)): ?>
                        <div class="block_section_list">
                            <div class="title_section_min">
                                <a href="<?= $items['url'] ?>">
                                    <span><?= $items['uf_title']!='' ? $items['uf_title'] : $items['name_section'] ?></span></a>
                            </div>
                            <? endif; ?>
                            <? if ($items['element'] != '') {
                                el($items['element']);
                            } ?>
                            <? if (!in_array($items['id'],$crutchForLayout)): ?>
                        </div>
                    <? endif; ?>
                        <?
                        if ($items['child_section'] != '') {
                            section($items['child_section']);
                        }
                        ?>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    </div>
</div>



