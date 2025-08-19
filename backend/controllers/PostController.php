<?php

namespace backend\controllers;

use Yii;
use common\models\Post;
use common\models\PostCategory;
use common\models\PostTag;
use common\models\Category;
use common\models\Tag;
use common\models\Media;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * PostController implements WordPress-style post management functionality.
 */
class PostController extends Controller
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
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            /** @var \common\models\User $user */
                            $user = Yii::$app->user->identity;
                            if (!$user) {
                                return false;
                            }

                            switch ($action->id) {
                                case 'index':
                                    return $user->hasPermission('post_view') || $user->hasPermission('admin_panel');
                                case 'create':
                                    return $user->hasPermission('post_create') || $user->hasPermission('admin_panel');
                                case 'update':
                                case 'quick-edit':
                                case 'toggle-status':
                                    return $user->hasPermission('post_edit') || $user->hasPermission('admin_panel');
                                case 'view':
                                    return $user->hasPermission('post_view') || $user->hasPermission('admin_panel');
                                case 'delete':
                                case 'bulk-delete':
                                    return $user->hasPermission('post_delete') || $user->hasPermission('admin_panel');
                                case 'bulk-publish':
                                    return $user->hasPermission('post_edit') || $user->hasPermission('admin_panel');
                                case 'generate-test-post':
                                    return $user->hasPermission('post_create') || $user->hasPermission('admin_panel');
                                default:
                                    return $user->hasPermission('post_view') || $user->hasPermission('admin_panel');
                            }
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models in WordPress style.
     * @return string
     */
    public function actionIndex()
    {
        $query = Post::find()->with(['author', 'categories', 'tags', 'featuredImage']);
        
        // Szűrés státusz szerint
        $status = Yii::$app->request->get('status');
        if ($status !== null && $status !== '') {
            $query->andWhere(['status' => $status]);
        }

        // Szűrés kategória szerint
        $categoryId = Yii::$app->request->get('category');
        if ($categoryId) {
            $query->joinWith('categories')->andWhere(['categories.id' => $categoryId]);
        }

        // Keresés
        $search = Yii::$app->request->get('search');
        if ($search) {
            $query->andWhere(['or',
                ['like', 'title', $search],
                ['like', 'content', $search],
                ['like', 'excerpt', $search]
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $categories = Category::getActive()->all();
        $authors = User::find()->all();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'categories' => $categories,
            'authors' => $authors,
            'statusFilter' => $status,
            'categoryFilter' => $categoryId,
            'searchQuery' => $search,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Post();
        $model->status = Post::STATUS_DRAFT;
        $model->visibility = Post::VISIBILITY_PUBLIC;
        $model->comment_status = Post::COMMENT_ENABLED;
        $model->author_id = Yii::$app->user->id;
        $model->seo_robots = 'index,follow';

        if ($model->load(Yii::$app->request->post())) {
            // Debug információ
            Yii::error('POST adatok: ' . print_r(Yii::$app->request->post(), true));
            Yii::error('Model status: ' . $model->status);
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Publikálás dátumának beállítása
                if ($model->status == Post::STATUS_PUBLISHED && !$model->published_at) {
                    $model->published_at = time();
                }

                if ($model->save()) {
                    // Kategóriák mentése
                    $this->saveCategories($model, Yii::$app->request->post('categories', []));
                    
                    // Címkék mentése
                    $this->saveTags($model, Yii::$app->request->post('tags', []));

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Bejegyzés sikeresen létrehozva.');
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Hiba a bejegyzés mentése során: ' . print_r($model->errors, true));
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Hiba történt: ' . $e->getMessage());
            }
        }

        $categories = Category::getActive()->all();
        $tags = Tag::getActive()->all();
        $mediaFiles = Media::getActive()->where(['media_type' => Media::TYPE_IMAGE])->all();

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'tags' => $tags,
            'mediaFiles' => $mediaFiles,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Publikálás dátumának beállítása
                if ($model->status == Post::STATUS_PUBLISHED && !$model->published_at) {
                    $model->published_at = time();
                }

                if ($model->save()) {
                    // Kategóriák mentése
                    $this->saveCategories($model, Yii::$app->request->post('categories', []));
                    
                    // Címkék mentése
                    $this->saveTags($model, Yii::$app->request->post('tags', []));

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Bejegyzés sikeresen frissítve.');
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Hiba a bejegyzés mentése során.');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Hiba történt: ' . $e->getMessage());
            }
        }

        $categories = Category::getActive()->all();
        $tags = Tag::getActive()->all();
        $mediaFiles = Media::getActive()->where(['media_type' => Media::TYPE_IMAGE])->all();
        $selectedCategories = ArrayHelper::map($model->categories, 'id', 'id');
        $selectedTags = ArrayHelper::map($model->tags, 'id', 'id');

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
            'tags' => $tags,
            'mediaFiles' => $mediaFiles,
            'selectedCategories' => $selectedCategories,
            'selectedTags' => $selectedTags,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Kapcsolatok törlése
            PostCategory::deleteAll(['post_id' => $model->id]);
            PostTag::deleteAll(['post_id' => $model->id]);
            
            $model->delete();
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Bejegyzés sikeresen törölve.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Hiba a bejegyzés törlése során: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Bulk delete posts (WordPress style)
     * @return Response
     */
    public function actionBulkDelete()
    {
        $ids = Yii::$app->request->post('selection', []);
        
        if (empty($ids)) {
            Yii::$app->session->setFlash('error', 'Nincs kiválasztott bejegyzés.');
            return $this->redirect(['index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $count = 0;
            foreach ($ids as $id) {
                $model = $this->findModel($id);
                
                // Kapcsolatok törlése
                PostCategory::deleteAll(['post_id' => $model->id]);
                PostTag::deleteAll(['post_id' => $model->id]);
                
                if ($model->delete()) {
                    $count++;
                }
            }
            
            $transaction->commit();
            if ($count > 0) {
                Yii::$app->session->setFlash('success', "{$count} bejegyzés sikeresen törölve.");
            } else {
                Yii::$app->session->setFlash('error', 'Nem sikerült törölni a kiválasztott bejegyzéseket.');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Hiba történt: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Bulk publish posts
     * @return Response
     */
    public function actionBulkPublish()
    {
        $ids = Yii::$app->request->post('selection', []);
        
        if (empty($ids)) {
            Yii::$app->session->setFlash('error', 'Nincs kiválasztott bejegyzés.');
            return $this->redirect(['index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $count = 0;
            foreach ($ids as $id) {
                $model = $this->findModel($id);
                
                // Csak akkor publikáljuk, ha még nem publikált
                if ($model->status != Post::STATUS_PUBLISHED) {
                    $model->status = Post::STATUS_PUBLISHED;
                    
                    // Publikálás dátumának beállítása, ha nincs még beállítva
                    if (!$model->published_at) {
                        $model->published_at = time();
                    }
                    
                    if ($model->save(false, ['status', 'published_at'])) {
                        $count++;
                    }
                }
            }
            
            $transaction->commit();
            if ($count > 0) {
                Yii::$app->session->setFlash('success', "{$count} bejegyzés sikeresen közzétéve.");
            } else {
                Yii::$app->session->setFlash('info', 'A kiválasztott bejegyzések már közzé vannak téve.');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Hiba történt: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * AJAX quick edit functionality (WordPress style)
     * @param int $id
     * @return array
     */
    public function actionQuickEdit($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();

        if (isset($data['title'])) {
            $model->title = $data['title'];
        }
        if (isset($data['slug'])) {
            $model->slug = $data['slug'];
        }
        if (isset($data['status'])) {
            $model->status = $data['status'];
        }
        if (isset($data['visibility'])) {
            $model->visibility = $data['visibility'];
        }

        if ($model->save()) {
            return [
                'success' => true,
                'message' => 'Bejegyzés sikeresen frissítve.',
                'data' => [
                    'id' => $model->id,
                    'title' => $model->title,
                    'slug' => $model->slug,
                    'status' => $model->getStatusName(),
                    'visibility' => $model->getVisibilityName(),
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Hiba a mentés során.',
                'errors' => $model->errors
            ];
        }
    }

    /**
     * Toggle post status (AJAX)
     * @param int $id
     * @return array
     */
    public function actionToggleStatus($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = $this->findModel($id);
        $model->status = $model->status == Post::STATUS_PUBLISHED ? Post::STATUS_DRAFT : Post::STATUS_PUBLISHED;
        
        // Publikálás dátumának beállítása
        if ($model->status == Post::STATUS_PUBLISHED && !$model->published_at) {
            $model->published_at = time();
        }
        
        if ($model->save(false, ['status', 'published_at'])) {
            return [
                'success' => true,
                'status' => $model->status,
                'statusName' => $model->getStatusName()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Hiba az állapot módosítása során.'
            ];
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A keresett bejegyzés nem található.');
    }

    /**
     * Kategóriák mentése
     * @param Post $model
     * @param array $categoryIds
     */
    private function saveCategories($model, $categoryIds)
    {
        // Régi kapcsolatok törlése
        PostCategory::deleteAll(['post_id' => $model->id]);
        
        // Új kapcsolatok létrehozása
        foreach ($categoryIds as $categoryId) {
            if ($categoryId) {
                $postCategory = new PostCategory();
                $postCategory->post_id = $model->id;
                $postCategory->category_id = $categoryId;
                $postCategory->save();
            }
        }
    }

    /**
     * Címkék mentése
     * @param Post $model
     * @param array $tagIds
     */
    private function saveTags($model, $tagIds)
    {
        // Régi kapcsolatok törlése
        PostTag::deleteAll(['post_id' => $model->id]);
        
        // Új kapcsolatok létrehozása
        foreach ($tagIds as $tagId) {
            if ($tagId) {
                $postTag = new PostTag();
                $postTag->post_id = $model->id;
                $postTag->tag_id = $tagId;
                $postTag->save();
            }
        }
    }

    /**
     * Teszt bejegyzés generálása
     * @return array
     */
    public function actionGenerateTestPost()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        try {
            // Ellenőrizzük a jogosultságokat
            $user = Yii::$app->user->identity;
            if (!$user || (!$user->hasPermission('post_create') && !$user->hasPermission('admin_panel'))) {
                return [
                    'success' => false,
                    'message' => 'Nincs jogosultságod teszt bejegyzés létrehozásához.'
                ];
            }

            // Véletlenszerű kép kiválasztása a médiatárból
            $randomMedia = Media::getActive()
                ->where(['media_type' => Media::TYPE_IMAGE])
                ->orderBy('RAND()')
                ->one();

            // Teszt téma és tartalom generálása
            $contentData = $this->generateTestContent();
            
            // Új bejegyzés létrehozása
            $post = new Post();
            $post->title = $contentData['title'];
            $post->slug = $this->generateSlug($contentData['title']);
            $post->content = $contentData['content'];
            $post->excerpt = $contentData['excerpt'];
            $post->status = Post::STATUS_DRAFT;
            $post->visibility = Post::VISIBILITY_PUBLIC;
            $post->comment_status = Post::COMMENT_ENABLED;
            $post->author_id = Yii::$app->user->id;
            $post->seo_title = $contentData['title'];
            $post->seo_description = $contentData['excerpt'];
            $post->seo_keywords = $contentData['keywords'];
            $post->seo_robots = 'index,follow';
            
            // Kép hozzárendelése ha van
            if ($randomMedia) {
                $post->featured_image_id = $randomMedia->id;
            }
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($post->save()) {
                    // Kategóriák létrehozása és hozzárendelése a témához kapcsolódóan
                    if (isset($contentData['categories']) && !empty($contentData['categories'])) {
                        $this->createAndAssignCategories($post, $contentData['categories']);
                    } else {
                        // Fallback: véletlenszerű kategóriák hozzárendelése
                        $this->assignRandomCategories($post);
                    }
                    
                    // Címkék létrehozása és hozzárendelése keywords alapján
                    if (isset($contentData['keywords']) && !empty($contentData['keywords'])) {
                        $this->createAndAssignTagsFromKeywords($post, $contentData['keywords']);
                    } else {
                        // Fallback: véletlenszerű címkék hozzárendelése
                        $this->assignRandomTags($post);
                    }
                    
                    $transaction->commit();
                    
                    return [
                        'success' => true,
                        'message' => 'Teszt bejegyzés sikeresen létrehozva!',
                        'data' => [
                            'id' => $post->id,
                            'title' => $post->title,
                            'slug' => $post->slug
                        ]
                    ];
                } else {
                    $transaction->rollBack();
                    return [
                        'success' => false,
                        'message' => 'Hiba a bejegyzés mentése során: ' . implode(', ', $post->getFirstErrors())
                    ];
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
            
        } catch (\Exception $e) {
            Yii::error('Teszt bejegyzés generálási hiba: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Hiba történt a teszt bejegyzés generálása során.'
            ];
        }
    }

    /**
     * Teszt tartalom generálása építészeti/mérnöki témákban
     * @return array
     */
    private function generateTestContent()
    {
        // 50 építészeti/mérnöki téma kozter.com stílusában
        $topics = [
            [
                'title' => 'Modern lakóház tervezés energiahatékony megoldásokkal',
                'content' => 'Az energiahatékony lakóházak tervezése napjainkban kulcsfontosságú szerepet játszik a fenntartható építészetben. A megfelelő hőszigetelés, az optimális tájolás és a megújuló energiaforrások integrálása jelentősen csökkenthetik az épület energiafogyasztását. A modern építészetben egyre nagyobb hangsúlyt kapnak a környezetkímélő technológiák.',
                'excerpt' => 'Energiahatékony lakóházak tervezésének alapelvei és modern megoldásai.',
                'keywords' => 'energiahatékony építészet, lakóház tervezés, hőszigetelés',
                'categories' => ['Energiahatékony építészet', 'Lakóházak', 'Fenntarthatóság']
            ],
            [
                'title' => 'Tartószerkezeti méretezés acélszerkezetek esetében',
                'content' => 'Az acélszerkezetek méretezése során figyelembe kell venni a teherbírást, a stabilitást és a használhatóságot. A modern számítási módszerek és a fejlett anyagok lehetővé teszik gazdaságos és biztonságos szerkezetek tervezését. Az acélszerkezetek előnyei közé tartozik a gyors kivitelezés és a nagy fesztávolságok áthidalásának lehetősége.',
                'excerpt' => 'Acélszerkezetek méretezésének szabályai és gyakorlati megoldásai.',
                'keywords' => 'acélszerkezet, tartószerkezet, méretezés, statika',
                'categories' => ['Tartószerkezetek', 'Acélszerkezetek', 'Méretezés']
            ],
            [
                'title' => 'Okos otthon rendszerek integrálása új építésű ingatlanokban',
                'content' => 'A smart home technológiák egyre inkább részévé válnak a modern otthonoknak. A megfelelő tervezés és kivitelezés biztosítja a különböző rendszerek zökkenőmentes működését és a lakók komfortját. Az okos otthon rendszerek magukban foglalják a világítás-, fűtés-, biztonsági és szórakoztató rendszerek automatizálását.',
                'excerpt' => 'Smart home technológiák beépítése és integrálása új lakóépületekben.',
                'keywords' => 'okos otthon, smart home, automatizálás, tervezés',
                'categories' => ['Smart Home', 'Lakóépületek', 'Automatizálás']
            ],
            [
                'title' => 'Geotechnikai vizsgálatok fontossága építési projekteknél',
                'content' => 'A talajmechanikai vizsgálatok elengedhetetlenek minden nagyobb építési projekt előtt. A megfelelő talajvizsgálat segít meghatározni az optimális alapozási módot és elkerülni a későbbi problémákat. A geotechnikai szakértelem nélkülözhetetlen a biztonságos és gazdaságos építéshez.',
                'excerpt' => 'Talajmechanikai vizsgálatok szerepe és módszerei az építészetben.',
                'keywords' => 'geotechnika, talajvizsgálat, alapozás, építési projekt',
                'categories' => ['Geotechnika', 'Talajvizsgálat', 'Építési projektek']
            ],
            [
                'title' => 'Zöldtető rendszerek tervezése és kivitelezése',
                'content' => 'A zöldtetők nemcsak esztétikailag vonzóak, hanem környezetvédelmi és energetikai előnyökkel is rendelkeznek. A megfelelő tervezés biztosítja a hosszú távú működést és a karbantartási igények minimalizálását. A zöldtetők hozzájárulnak a városi hősziget-hatás csökkentéséhez és javítják a levegő minőségét.',
                'excerpt' => 'Zöldtetők előnyei, tervezési szempontjai és kivitelezési módjai.',
                'keywords' => 'zöldtető, fenntartható építészet, növényzet, tetőkert',
                'categories' => ['Zöldtetők', 'Fenntarthatóság', 'Környezetvédelem']
            ],
            [
                'title' => 'Vasbeton szerkezetek korrózióvédelme',
                'content' => 'A vasbeton konstrukciók tartósságát jelentősen befolyásolja a betonacél korrózióvédelme. A megfelelő tervezés és anyagválasztás kulcsfontosságú a hosszú élettartam biztosításához. A modern korrózióvédelmi technológiák alkalmazása növeli a szerkezetek élettartamát és csökkenti a karbantartási költségeket.',
                'excerpt' => 'Betonacél védelmének módszerei és a vasbeton szerkezetek tartóssága.',
                'keywords' => 'vasbeton, korrózióvédelem, betonacél, tartósság',
                'categories' => ['Vasbeton szerkezetek', 'Korrózióvédelem', 'Tartószerkezetek']
            ],
            [
                'title' => 'Akadálymentes tervezés alapelvei közintézményekben',
                'content' => 'Az univerzális tervezés biztosítja, hogy minden ember számára használható és megközelíthető tereket hozzunk létre. Ez különösen fontos közintézmények és középületek esetében. Az akadálymentes környezet kialakítása nemcsak jogi kötelezettség, hanem társadalmi felelősség is.',
                'excerpt' => 'Akadálymentes környezet kialakításának szabályai és módszerei.',
                'keywords' => 'akadálymentesítés, univerzális tervezés, közintézmény',
                'categories' => ['Akadálymentesítés', 'Közintézmények', 'Univerzális tervezés']
            ],
            [
                'title' => 'Építési engedélyezési folyamat 2024-ben',
                'content' => 'Az építési engedélyezési eljárás szabályai folyamatosan változnak. Fontos naprakésznek lenni a legújabb előírásokkal és dokumentációs követelményekkel. A digitalizáció jelentősen felgyorsította az engedélyezési folyamatokat és javította azok átláthatóságát.',
                'excerpt' => 'Építési engedélyezés aktuális szabályai és dokumentációs követelményei.',
                'keywords' => 'építési engedély, engedélyezés, építéshatóság, dokumentáció',
                'categories' => ['Építési engedély', 'Engedélyezés', 'Jogi háttér']
            ],
            [
                'title' => 'Passzívház technológia és energetikai tanúsítás',
                'content' => 'A passzívházak extrém alacsony energiafogyasztásukkal példamutatóak a fenntartható építészetben. Az energetikai tanúsítás igazolja a tervezett és megvalósított teljesítményt. A passzívház szabvány alkalmazása hosszú távon jelentős megtakarítást eredményez az üzemeltetési költségekben.',
                'excerpt' => 'Passzívház építés alapjai és az energetikai tanúsítás folyamata.',
                'keywords' => 'passzívház, energetikai tanúsítvány, energiahatékonyság',
                'categories' => ['Passzívház', 'Energetikai tanúsítás', 'Energiahatékonyság']
            ],
            [
                'title' => 'Épületgépészeti rendszerek optimalizálása',
                'content' => 'A modern épületgépészeti rendszerek tervezése komplex feladat, amely magában foglalja a fűtést, szellőzést, klímatechnikát és a víz-szennyvíz rendszereket. Az integrált megközelítés biztosítja a rendszerek hatékony működését és az energiafogyasztás minimalizálását.',
                'excerpt' => 'Épületgépészeti rendszerek integrált tervezése és optimalizálása.',
                'keywords' => 'épületgépészet, HVAC, klímatechnika, rendszeroptimalizálás'
            ],
            [
                'title' => 'BIM technológia alkalmazása az építőiparban',
                'content' => 'A Building Information Modeling (BIM) forradalmasította az építési projektek tervezését és kivitelezését. A háromdimenziós modellek lehetővé teszik a jobb koordinációt és a hibák korai felismerését. A BIM alkalmazása javítja a projektek hatékonyságát és csökkenti a költségeket.',
                'excerpt' => 'BIM technológia előnyei és alkalmazási lehetőségei az építészetben.',
                'keywords' => 'BIM, Building Information Modeling, 3D tervezés, digitalizáció'
            ],
            [
                'title' => 'Fa szerkezetek modern alkalmazása nagyépítészetben',
                'content' => 'A fa mint építőanyag reneszánszát éli a modern építészetben. Az előregyártott fa szerkezetek lehetővé teszik nagyobb épületek gazdaságos és környezetbarát építését. A CLT (Cross Laminated Timber) technológia új perspektívákat nyit a fa építészetben.',
                'excerpt' => 'Modern fa szerkezetek alkalmazása és előnyei a nagyépítészetben.',
                'keywords' => 'fa szerkezet, CLT, környezetbarát építés, előregyártás'
            ],
            [
                'title' => 'Hőszivattyús rendszerek tervezése lakóépületekben',
                'content' => 'A hőszivattyús fűtési rendszerek egyre népszerűbbek az alacsony üzemeltetési költségeik miatt. A megfelelő rendszertervezés biztosítja a hatékony működést és a komfortos beltéri környezetet. A hőszivattyúk kiváló alternatívát jelentenek a hagyományos fűtési rendszerekkel szemben.',
                'excerpt' => 'Hőszivattyús fűtési rendszerek tervezése és optimalizálása.',
                'keywords' => 'hőszivattyú, fűtési rendszer, energiahatékonyság, megújuló energia'
            ],
            [
                'title' => 'Napelemes rendszerek integrálása épület homlokzatokba',
                'content' => 'A fotovoltaikus rendszerek homlokzati integrációja egyre gyakoribb a modern építészetben. A BIPV (Building Integrated Photovoltaics) megoldások esztétikus és funkccionális energiatermelést tesznek lehetővé. A napelemes homlokzatok hozzájárulnak az épületek energiafüggetlenségéhez.',
                'excerpt' => 'Fotovoltaikus rendszerek építészeti integrálásának lehetőségei.',
                'keywords' => 'napelem, BIPV, homlokzat, megújuló energia, fotovoltaikus'
            ],
            [
                'title' => 'Akusztikai tervezés irodaépületekben',
                'content' => 'A megfelelő akusztikai környezet kialakítása kulcsfontosságú az irodaépületek tervezésében. A zaj- és rezgésvédelem, valamint a beszédérthetőség biztosítása javítja a munkavállalók komfortját és produktivitását. A modern akusztikai megoldások esztétikusak és hatékonyak.',
                'excerpt' => 'Akusztikai tervezés alapelvei és megoldásai irodaépületekben.',
                'keywords' => 'akusztika, zajvédelem, irodaépület, beszédérthetőség'
            ],
            [
                'title' => 'Világítástervezés és LED technológia alkalmazása',
                'content' => 'A LED technológia elterjedése forradalmasította a világítástervezést. Az energiahatékony és hosszú élettartamú LED-ek új lehetőségeket nyitottak a kreatív világítási megoldásokban. A okos világítási rendszerek további energiamegtakarítást és komfortot biztosítanak.',
                'excerpt' => 'Modern világítástervezés és LED technológia alkalmazásának előnyei.',
                'keywords' => 'LED világítás, világítástervezés, energiahatékonyság, okos rendszerek'
            ],
            [
                'title' => 'Csapadékvíz-gazdálkodás városi környezetben',
                'content' => 'A városi csapadékvíz-gazdálkodás egyre fontosabb szerepet játszik a fenntartható városfejlesztésben. A természetes megoldások, mint a zöldtetők és az esővízgyűjtő rendszerek, hozzájárulnak az árvízvédelem javításához és a vízkészletek megőrzéséhez.',
                'excerpt' => 'Fenntartható csapadékvíz-gazdálkodási megoldások városi környezetben.',
                'keywords' => 'csapadékvíz, városi vízelvezetés, fenntarthatóság, zöldinfrastruktúra'
            ],
            [
                'title' => 'Utólagos épületfelújítás energetikai szempontból',
                'content' => 'A meglévő épületek energetikai felújítása kulcsfontosságú a klímacélok elérésében. A komplex felújítási programok jelentős energiamegtakarítást eredményezhetnek. A megfelelő tervezés és kivitelezés biztosítja a befektetés megtérülését.',
                'excerpt' => 'Épületenergetikai felújítás tervezése és megvalósítása.',
                'keywords' => 'épületfelújítás, energetikai korszerűsítés, hőszigetelés, megtérülés'
            ],
            [
                'title' => 'Tűzvédelmi tervezés többszintes épületekben',
                'content' => 'A tűzvédelmi tervezés alapvető követelmény minden építési projektnél. A megfelelő menekülési útvonalak, tűzoltó rendszerek és építőanyagok kiválasztása életeket menthet. A modern tűzvédelmi technológiák hatékony védelmet nyújtanak minimális építészeti kompromisszumokkal.',
                'excerpt' => 'Tűzvédelmi követelmények és megoldások nagyobb épületekben.',
                'keywords' => 'tűzvédelem, menekülési útvonal, tűzoltó rendszerek, biztonság'
            ],
            [
                'title' => 'Közlekedési létesítmények akusztikai védelme',
                'content' => 'A közlekedési létesítmények zajvédelme kiemelt fontosságú a környező területek lakhatóságának megőrzése érdekében. A modern zajvédő megoldások hatékonyak és esztétikusak. A tervezés során figyelembe kell venni a helyi adottságokat és a környezeti hatásokat.',
                'excerpt' => 'Zajvédelmi megoldások közlekedési létesítmények tervezésénél.',
                'keywords' => 'zajvédelem, közlekedés, akusztika, környezetvédelem'
            ],
            [
                'title' => 'Prefabrikált beton elemek alkalmazása',
                'content' => 'A prefabrikált beton elemek használata felgyorsítja az építési folyamatokat és javítja a minőséget. A gyári körülmények között készített elemek egyenletes minőséget biztosítanak. A modern prefabrikációs technológiák lehetővé teszik komplex geometriájú elemek gazdaságos előállítását.',
                'excerpt' => 'Prefabrikált beton elemek előnyei és alkalmazási területei.',
                'keywords' => 'prefabrikált beton, előregyártás, minőségbiztosítás, gyorsítás'
            ],
            [
                'title' => 'Vízszigetelési rendszerek modern épületekben',
                'content' => 'A megfelelő vízszigetelés alapvető fontosságú az épületek tartósságához. A modern vízszigetelő anyagok és technológiák hosszú távú védelmet nyújtanak. A tervezés során figyelembe kell venni a klimatikus viszonyokat és a szerkezeti adottságokat.',
                'excerpt' => 'Vízszigetelési technológiák és anyagok alkalmazása az építészetben.',
                'keywords' => 'vízszigetelés, szigetelő anyagok, nedvességvédelem, tartósság'
            ],
            [
                'title' => 'Épületautomatizálási rendszerek tervezése',
                'content' => 'Az épületautomatizálási rendszerek integrált irányítást biztosítanak a különböző épületgépészeti rendszerek felett. A BMS (Building Management System) optimalizálja az energiafogyasztást és javítja a komfortot. A modern rendszerek távoli monitorozást és irányítást is lehetővé tesznek.',
                'excerpt' => 'Épületautomatizálás szerepe a modern építészetben.',
                'keywords' => 'épületautomatizálás, BMS, energiaoptimalizálás, távirányítás'
            ],
            [
                'title' => 'Közterületi térburkolatok tervezése és kivitelezése',
                'content' => 'A közterületi térburkolatok tervezése során figyelembe kell venni a funkcionális és esztétikai szempontokat egyaránt. A megfelelő anyagválasztás és kivitelezés biztosítja a hosszú élettartamot és az alacsony karbantartási igényt. A modern burkolóanyagok környezetbarát megoldásokat is kínálnak.',
                'excerpt' => 'Közterületi térburkolatok tervezési szempontjai és anyagai.',
                'keywords' => 'térburkolat, közterület, járófelület, anyagválasztás'
            ],
            [
                'title' => 'Lift és függőleges közlekedési rendszerek',
                'content' => 'A lift és függőleges közlekedési rendszerek tervezése során figyelembe kell venni a forgalmi igényeket és a biztonsági előírásokat. A modern liftrendszerek energiahatékonyak és intelligens irányítással rendelkeznek. Az akadálymentes közlekedés biztosítása alapvető követelmény.',
                'excerpt' => 'Függőleges közlekedési rendszerek tervezése és optimalizálása.',
                'keywords' => 'lift, függőleges közlekedés, energiahatékonyság, akadálymentesség'
            ],
            [
                'title' => 'Csarnok típusú épületek tartószerkezeti megoldásai',
                'content' => 'A nagy fesztávolságú csarnok épületek tartószerkezeti tervezése speciális szakértelmet igényel. A megfelelő szerkezeti rendszer kiválasztása befolyásolja az építési költségeket és a funkcionális rugalmasságot. Az acél és fa szerkezetek egyaránt alkalmasak nagy terek átfogására.',
                'excerpt' => 'Nagy fesztávolságú csarnokok tartószerkezeti megoldásai.',
                'keywords' => 'csarnok, nagy fesztávolság, tartószerkezet, acélszerkezet'
            ],
            [
                'title' => 'Magasház építés speciális kihívásai',
                'content' => 'A magasház építés különleges tervezési és kivitelezési kihívásokat rejt magában. A szélterhelés, a függőleges közlekedés és a tűzbiztonsági követelmények speciális megoldásokat igényelnek. A modern magasházak energiahatékony és fenntartható működésre törekednek.',
                'excerpt' => 'Magasház építés tervezési és kivitelezési szempontjai.',
                'keywords' => 'magasház, szélterhelés, függőleges közlekedés, tűzbiztonság'
            ],
            [
                'title' => 'Hidak és műtárgyak tervezési alapjai',
                'content' => 'A hidak és műtárgyak tervezése során figyelembe kell venni a geológiai adottságokat, a forgalmi terheléseket és a környezeti hatásokat. A modern híd építési technológiák lehetővé teszik nagy fesztávolságú és esztétikus szerkezetek megvalósítását.',
                'excerpt' => 'Hidak és műtárgyak tervezésének műszaki alapjai.',
                'keywords' => 'híd, műtárgy, szerkezettervezés, geológia'
            ],
            [
                'title' => 'Ipari épületek technológiai tervezése',
                'content' => 'Az ipari épületek tervezése során a technológiai folyamatok határozzák meg az építészeti megoldásokat. A megfelelő alapozás, a teherbírás és a különleges környezeti követelmények figyelembevétele kulcsfontosságú. A rugalmasság és a bővíthetőség is fontos szempont.',
                'excerpt' => 'Ipari létesítmények tervezési szempontjai és követelményei.',
                'keywords' => 'ipari épület, technológiai tervezés, teherbírás, rugalmasság'
            ],
            [
                'title' => 'Kulturális létesítmények akusztikai tervezése',
                'content' => 'A kulturális épületek, különösen a koncerttermek és színházak akusztikai tervezése rendkívül speciális szaktudást igényel. A megfelelő hangzás és akusztikai környezet kialakítása meghatározza az épület funkcionalitását. A modern akusztikai szimulációs eszközök segítik a tervezést.',
                'excerpt' => 'Koncerttermek és színházak akusztikai tervezésének alapjai.',
                'keywords' => 'akusztika, koncertterem, színház, hangzás'
            ],
            [
                'title' => 'Sportlétesítmények tervezési szempontjai',
                'content' => 'A sportlétesítmények tervezése során figyelembe kell venni a specifikus sport követelményeit, a nézői komfortot és a biztonsági előírásokat. A megfelelő világítás, szellőzés és akusztika biztosítása alapvető fontosságú. A multifunkcionális használat növeli a létesítmény gazdaságosságát.',
                'excerpt' => 'Sportlétesítmények komplex tervezési követelményei.',
                'keywords' => 'sportlétesítmény, világítás, szellőzés, multifunkcionális'
            ],
            [
                'title' => 'Kórház és egészségügyi épületek tervezése',
                'content' => 'Az egészségügyi létesítmények tervezése során a higiéniai követelmények, a funkcionális elrendezés és a betegbiztonság a legfontosabb szempontok. A megfelelő szellőzési rendszerek és a tiszta-piszkos útvonalak szétválasztása elengedhetetlen. A rugalmas alaprajzi megoldások lehetővé teszik a jövőbeni technológiai változásokhoz való alkalmazkodást.',
                'excerpt' => 'Egészségügyi létesítmények speciális tervezési követelményei.',
                'keywords' => 'kórház, egészségügy, higiénia, szellőzés'
            ],
            [
                'title' => 'Iskola és oktatási épületek modernizációja',
                'content' => 'Az oktatási épületek modernizációja során figyelembe kell venni a pedagógiai módszerek változásait és a technológiai fejlődést. A rugalmas tantermi megoldások, a természetes világítás és a megfelelő akusztikai környezet javítja a tanulási hatékonyságot. Az energetikai korszerűsítés csökkenti az üzemeltetési költségeket.',
                'excerpt' => 'Oktatási épületek korszerűsítésének tervezési szempontjai.',
                'keywords' => 'iskola, oktatás, modernizáció, energetikai korszerűsítés'
            ],
            [
                'title' => 'Parkolóházak és mélygarázsok tervezése',
                'content' => 'A parkolóházak és mélygarázsok tervezése során a forgalmi áramlás, a szellőzés és a tűzbiztonság a legfontosabb szempontok. A megfelelő magasságok és rámpa kialakítások biztosítják a kényelmes használatot. A modern parkolóházak gyakran multifunkcionális megoldásokat is tartalmaznak.',
                'excerpt' => 'Parkolóházak és mélygarázsok műszaki tervezési követelményei.',
                'keywords' => 'parkolóház, mélygarázs, szellőzés, forgalmi áramlás'
            ],
            [
                'title' => 'Szálloda és vendéglátó épületek tervezése',
                'content' => 'A szálloda és vendéglátó épületek tervezése során a vendégkomfort és a funkcionális hatékonyság egyensúlyának megteremtése a cél. A megfelelő akusztikai környezet, a világítás és a klímatechnika biztosítja a magas színvonalú szolgáltatást. A fenntarthatósági szempontok egyre fontosabbak a vendégek számára.',
                'excerpt' => 'Szálloda és vendéglátó épületek tervezési alapelvei.',
                'keywords' => 'szálloda, vendéglátás, vendégkomfort, fenntarthatóság'
            ],
            [
                'title' => 'Kereskedelmi központok és áruházak építészete',
                'content' => 'A kereskedelmi létesítmények tervezése során a vásárlói élmény és a funkcionalitás egyaránt fontos. A megfelelő forgalmi útvonalak, a természetes világítás és a rugalmas alaprajzi megoldások növelik a kereskedelmi hatékonyságot. A fenntarthatósági szempontok és az energiahatékonyság egyre nagyobb szerepet kapnak.',
                'excerpt' => 'Kereskedelmi létesítmények tervezési és működési szempontjai.',
                'keywords' => 'kereskedelmi központ, áruház, vásárlói élmény, energiahatékonyság'
            ],
            [
                'title' => 'Logisztikai központok és raktárak tervezése',
                'content' => 'A logisztikai létesítmények tervezése során a hatékony anyagmozgatás és a rugalmas tárolási lehetőségek a legfontosabb szempontok. A megfelelő magasságok, osztatlanság és a modern automatizálási rendszerek integrálása növeli a működési hatékonyságot. Az energiahatékony megoldások csökkentik az üzemeltetési költségeket.',
                'excerpt' => 'Logisztikai létesítmények tervezési és technológiai követelményei.',
                'keywords' => 'logisztika, raktár, anyagmozgatás, automatizálás'
            ],
            [
                'title' => 'Természeti katasztrófák elleni építészeti védekezés',
                'content' => 'A klímaváltozás következményeként egyre fontosabb a természeti katasztrófák elleni építészeti védekezés. A földrengésálló tervezés, az árvízvédelem és a szélvihar elleni védelem különleges szerkezeti megoldásokat igényel. A reziliens építészet hosszú távon védi az épületeket és használóikat.',
                'excerpt' => 'Építészeti megoldások természeti katasztrófák elleni védelemben.',
                'keywords' => 'katasztrófavédelem, földrengésállóság, árvízvédelem, reziliens építészet'
            ],
            [
                'title' => 'Városrehabilitáció és brownfield fejlesztések',
                'content' => 'A városi barnamezős területek rehabilitációja során figyelembe kell venni a talajszennyezettséget, a meglévő infrastruktúrát és a társadalmi igényeket. A fenntartható városfejlesztés keretében a brownfield projektek hozzájárulnak a városok megújulásához. Az integrált tervezési megközelítés biztosítja a sikeres megvalósítást.',
                'excerpt' => 'Barnamezős területek rehabilitációjának tervezési szempontjai.',
                'keywords' => 'városrehabilitáció, brownfield, talajszennyezettség, fenntartható fejlesztés'
            ],
            [
                'title' => 'Nyári termikus komfort biztosítása épületekben',
                'content' => 'A klímaváltozás miatt egyre fontosabb a nyári túlmelegedés elleni védelem. A passzív hűtési megoldások, az árnyékolási rendszerek és a természetes szellőzés csökkentik az energiafogyasztást. A megfelelő tájolás és anyagválasztás javítja a termikus komfortot.',
                'excerpt' => 'Passzív hűtési megoldások és nyári komfort biztosítása.',
                'keywords' => 'termikus komfort, passzív hűtés, árnyékolás, természetes szellőzés'
            ],
            [
                'title' => 'Digitális tervezési eszközök az építészetben',
                'content' => 'A digitális tervezési eszközök forradalmasították az építészeti tervezést. A parametrikus tervezés, a virtuális valóság és a mesterséges intelligencia új lehetőségeket nyit az optimalizálásban. A digital twin technológia lehetővé teszi az épületek teljes életciklusának monitorozását.',
                'excerpt' => 'Modern digitális eszközök alkalmazása az építészeti tervezésben.',
                'keywords' => 'digitális tervezés, parametrikus tervezés, AI, digital twin'
            ],
            [
                'title' => 'Fenntartható építőanyagok és körforgásos építészet',
                'content' => 'A körforgásos építészet keretében az építőanyagok újrahasznosítása és életciklus-elemzése egyre fontosabb. A természetes és megújuló anyagok alkalmazása csökkenti a környezeti hatásokat. A moduláris építészet lehetővé teszi az épületelemek újrafelhasználását.',
                'excerpt' => 'Körforgásos építészet és fenntartható anyagok alkalmazása.',
                'keywords' => 'körforgásos építészet, újrahasznosítás, természetes anyagok, moduláris építészet'
            ],
            [
                'title' => 'Épület-egészségügy és wellness építészet',
                'content' => 'Az épületek hatása az emberi egészségre egyre nagyobb figyelmet kap. A megfelelő levegőminőség, a természetes világítás és a zöld környezet javítja a lakók és használók egészségét. A wellness építészet holisztikus megközelítést alkalmaz az épületek tervezésében.',
                'excerpt' => 'Építészeti megoldások az emberi egészség és jóllét támogatására.',
                'keywords' => 'épület-egészségügy, wellness építészet, levegőminőség, biofília'
            ],
            [
                'title' => 'Agrár épületek és vidéki építészet modern megoldásai',
                'content' => 'A modern agrár épületek tervezése során figyelembe kell venni a technológiai fejlődést és a fenntarthatósági szempontokat. A precíziós mezőgazdaság új épülettípusokat és funkciókat igényel. A vidéki építészet megőrzi a hagyományos értékeket, miközben alkalmazza a modern technológiákat.',
                'excerpt' => 'Modern agrár épületek és vidéki építészet fejlesztési irányai.',
                'keywords' => 'agrár épület, vidéki építészet, precíziós mezőgazdaság, hagyomány'
            ],
            [
                'title' => 'Klímaadaptációs stratégiák az építészetben',
                'content' => 'A klímaváltozáshoz való alkalmazkodás új tervezési paradigmákat igényel az építészetben. A szélsőséges időjárási események elleni védelem, a vízhiány kezelése és a hőhullámok elleni védelem különleges megoldásokat kíván. Az adaptív építészet rugalmasan reagál a változó környezeti feltételekre.',
                'excerpt' => 'Építészeti stratégiák a klímaváltozáshoz való alkalmazkodásban.',
                'keywords' => 'klímaadaptáció, szélsőséges időjárás, adaptív építészet, rugalmasság'
            ],
            [
                'title' => 'Építőipari robotika és automatizálás',
                'content' => 'A robotika és automatizálás fokozatosan átalakítja az építőipart. A 3D nyomtatás, a robotos építési technológiák és az automatizált gyártási folyamatok növelik a hatékonyságot és javítják a minőséget. A digitális építési technológiák csökkentik az emberi hibalehetőségeket.',
                'excerpt' => 'Robotika és automatizálás alkalmazása az építőiparban.',
                'keywords' => 'építőipari robotika, 3D nyomtatás, automatizálás, digitális építés'
            ],
            [
                'title' => 'Közösségi terek és társadalmi fenntarthatóság',
                'content' => 'A közösségi terek tervezése során figyelembe kell venni a társadalmi igényeket és a kulturális sokszínűséget. A befogadó és akadálymentes terek erősítik a közösségi kohéziót. A participatív tervezési módszerek bevonják a helyi közösségeket a tervezési folyamatba.',
                'excerpt' => 'Társadalmilag fenntartható közösségi terek tervezése.',
                'keywords' => 'közösségi tér, társadalmi fenntarthatóság, befogadó tervezés, participáció'
            ],
            [
                'title' => 'Energiatárolás és mikrohálózatok épületekben',
                'content' => 'Az épületintegrált energiatárolási rendszerek lehetővé teszik a megújuló energia hatékony hasznosítását. A mikrohálózatok növelik az energiabiztonságot és csökkentik a hálózattól való függőséget. Az okos energiagazdálkodási rendszerek optimalizálják az energiafogyasztást.',
                'excerpt' => 'Energiatárolási megoldások és mikrohálózatok épületekben.',
                'keywords' => 'energiatárolás, mikrohálózat, megújuló energia, okos hálózat'
            ],
            [
                'title' => 'Vízkörforgás és szürkevíz hasznosítás épületekben',
                'content' => 'A vízkörforgásos rendszerek csökkentik a vízfogyasztást és a szennyvízterhelést. A szürkevíz hasznosítása és az esővíz gyűjtése fenntartható vízgazdálkodást tesz lehetővé. A modern vízkezelési technológiák biztonságos újrahasznosítást biztosítanak.',
                'excerpt' => 'Fenntartható vízgazdálkodás és vízkörforgás épületekben.',
                'keywords' => 'vízkörforgás, szürkeví hasznosítás, esővíz gyűjtés, vízkezelés'
            ]
        ];

        // Véletlenszerű téma kiválasztása
        $randomTopic = $topics[array_rand($topics)];
        
        // Ha nincs kategória definiálva, generálunk automatikusan
        if (!isset($randomTopic['categories']) || empty($randomTopic['categories'])) {
            $randomTopic['categories'] = $this->generateCategoriesFromTitle($randomTopic['title']);
        }
        
        return $randomTopic;
    }

    /**
     * Kategóriák generálása cím alapján
     * @param string $title
     * @return array
     */
    private function generateCategoriesFromTitle($title)
    {
        // Alapértelmezett építészeti kategóriák
        $baseCategories = ['Építészet', 'Mérnöki tervezés'];
        
        // Kulcsszó alapú kategória mappings
        $keywordMappings = [
            // Energiahatékonyság
            'energiahatékony' => 'Energiahatékonyság',
            'passzív' => 'Passzívház',
            'fenntartható' => 'Fenntarthatóság',
            'zöld' => 'Környezetvédelem',
            
            // Épülettípusok
            'lakóház' => 'Lakóházak',
            'iroda' => 'Irodaépületek',
            'kórház' => 'Egészségügyi épületek',
            'iskola' => 'Oktatási épületek',
            'szálloda' => 'Vendéglátó épületek',
            'csarnok' => 'Ipari épületek',
            'magasház' => 'Magasházak',
            
            // Szerkezetek
            'acél' => 'Acélszerkezetek',
            'vasbeton' => 'Vasbeton szerkezetek',
            'fa szerkezet' => 'Fa szerkezetek',
            'tartószerkezet' => 'Tartószerkezetek',
            
            // Technológiák
            'bim' => 'BIM technológia',
            'smart' => 'Smart Home',
            'okos' => 'Smart Home',
            'led' => 'LED technológia',
            'napelem' => 'Megújuló energia',
            'hőszivattyú' => 'Hőtechnika',
            
            // Rendszerek
            'gépészet' => 'Épületgépészet',
            'világítás' => 'Világítástervezés',
            'akusztika' => 'Akusztika',
            'tűzvédelem' => 'Tűzvédelem',
            'víz' => 'Vízgazdálkodás',
            
            // Szakmai területek
            'geotechnika' => 'Geotechnika',
            'talaj' => 'Geotechnika',
            'engedély' => 'Engedélyezés',
            'felújítás' => 'Épületfelújítás',
            'tervezés' => 'Tervezés',
            'digitális' => 'Digitalizáció',
            'robotika' => 'Automatizálás'
        ];
        
        $generatedCategories = $baseCategories;
        $title = strtolower($title);
        
        // Kategóriák keresése a címben
        foreach ($keywordMappings as $keyword => $category) {
            if (strpos($title, $keyword) !== false) {
                $generatedCategories[] = $category;
            }
        }
        
        // Egyedi kategóriák visszaadása (max 4)
        $uniqueCategories = array_unique($generatedCategories);
        return array_slice($uniqueCategories, 0, 4);
    }

    /**
     * Slug generálása címből
     * @param string $title
     * @return string
     */
    private function generateSlug($title)
    {
        $slug = strtolower($title);
        
        // Magyar karakterek cseréje
        $replacements = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ö' => 'o', 'ő' => 'o',
            'ú' => 'u', 'ü' => 'u', 'ű' => 'u', 'ä' => 'a', 'ë' => 'e', 'ï' => 'i',
            'ô' => 'o', 'ù' => 'u', 'ÿ' => 'y', 'ç' => 'c', 'ñ' => 'n'
        ];
        
        $slug = str_replace(array_keys($replacements), array_values($replacements), $slug);
        
        // Csak betűk, számok és kötőjel
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        
        // Többszörös kötőjelek eltávolítása
        $slug = preg_replace('/-+/', '-', $slug);
        
        // Eleje és végéről kötőjel eltávolítása
        $slug = trim($slug, '-');
        
        // Egyediség biztosítása
        $originalSlug = $slug;
        $counter = 1;
        while (Post::find()->where(['slug' => $slug])->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Kategóriák létrehozása és hozzárendelése bejegyzéshez
     * @param Post $post
     * @param array $categoryNames
     */
    private function createAndAssignCategories($post, $categoryNames)
    {
        if (empty($categoryNames)) {
            return;
        }
        
        foreach ($categoryNames as $categoryName) {
            // Ellenőrizzük, hogy létezik-e már a kategória
            $category = Category::find()
                ->where(['name' => $categoryName, 'status' => Category::STATUS_ACTIVE])
                ->one();
            
            if (!$category) {
                // Kategória létrehozása, ha még nem létezik
                $category = new Category();
                $category->name = $categoryName;
                $category->status = Category::STATUS_ACTIVE;
                $category->description = 'Automatikusan generált kategória: ' . $categoryName;
                
                if (!$category->save()) {
                    Yii::error('Hiba a kategória létrehozása során: ' . $categoryName . ' - ' . print_r($category->errors, true));
                    continue; // Folytatjuk a következő kategóriával
                }
            }
            
            // Ellenőrizzük, hogy nincs-e már hozzárendelve
            $existingAssignment = PostCategory::find()
                ->where(['post_id' => $post->id, 'category_id' => $category->id])
                ->exists();
            
            if (!$existingAssignment) {
                // Kategória hozzárendelése a bejegyzéshez
                $postCategory = new PostCategory();
                $postCategory->post_id = $post->id;
                $postCategory->category_id = $category->id;
                
                if (!$postCategory->save()) {
                    Yii::error('Hiba a kategória hozzárendelése során: ' . print_r($postCategory->errors, true));
                }
            }
        }
    }

    /**
     * Véletlenszerű kategóriák hozzárendelése (fallback funkció)
     * @param Post $post
     */
    private function assignRandomCategories($post)
    {
        $categories = Category::getActive()->all();
        if (!empty($categories)) {
            $categoryCount = rand(1, min(2, count($categories)));
            $selectedCategories = array_rand(array_column($categories, 'id'), $categoryCount);
            
            if (!is_array($selectedCategories)) {
                $selectedCategories = [$selectedCategories];
            }
            
            foreach ($selectedCategories as $index) {
                $postCategory = new PostCategory();
                $postCategory->post_id = $post->id;
                $postCategory->category_id = $categories[$index]->id;
                $postCategory->save();
            }
        }
    }

    /**
     * Címkék létrehozása és hozzárendelése keywords alapján
     * @param Post $post
     * @param string $keywords
     */
    private function createAndAssignTagsFromKeywords($post, $keywords)
    {
        if (empty($keywords)) {
            return;
        }
        
        // Keywords szétbontása vesszővel és trimmelés
        $keywordArray = array_map('trim', explode(',', $keywords));
        
        // Alapértelmezett színek a címkékhez
        $defaultColors = Tag::getDefaultColors();
        
        foreach ($keywordArray as $keyword) {
            if (empty($keyword)) {
                continue;
            }
            
            // Ellenőrizzük, hogy létezik-e már a címke
            $tag = Tag::find()
                ->where(['name' => $keyword, 'status' => Tag::STATUS_ACTIVE])
                ->one();
            
            if (!$tag) {
                // Címke létrehozása, ha még nem létezik
                $tag = new Tag();
                $tag->name = $keyword;
                $tag->status = Tag::STATUS_ACTIVE;
                $tag->description = 'Automatikusan generált címke: ' . $keyword;
                
                // Véletlenszerű szín hozzárendelése
                $tag->color = $defaultColors[array_rand($defaultColors)];
                
                if (!$tag->save()) {
                    Yii::error('Hiba a címke létrehozása során: ' . $keyword . ' - ' . print_r($tag->errors, true));
                    continue; // Folytatjuk a következő címkével
                }
            }
            
            // Ellenőrizzük, hogy nincs-e már hozzárendelve
            $existingAssignment = PostTag::find()
                ->where(['post_id' => $post->id, 'tag_id' => $tag->id])
                ->exists();
            
            if (!$existingAssignment) {
                // Címke hozzárendelése a bejegyzéshez
                $postTag = new PostTag();
                $postTag->post_id = $post->id;
                $postTag->tag_id = $tag->id;
                
                if (!$postTag->save()) {
                    Yii::error('Hiba a címke hozzárendelése során: ' . print_r($postTag->errors, true));
                }
            }
        }
    }

    /**
     * Véletlenszerű címkék hozzárendelése (fallback funkció)
     * @param Post $post
     */
    private function assignRandomTags($post)
    {
        $tags = Tag::getActive()->all();
        if (!empty($tags)) {
            $tagCount = rand(2, min(4, count($tags)));
            $selectedTags = array_rand(array_column($tags, 'id'), $tagCount);
            
            if (!is_array($selectedTags)) {
                $selectedTags = [$selectedTags];
            }
            
            foreach ($selectedTags as $index) {
                $postTag = new PostTag();
                $postTag->post_id = $post->id;
                $postTag->tag_id = $tags[$index]->id;
                $postTag->save();
            }
        }
    }
}
