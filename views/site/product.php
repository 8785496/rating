<?php

use yii\helpers\Html;

$this->title = Html::encode($name);
//$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::tag('h1', $this->title) ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Username</th>
            <th>Rating</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <?= Html::tag('td', Html::encode($row['Name'])) ?>
                <?= Html::tag('td', Html::encode($row['Rating'])) ?>
                <?= Html::tag('td', Html::encode($row['Comment'])) ?>
            </tr>        
        <?php endforeach ?>
    </tbody>    
</table>
Суммарный рейтинг: <?= $sumRating ?>
<br>
Средний рейтинг: <?= $avgRating ?>
