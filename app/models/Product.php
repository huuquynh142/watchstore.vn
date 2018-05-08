<?php
/**
 * Created by PhpStorm.
 * User: huuqu
 * Date: 5/8/2018
 * Time: 5:49 AM
 */

namespace App\Models;


class Product extends ProductAbstract
{

    public static function allTypeProduct(){
        return array(
            'tat-ca-san-pham' => '' ,
            'san-pham-nam' => 1 ,
            'san-pham-nu' => 2 ,
            'san-pham-the-thao' => 3 ,
            'san-pham-sang-trong' => 4,
            'san-pham-giao-duc' => 5
        );
    }
}