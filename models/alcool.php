<?php
/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

class Alcool extends ObjectModel
{
    public static $definition = array(
        'table' => 'product_containing_alcohol',
        'primary' => 'id_product_containing_alcohol',
        'multilang' => false,
        'fields' => array(
            'id_product' => array(
                'type' => parent::TYPE_INT,
                'required' => true
            ),
            'alcohol' => array(
                'type' => parent::TYPE_BOOL,
                'required' => true
            )
        ),
    );




    private static function alcoolInsert(int $id_product, bool $alcool)
    {
        $values = array(
            'id_product' => $id_product,
            'alcohol' => $alcool ,
        );

        Db::getInstance()->insert(self::$definition['table'], $values);

        return Db::getInstance()->Insert_ID();
    }

    private static function alcoolUpdate(int $id_product, bool $alcool)
    {
        $values = array(
            'alcohol' => $alcool
        );

        Db::getInstance()->update(self::$definition['table'], $values, 'id_product =' .$id_product);

        return Db::getInstance()->Insert_ID();
    }

    public static function alcoolExist(int $id_product)
    {
        $sql = 'SELECT id_product_containing_alcohol FROM `' . _DB_PREFIX_ . self::$definition['table']
            . '`WHERE id_product = "' . $id_product .'"';

        if (Db::getInstance()->getValue($sql)) {
            return  true;
        } else {
            return false;
        }
    }

    public static function alcoolSave(int $id_product, bool $alcool)
    {
        if (self::alcoolExist($id_product)) {
            self::alcoolUpdate($id_product, $alcool);
        } else {
            self::alcoolInsert($id_product, $alcool);
        }
    }

    public static function alcoolGet(int $id_product)
    {
        $sql = 'SELECT alcohol FROM `' . _DB_PREFIX_ . self::$definition['table']
            . '`WHERE id_product = "' . $id_product .'"';


        return Db::getInstance()->getValue($sql);
    }
}
