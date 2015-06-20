<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Product;
use app\models\Rating;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

use yii\web\Controller;

class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['products', 'product', 'delete'],
                'rules' => [
                    [
                        'actions' => ['products', 'product', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionProducts() 
    {
        $model = new Product();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            $this->goHome();
        }
        $products = Product::getProducts($model);
        return $this->render('products', [
            'products' => $products,
        ]);
    }
    
    public function actionProduct($id)
    {
        $product = Product::findOne($id);
        if (!$product) {
            throw new NotFoundHttpException('Product does not exist.');
        }
        return $this->render('product', [
            'name' => $product['Name'],
            'rows' => Rating::getRatings($id),
            'avgRating' => Rating::average($id),
            'sumRating' => Rating::sum($id),
        ]);
    }
    
    public function actionDelete($id)
    {
        $model = Rating::findOne($id, Yii::$app->user->id);
        if ($model && $model->delete()) {
            $this->goHome();
        }
        throw new BadRequestHttpException('Rating does not delete.');
    }
}
