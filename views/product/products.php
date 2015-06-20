<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Prpducts';
?>
<?php $ratingList = ['' => 'Rating', '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5] ?>
<?php foreach ($products as $product): ?>
<?php
if (is_null($product->Rating)) {
    $buttonName = 'Send';
    $buttonClass = 'btn btn-success';
} else {
    $buttonName = 'Refresh';
    $buttonClass = 'btn btn-primary';
}
?>
<div class="row">
    <div class="col-lg-3">
        <h4>
            <?=
            Html::a(Html::encode($product->Name), 
                    Url::toRoute(['product/product', 'id' => $product->ProdID]))
            ?>            
            <?php
            if (!is_null($product->Rating) && empty($product->errors)) {
                echo Html::a('Delete Rating', [
                    'product/delete', 'id' => $product['ProdID']], [
                        'class' => 'btn btn-xs btn-danger pull-right',
                        'data' => [
                            'confirm' => 'Are you sure you wont to delete this rating?',
                            'method' => 'post',
                        ],
                    ]
                );
            }
            ?>
        </h4>
    </div>
    <div class="col-lg-9">
        <?php
        $form = ActiveForm::begin(['method' => 'post',
            'options' => ['class' => 'form-horizontal'],
        ]);
        ?>
        <?= Html::input('hidden', 'Product[ProdID]', $product['ProdID']) ?>
        <div class="form-group">
            <div class="col-sm-12">
                <div>
                <?= $form->field($product, 'Comment')
                    ->textarea(['rows' => 4, 'class' => 'form-control', 'placeholder' => 'Enter your comment...'])
                    ->label(false) ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <?= $form->field($product, 'Rating')
                    ->dropDownList($ratingList)
                    ->label(false) ?>
            </div>
            <div class="col-sm-9">
                <?= Html::submitButton($buttonName, ['class' => $buttonClass]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php endforeach ?>
