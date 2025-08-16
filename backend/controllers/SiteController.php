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
                        'actions' => ['login', 'error', 'admin-register', 'forgot-password', 'reset-password'],
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
        // Statisztikai adatok gyűjtése
        $userStats = [
            'total' => \common\models\User::find()->count(),
            'active' => \common\models\User::find()->where(['status' => \common\models\User::STATUS_ACTIVE])->count(),
            'inactive' => \common\models\User::find()->where(['status' => \common\models\User::STATUS_INACTIVE])->count(),
            'recent' => \common\models\User::find()->where(['>=', 'created_at', time() - 86400 * 7])->count(), // utolsó 7 nap
        ];

        $categoryStats = [
            'total' => \common\models\Category::find()->count(),
            'active' => \common\models\Category::find()->where(['status' => \common\models\Category::STATUS_ACTIVE])->count(),
            'inactive' => \common\models\Category::find()->where(['status' => \common\models\Category::STATUS_INACTIVE])->count(),
            'recent' => \common\models\Category::find()->where(['>=', 'created_at', time() - 86400 * 7])->count(),
        ];

        $tagStats = [
            'total' => \common\models\Tag::find()->count(),
            'active' => \common\models\Tag::find()->where(['status' => \common\models\Tag::STATUS_ACTIVE])->count(),
            'inactive' => \common\models\Tag::find()->where(['status' => \common\models\Tag::STATUS_INACTIVE])->count(),
            'recent' => \common\models\Tag::find()->where(['>=', 'created_at', time() - 86400 * 7])->count(),
        ];

        $mediaStats = [
            'total' => \common\models\Media::find()->count(),
            'active' => \common\models\Media::find()->where(['status' => \common\models\Media::STATUS_ACTIVE])->count(),
            'images' => \common\models\Media::find()->where(['media_type' => \common\models\Media::TYPE_IMAGE])->count(),
            'totalSize' => \common\models\Media::find()->sum('file_size') ?: 0,
            'recent' => \common\models\Media::find()->where(['>=', 'created_at', time() - 86400 * 7])->count(),
        ];

        return $this->render('index', [
            'userStats' => $userStats,
            'categoryStats' => $categoryStats,
            'tagStats' => $tagStats,
            'mediaStats' => $mediaStats,
        ]);
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
                Yii::$app->session->setFlash('success', 'Jelszó visszaállítási linket küldtünk az e-mail címedre, amennyiben létező email címet adtál meg. Ellenőrizd a postafiókodat.');
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
     * Jelszó visszaállítás token alapján
     *
     * @param string $token
     * @return string|Response
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new \frontend\models\ResetPasswordForm($token);
        } catch (\yii\base\InvalidArgumentException $e) {
            Yii::$app->session->setFlash('error', 'Érvénytelen vagy lejárt jelszó visszaállítási link.');
            return $this->redirect(['login']);
        }

        $this->layout = 'blank';

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Új jelszó sikeresen beállítva. Most már bejelentkezhet az új jelszavával.');
            return $this->redirect(['login']);
        }

        return $this->render('reset-password', [
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
