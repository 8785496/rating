<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Prpducts';
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Rating</th>
        </tr>
    </thead> 
    <tbody>
        <?php $ratingList = ['' => 'Rating', '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5] ?>
        <?php foreach ($products as $product): ?>
            <tr>
                <?= Html::tag('td', Html::encode($product['ProdID'])) ?>
                <td>
                    <?=
                    Html::a(Html::encode($product['Name']), 
                            Url::toRoute(['site/product', 'id' => (int) $product['ProdID']]))
                    ?>
                </td>
                <td>
                    <?php
                    if (!is_null($product['Rating'])) {
                        echo Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', 
                            ['rating/delete', 'id' => $product['ProdID']], 
                            [
                                'class' => 'btn btn-xs btn-danger pull-right',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить это сообщение?',
                                    'method' => 'post',
                                ],
                            ]
                        );
                    }
                    ?>
                    <?php
                    if (is_null($product['Rating'])) {
                        $action = ['rating/create'];
                        $buttonName = 'Send';
                        $buttonClass = 'btn btn-success';
                    } else {
                        $action = ['rating/update', 'id' => (int) $product['ProdID']];
                        $buttonName = 'Refresh';
                        $buttonClass = 'btn btn-primary';
                    }
                    ?>
                    <?php
                    $form = ActiveForm::begin([
                                'action' => $action,
                                'method' => 'post',
                                'options' => ['class' => 'form-horizontal'],
                    ]);
                    ?>
                            <?= Html::input('hidden', 'Rating[ProdID]', (int) $product['ProdID']) ?>
                    <div class="form-group">
                        <div class="control-label col-sm-12">
                            <?=
                            Html::tag('textarea', Html::encode($product['Comment']), [
                                'class' => 'form-control',
                                'rows' => 4,
                                'name' => 'Rating[Comment]',
                                'required' => 'required',
                                'placeholder' => 'Enter your comment...',
                            ])
                            ?>    
                        </div> 
                    </div>
                    <div class="form-group">
                        <div class="control-label col-sm-3">
                            <?= Html::dropDownList('Rating[Rating]', $product['Rating'], $ratingList, ['class' => 'form-control', 'required' => 'required']) ?>
                        </div>
                        <div class="control-label col-sm-9">
                            <?= Html::submitButton($buttonName, ['class' => $buttonClass]) ?>
                            
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </td>
            </tr>        
        <?php endforeach ?>
    </tbody>  
</table>
