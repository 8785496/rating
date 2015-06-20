<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\base\Object;

class Rating extends Object
{
    public $ProdID;
    public $UserID;
    public $Rating;
    public $Comment;
    
    public static function getRatings($id)
    {
        $db = Yii::$app->db;
        $query = <<<EOT
SELECT `users`.`Name`, `ratings`.`Rating`, `ratings`.`Comment`
FROM `users`
NATURAL JOIN `ratings`
WHERE `ratings`.`ProdID` = :id;
EOT;
        return $db->createCommand($query)
            ->bindValue(':id', $id)
            ->queryAll();
    }
    
    public static function average($id)
    {
        return (new Query())->from('ratings')->where([
            'ProdID' => $id,
        ])->average('Rating');
    }
    
    public static function sum($id)
    {
        return (new Query())->from('ratings')->where([
            'ProdID' => $id,
        ])->sum('Rating');
    }
    
    public static function findOne($ProdID, $UserID)
    {
        $model = (new Query())->from('ratings')->where([
            'ProdID' => $ProdID,
            'UserID' => $UserID,
        ])->one();
        
        if ($model) {
            return new self($model);
        } else {
            return false;
        }
    }
    
    public function delete()
    {
        $db = Yii::$app->db;
        return $db->createCommand()->delete('ratings', [
            'ProdID' => $this->ProdID,
            'UserID' => $this->UserID,
        ])->execute();
    }
}
