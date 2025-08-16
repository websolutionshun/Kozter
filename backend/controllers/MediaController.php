<?php

namespace backend\controllers;

use Yii;
use common\models\Media;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

/**
 * MediaController implements the CRUD actions for Media model.
 */
class MediaController extends Controller
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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity && Yii::$app->user->identity->hasPermission('media_view');
                        },
                    ],
                    [
                        'actions' => ['create', 'upload', 'ajax-upload'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity && Yii::$app->user->identity->hasPermission('media_create');
                        },
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity && Yii::$app->user->identity->hasPermission('media_update');
                        },
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity && Yii::$app->user->identity->hasPermission('media_delete');
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'ajax-upload' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Media models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->view->title = 'Média kezelése';
        $this->view->params['breadcrumbs'][] = ['label' => 'Média', 'url' => ['index']];

        $dataProvider = new ActiveDataProvider([
            'query' => Media::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Media model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $this->view->title = 'Média részletei';
        $this->view->params['breadcrumbs'][] = ['label' => 'Média', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = $model->original_name;

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Media model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Media();
        
        $this->view->title = 'Média feltöltése';
        $this->view->params['breadcrumbs'][] = ['label' => 'Média', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = 'Feltöltés';

        if ($model->load(Yii::$app->request->post())) {
            $model->uploadedFile = UploadedFile::getInstance($model, 'uploadedFile');
            
            if ($model->uploadedFile && $model->upload()) {
                Yii::$app->session->setFlash('success', 'A média sikeresen feltöltve!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Media model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $this->view->title = 'Média szerkesztése';
        $this->view->params['breadcrumbs'][] = ['label' => 'Média', 'url' => ['index']];
        $this->view->params['breadcrumbs'][] = ['label' => $model->original_name, 'url' => ['view', 'id' => $model->id]];
        $this->view->params['breadcrumbs'][] = 'Szerkesztés';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'A média adatai sikeresen frissítve!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Media model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'A média sikeresen törölve!');
        } else {
            Yii::$app->session->setFlash('error', 'Hiba történt a média törlése során!');
        }

        return $this->redirect(['index']);
    }

    /**
     * AJAX fájl feltöltés drag & drop funkcióhoz
     * @return array
     */
    public function actionAjaxUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new Media();
        $model->uploadedFile = UploadedFile::getInstanceByName('file');
        
        if ($model->uploadedFile && $model->upload()) {
            return [
                'success' => true,
                'message' => 'Fájl sikeresen feltöltve!',
                'data' => [
                    'id' => $model->id,
                    'filename' => $model->filename,
                    'original_name' => $model->original_name,
                    'file_size' => $model->getHumanFileSize(),
                    'media_type' => $model->getMediaTypeName(),
                    'file_url' => $model->getFileUrl(),
                    'thumbnail_url' => $model->getThumbnailUrl(),
                    'created_at' => Yii::$app->formatter->asDatetime($model->created_at),
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Hiba történt a fájl feltöltése során!',
                'errors' => $model->errors
            ];
        }
    }

    /**
     * Bulk delete action
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $ids = Yii::$app->request->post('selection', []);
        
        if (empty($ids)) {
            Yii::$app->session->setFlash('warning', 'Nem választottál ki egyetlen médiát sem!');
            return $this->redirect(['index']);
        }

        $deletedCount = 0;
        foreach ($ids as $id) {
            $model = Media::findOne($id);
            if ($model && $model->delete()) {
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            Yii::$app->session->setFlash('success', $deletedCount . ' média sikeresen törölve!');
        } else {
            Yii::$app->session->setFlash('error', 'Nem sikerült törölni a kiválasztott médiákat!');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A kért oldal nem található.');
    }
}
