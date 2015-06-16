<?php

namespace app\models;

/**
 * This is the model class for table "ratings".
 *
 * @property integer $ProdID
 * @property integer $UserID
 * @property integer $Rating
 * @property string $Comment
 */
class Rating extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'ratings';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ProdID', 'UserID', 'Rating', 'Comment'], 'required'],
        ];
    }
}