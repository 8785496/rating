<?php

namespace app\models;

use Yii;

/**
 * Description of ProductItem
 *
 * @author german
 */
class ProductItem extends \yii\base\Model
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
                $products[] = new ProductItem($item);
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
        $model = Rating::findOne(['ProdID' => $this->ProdID, 
            'UserID' => Yii::$app->user->id
        ]);
        if(is_null($model)){
            $model = new Rating();
            $model->ProdID = $this->ProdID;
            $model->UserID = Yii::$app->user->id;
            $model->Rating = $this->Rating;
            $model->Comment = $this->Comment;
        } else {
            $model->Rating = $this->Rating;
            $model->Comment = $this->Comment;
        }
        return $model->save();
    }
}
