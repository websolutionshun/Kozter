<?php

namespace backend\controllers;

use common\models\Log;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'stats'],
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {
                                $user = Yii::$app->user->identity;
                                if (!$user) {
                                    return false;
                                }
                                return $user->hasPermission('log_view') || 
                                       $user->hasPermission('admin_panel');
                            },
                        ],
                        [
                            'actions' => ['delete', 'bulk-delete', 'clear-old'],
                            'allow' => true,
                            'matchCallback' => function ($rule, $action) {
                                $user = Yii::$app->user->identity;
                                if (!$user) {
                                    return false;
                                }
                                return $user->hasPermission('log_manage') || 
                                       $user->hasPermission('admin_panel');
                            },
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                        'bulk-delete' => ['POST'],
                        'clear-old' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Log models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Log::find();

        // Szűrések
        if ($level = Yii::$app->request->get('level')) {
            $query->andWhere(['level' => $level]);
        }

        if ($category = Yii::$app->request->get('category')) {
            $query->andWhere(['like', 'category', $category]);
        }

        if ($search = Yii::$app->request->get('search')) {
            $query->andWhere(['like', 'message', $search]);
        }

        if ($userId = Yii::$app->request->get('user_id')) {
            $query->andWhere(['user_id' => $userId]);
        }

        // Dátum szűrés (utolsó 24 óra, 7 nap, 30 nap, stb.)
        if ($dateFilter = Yii::$app->request->get('date_filter')) {
            $timestamp = time();
            switch ($dateFilter) {
                case 'today':
                    $query->andWhere(['>=', 'created_at', $timestamp - 86400]);
                    break;
                case 'week':
                    $query->andWhere(['>=', 'created_at', $timestamp - (86400 * 7)]);
                    break;
                case 'month':
                    $query->andWhere(['>=', 'created_at', $timestamp - (86400 * 30)]);
                    break;
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'levels' => Log::getLevels(),
            'categories' => $this->getCategories(),
        ]);
    }

    /**
     * Displays a single Log model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing Log model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Log bejegyzés sikeresen törölve.');
        return $this->redirect(['index']);
    }

    /**
     * Tömeges törlés
     */
    public function actionBulkDelete()
    {
        $ids = Yii::$app->request->post('selection');
        if (empty($ids)) {
            Yii::$app->session->setFlash('error', 'Nincs kiválasztott elem.');
            return $this->redirect(['index']);
        }

        $count = Log::deleteAll(['id' => $ids]);
        
        Yii::$app->session->setFlash('success', "{$count} log bejegyzés sikeresen törölve.");
        return $this->redirect(['index']);
    }

    /**
     * Régi logok törlése
     */
    public function actionClearOld()
    {
        $days = (int) Yii::$app->request->post('days', 30);
        $timestamp = time() - ($days * 86400);
        
        $count = Log::deleteAll(['<', 'created_at', $timestamp]);
        
        Yii::$app->session->setFlash('success', "{$count} régi log bejegyzés törölve ({$days} napnál régebbiek).");
        return $this->redirect(['index']);
    }

    /**
     * Statisztikák megjelenítése
     */
    public function actionStats()
    {
        $stats = [];
        
        // Szint szerinti statisztika
        $levelStats = Log::find()
            ->select(['level', 'COUNT(*) as count'])
            ->groupBy('level')
            ->asArray()
            ->all();
        
        $stats['levels'] = [];
        foreach ($levelStats as $stat) {
            $stats['levels'][$stat['level']] = $stat['count'];
        }

        // Kategória szerinti statisztika
        $categoryStats = Log::find()
            ->select(['category', 'COUNT(*) as count'])
            ->where(['!=', 'category', null])
            ->groupBy('category')
            ->orderBy('count DESC')
            ->limit(10)
            ->asArray()
            ->all();
        
        $stats['categories'] = $categoryStats;

        // Napi statisztika (utolsó 7 nap)
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', time() - ($i * 86400));
            $startTimestamp = strtotime($date . ' 00:00:00');
            $endTimestamp = strtotime($date . ' 23:59:59');
            
            $count = Log::find()
                ->where(['between', 'created_at', $startTimestamp, $endTimestamp])
                ->count();
                
            $dailyStats[] = [
                'date' => $date,
                'count' => $count,
            ];
        }
        $stats['daily'] = $dailyStats;

        // Összes log száma
        $stats['total'] = Log::find()->count();
        
        // Mai logok száma
        $todayStart = strtotime(date('Y-m-d') . ' 00:00:00');
        $stats['today'] = Log::find()->where(['>=', 'created_at', $todayStart])->count();

        return $this->render('stats', [
            'stats' => $stats,
            'levels' => Log::getLevels(),
        ]);
    }

    /**
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Log::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A kért oldal nem található.');
    }

    /**
     * Elérhető kategóriák lekérése
     */
    private function getCategories()
    {
        return Log::find()
            ->select('category')
            ->distinct()
            ->where(['!=', 'category', null])
            ->orderBy('category')
            ->column();
    }
}
