<?php

class WbsmdLocalizationHelper {
    public static function choice_item_class($percentage) {
        switch (true) {
            case $percentage <= 20:
                return WBSMD_GREEN_ITEM;
            case $percentage > 20 && $percentage <= 50:
                return WBSMD_ORANGE_ITEM;
            case $percentage > 50:
                return WBSMD_RED_ITEM;
            default:
                return null;
        }
    }

    public static function choice_item_class_by_coeff($coeff) {
        switch (true) {
            case $coeff === 1:
                return WBSMD_GREEN_ITEM;
            case $coeff === 0.5:
                return WBSMD_ORANGE_ITEM;
            case $coeff === 0:
                return WBSMD_RED_ITEM;
            default:
                return null;
        }
    }
    
    public static function get_cat_title( $name ) {
        switch( $name ) {
            case 'news':
                return 'Новини';
            case 'events':
                return 'Анонси';
            default:
                return '';
        }
    }
    
    public static function remove_symbol_from_url( $url ) {
        return str_replace('/','',str_replace('https:','',str_replace('http:','',$url)));
    }

    public static function get_section_title( $name ) {
        switch( $name ) {
            case 'uk':
                return 'Україномовна';
            case 'eng':
                return 'Англомовна';
            default:
                return '';
        }
    }
}