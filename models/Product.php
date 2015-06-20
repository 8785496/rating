<?php

namespace app\models;

use Yii;
use yii\db\Query;

class Product extends \yii\base\Model
{
    public $ProdID;
    public $Name;
    public $Rating;
    public $Comment;

    public static function getProducts($model)
    {
        $db = Yii::$app->db;
        $query = <<<EOT
SELECT `products`.`ProdID`, `products`.`Name`, `ratings`.`Rating`, `ratings`.`Comment`
FROM `products`
LEFT JOIN `ratings` ON `ratings`.`ProdID` = `products`.`ProdID` 
AND `ratings`.`UserID` = :id;
EOT;
        $items = $db->createCommand($query)
            ->bindValue(':id', Yii::$app->user->id)
            ->queryAll();
        $products = [];
        foreach ($items as $item) {
            if($model->ProdID == $item['ProdID']){
                $model->Name = $item['Name'];
                $products[] = $model;
            } else {
                $products[] = new self($item);
            }
        }
        return $products;
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ProdID', 'Rating', 'Comment'], 'required'],
            ['Rating', 'integer', 'max' => 5, 'min' => 1],
            ['Comment', 'string', 'max' => 256]
        ];
    }
    
    public function save() 
    {
        $model = (new Query())->from('ratings')->where([
                'ProdID' => $this->ProdID,
                'UserID' => Yii::$app->user->id
            ])->one();
        $db = Yii::$app->db;
        if ($model) {
            return $db->createCommand()->update('ratings', [
                'Rating' => $this->Rating,
                'Comment' => $this->Comment
            ], [
                'ProdID' => $this->ProdID,
                'UserID' => Yii::$app->user->id,
            ])->execute();
        } else {
            return $db->createCommand()->insert('ratings', [
                'ProdID' => $this->ProdID,
                'UserID' => Yii::$app->user->id,
                'Rating' => $this->Rating,
                'Comment' => $this->Comment
            ])->execute();
        }
    }
    
    public static function findOne($id)
    {
        return (new Query())->from('products')->where([
            'ProdID' => $id,
        ])->one();
    }
}
