<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Product;
use app\models\ProductItem;
use app\models\Rating;
use app\models\RatingItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'products', 'product'],
                'rules' => [
                    [
                        'actions' => ['logout', 'products', 'product', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function actionProducts() 
    {
        $model = new ProductItem();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            $this->goHome();
        }
        $products = ProductItem::getProducts($model);
        return $this->render('products', [
            'products' => $products,
        ]);
    }
    
    public function actionProduct($id)
    {
        $product = Product::findOne($id);
        if (is_null($product)) {
            throw new NotFoundHttpException('Product does not exist.');
        }
        return $this->render('product', [
            'name' => $product->Name,
            'rows' => RatingItem::getRatings($id),
            'avgRating' => Rating::find()->where(['ProdID' => $id])->average('Rating'),
            'sumRating' => Rating::find()->where(['ProdID' => $id])->sum('Rating'),
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionDelete($id)
    {
        $model = Rating::findOne([
            'ProdID' => $id, 
            'UserID' => Yii::$app->user->id
        ]);
        if (!is_null($model) && $model->delete()) {
            $this->goHome();
        }
        throw new BadRequestHttpException('Rating does not delete.');
    }
}
