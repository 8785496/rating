<?php

namespace app\models;

class RatingItem extends \yii\base\Object
{
    public static function getRatings($id)
    {
        $db = \Yii::$app->db;
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
}
