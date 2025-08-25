<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Post;
use common\models\Category;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\data\ActiveDataProvider;

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
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        // ÚJ 3 OSZLOPOS MAGAZIN-STÍLUSÚ ELRENDEZÉS
        // Összes cikk lekérése időrendben 100-150 darabra optimalizálva
        $allPosts = Post::getPublished()
            ->orderBy(['published_at' => SORT_DESC])
            ->limit(150) // Nagy mennyiségre optimalizálva
            ->all();

        // CIKKEK EGYENLETES ELOSZTÁSA 3 OSZLOPRA
        $column1Posts = []; // Bal oszlop - 4 cikk elrendezésben
        $column2Posts = []; // Közép oszlop - 3 cikk (első kiemelt lead)  
        $column3Posts = []; // Jobb oszlop - 4 cikk elrendezésben

        // Egyenletes elosztás időrend szerint
        // 1. oszlop: 1., 4., 7., 10., 13., 16., 19., stb. (minden 3. + 1)
        // 2. oszlop: 2., 5., 8., 11., 14., 17., 20., stb. (minden 3. + 2)  
        // 3. oszlop: 3., 6., 9., 12., 15., 18., 21., stb. (minden 3. + 0)
        
        foreach ($allPosts as $index => $post) {
            $positionInCycle = $index % 3;
            
            if ($positionInCycle === 0) {
                $column1Posts[] = $post;
            } elseif ($positionInCycle === 1) {
                $column2Posts[] = $post;
            } else {
                $column3Posts[] = $post;
            }
        }

        // OSZLOPOK LIMITÁLÁSA A TERVEZETT ELRENDEZÉSRE
        // Első betöltéskor: 1. oszlop 4 cikk, 2. oszlop 3 cikk, 3. oszlop 4 cikk
        $column1Initial = array_slice($column1Posts, 0, 4);
        $column2Initial = array_slice($column2Posts, 0, 3);
        $column3Initial = array_slice($column3Posts, 0, 4);

        // TOVÁBBI TARTALOM AJAX-HOS
        $column1More = array_slice($column1Posts, 4);
        $column2More = array_slice($column2Posts, 3);
        $column3More = array_slice($column3Posts, 4);

        // NÉPSZERŰ CIKKEK (jobb oldali kiegészítő blokk)
        $popularPosts = Post::getPublished()
            ->where(['>=', 'published_at', strtotime('-7 days')])
            ->orderBy(['view_count' => SORT_DESC])
            ->limit(8)
            ->all();

        // Ha nincs elég a héten, akkor kiegészítjük általánosból
        if (count($popularPosts) < 6) {
            $additionalPosts = Post::getPublished()
                ->orderBy(['view_count' => SORT_DESC])
                ->limit(8 - count($popularPosts))
                ->all();
            $popularPosts = array_merge($popularPosts, $additionalPosts);
        }

        // CÍMKÉK SZEKCIÓ (jobb oldali kiegészítő)
        $tagSections = [];
        try {
            $popularTagsQuery = "
                SELECT t.*, COUNT(pt.post_id) as post_count 
                FROM {{%tags}} t 
                INNER JOIN {{%post_tags}} pt ON t.id = pt.tag_id 
                INNER JOIN {{%posts}} p ON pt.post_id = p.id 
                WHERE t.status = :tag_status 
                AND p.status = :post_status 
                AND p.visibility = :post_visibility
                GROUP BY t.id 
                ORDER BY post_count DESC 
                LIMIT 2
            ";
            
            $popularTagsData = Yii::$app->db->createCommand($popularTagsQuery, [
                ':tag_status' => \common\models\Tag::STATUS_ACTIVE,
                ':post_status' => Post::STATUS_PUBLISHED,
                ':post_visibility' => Post::VISIBILITY_PUBLIC
            ])->queryAll();

            foreach ($popularTagsData as $tagData) {
                $tag = \common\models\Tag::findOne($tagData['id']);
                if ($tag) {
                    $posts = Post::getPublished()
                        ->joinWith('tags')
                        ->where(['{{%tags}}.id' => $tag->id])
                        ->orderBy(['published_at' => SORT_DESC])
                        ->limit(3)
                        ->all();
                    
                    if (!empty($posts)) {
                        $tagSections[] = [
                            'tag' => $tag,
                            'posts' => $posts
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Yii::warning('Címkék lekérdezése sikertelen: ' . $e->getMessage(), __METHOD__);
            $tagSections = [];
        }

        return $this->render('index', [
            // 3 oszlop fő tartalma
            'column1Posts' => $column1Initial,
            'column2Posts' => $column2Initial,
            'column3Posts' => $column3Initial,
            
            // További tartalmak AJAX-hoz
            'hasMoreColumn1' => !empty($column1More),
            'hasMoreColumn2' => !empty($column2More),
            'hasMoreColumn3' => !empty($column3More),
            
            // Kiegészítő tartalmak
            'popularPosts' => $popularPosts,
            'tagSections' => $tagSections,
            
            // Meta információk
            'totalPosts' => count($allPosts),
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

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
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Választási térkép oldal.
     *
     * @return mixed
     */
    public function actionElectionMap()
    {
        return $this->render('election-map');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->verifyEmail()) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
