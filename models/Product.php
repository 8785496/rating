<?php

namespace app\models;
/**
 * This is the model class for table "products".
 *
 * @property integer $ProdID
 * @property string $Name
 */
class Product extends \yii\db\ActiveRecord 
{
    public static function tableName()
    {
        return 'products';
    }
}
