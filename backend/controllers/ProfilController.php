<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ProfilController a bejelentkezett felhasználó profiljának kezeléséhez
 */
class ProfilController extends Controller
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
                        'allow' => true,
                        'roles' => ['@'], // Csak bejelentkezett felhasználók
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * A bejelentkezett felhasználó profiljának megjelenítése
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = $this->getCurrentUser();
        
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * A bejelentkezett felhasználó profiljának szerkesztése
     *
     * @return string|Response
     */
    public function actionSzerkesztes()
    {
        $model = $this->getCurrentUser();
        
        if ($model->load(Yii::$app->request->post())) {
            // Új jelszó beállítása ha megadták
            $newPassword = Yii::$app->request->post('new_password');
            if (!empty($newPassword)) {
                $model->setPassword($newPassword);
                $model->generateAuthKey();
            }
            
            // Profilkép feltöltés kezelése
            $uploadedFile = UploadedFile::getInstanceByName('profile_image');
            if ($uploadedFile) {
                $uploadPath = Yii::getAlias('@frontend/web/uploads/profiles/');
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $fileName = uniqid() . '.' . $uploadedFile->extension;
                $filePath = $uploadPath . $fileName;
                
                if ($uploadedFile->saveAs($filePath)) {
                    // Régi kép törlése
                    if ($model->profile_image && file_exists(Yii::getAlias('@frontend/web/') . $model->profile_image)) {
                        unlink(Yii::getAlias('@frontend/web/') . $model->profile_image);
                    }
                    $model->profile_image = 'uploads/profiles/' . $fileName;
                }
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Profil sikeresen frissítve.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('szerkesztes', [
            'model' => $model,
        ]);
    }

    /**
     * A bejelentkezett felhasználó profilképének törlése
     *
     * @return Response
     */
    public function actionProfilkepTorles()
    {
        $model = $this->getCurrentUser();
        
        if ($model->profile_image) {
            $filePath = Yii::getAlias('@frontend/web/') . $model->profile_image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $model->profile_image = null;
            $model->save(false);
            
            Yii::$app->session->setFlash('success', 'Profilkép sikeresen törölve.');
        }
        
        return $this->redirect(['szerkesztes']);
    }

    /**
     * A jelenlegi bejelentkezett felhasználó lekérése
     *
     * @return User
     * @throws NotFoundHttpException
     */
    protected function getCurrentUser()
    {
        if (Yii::$app->user->isGuest) {
            throw new NotFoundHttpException('Nincs bejelentkezett felhasználó.');
        }
        
        $model = User::findOne(Yii::$app->user->id);
        if ($model === null) {
            throw new NotFoundHttpException('A felhasználó nem található.');
        }
        
        return $model;
    }
}
