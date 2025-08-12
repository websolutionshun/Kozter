<?php

namespace backend\controllers;

use backend\models\AdminRegistrationForm;
use backend\models\ForgotPasswordForm;
use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'admin-register', 'forgot-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Admin registration action.
     *
     * @return string|Response
     */
    public function actionAdminRegister()
    {
        // Ellenőrizzük, hogy az env változó be van-e állítva
        $adminKey = $_ENV['ADMIN_ADD_USER_MANUAL'] ?? null;
        if (empty($adminKey)) {
            throw new \yii\web\NotFoundHttpException('Az oldal nem található.');
        }

        $this->layout = 'blank';

        $model = new AdminRegistrationForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Admin felhasználó sikeresen létrehozva!');
            return $this->redirect(['login']);
        }

        return $this->render('admin-register', [
            'model' => $model,
        ]);
    }

    /**
     * Elfelejtett jelszó action.
     *
     * @return string|Response
     */
    public function actionForgotPassword()
    {
        $this->layout = 'blank';

        $model = new ForgotPasswordForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Jelszó visszaállítási linket küldtünk az e-mail címére. Kérjük, ellenőrizze a postafiókját.');
                return $this->redirect(['login']);
            } else {
                Yii::$app->session->setFlash('error', 'Sajnos nem sikerült elküldeni az e-mailt. Kérjük, próbálja újra később.');
            }
        }

        return $this->render('forgot-password', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
