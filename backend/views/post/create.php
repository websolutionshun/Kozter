<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Post;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $categories common\models\Category[] */
/* @var $tags common\models\Tag[] */
/* @var $mediaFiles common\models\Media[] */

$this->title = 'Új bejegyzés';
$this->params['breadcrumbs'][] = ['label' => 'Bejegyzések', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// CKEditor CDN
$this->registerCssFile('https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.css');
$this->registerJsFile('https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js', ['position' => \yii\web\View::POS_HEAD]);

// Custom JavaScript
$this->registerJs('
// CKEditor inicializálása
ClassicEditor
    .create(document.querySelector("#post-content"), {
        toolbar: [
            "heading", "|",
            "bold", "italic", "underline", "strikethrough", "|",
            "link", "bulletedList", "numberedList", "|",
            "outdent", "indent", "|",
            "imageUpload", "blockQuote", "insertTable", "|",
            "undo", "redo", "|",
            "alignment", "fontFamily", "fontSize", "fontColor", "fontBackgroundColor", "|",
            "horizontalLine", "specialCharacters", "sourceEditing"
        ],
        language: "hu",
        image: {
            toolbar: [
                "imageTextAlternative", "imageCaption", "|",
                "imageStyle:inline", "imageStyle:block", "imageStyle:side", "|",
                "toggleImageCaption", "imageResize"
            ]
        },
        table: {
            contentToolbar: [
                "tableColumn", "tableRow", "mergeTableCells",
                "tableCellProperties", "tableProperties"
            ]
        },
        licenseKey: "",
    })
    .then(editor => {
        window.editor = editor;
    })
    .catch(error => {
        console.error("CKEditor inicializálási hiba:", error);
    });

// Auto-generate slug from title
$("#post-title").on("keyup", function() {
    var title = $(this).val();
    var slug = title.toLowerCase()
        .replace(/[áàâä]/g, "a")
        .replace(/[éèêë]/g, "e")
        .replace(/[íìîï]/g, "i")
        .replace(/[óòôö]/g, "o")
        .replace(/[úùûü]/g, "u")
        .replace(/[ő]/g, "o")
        .replace(/[ű]/g, "u")
        .replace(/[ç]/g, "c")
        .replace(/[ñ]/g, "n")
        .replace(/[^a-z0-9]/g, "-")
        .replace(/-+/g, "-")
        .replace(/^-|-$/g, "");
    $("#post-slug").val(slug);
});

// Publikálás gomb funkció
$("#publish-btn").click(function() {
    // Form validáció ellenőrzése
    var title = $("#post-title").val();
    if (!title.trim()) {
        alert("A cím megadása kötelező!");
        return false;
    }
    
    // CKEditor tartalom szinkronizálása
    if (window.editor) {
        $("#post-content").val(window.editor.getData());
    }
    
    $("#post-status").val(1); // STATUS_PUBLISHED
    $("#post-form").submit();
});

// Vázlat mentés gomb funkció
$("#draft-btn").click(function() {
    // CKEditor tartalom szinkronizálása
    if (window.editor) {
        $("#post-content").val(window.editor.getData());
    }
    $("#post-status").val(0); // STATUS_DRAFT
    $("#post-form").submit();
});

// Előnézet gomb funkció
$("#preview-btn").click(function() {
    // Itt később implementálhatjuk az előnézet funkciót
    alert("Előnézet funkció hamarosan!");
});

// Média kiválasztó
$(".media-item").click(function() {
    $(".media-item").removeClass("selected");
    $(this).addClass("selected");
    $("#post-featured_image_id").val($(this).data("id"));
});

// Láthatóság beállítás
$("#post-visibility").change(function() {
    if ($(this).val() == 2) { // VISIBILITY_PASSWORD
        $("#password-field").show();
    } else {
        $("#password-field").hide();
        $("#post-password").val("");
    }
});
', yii\web\View::POS_READY);

// CSS
$this->registerCss("
.media-selector {
    max-height: 300px;
    overflow-y: auto;
}
.media-item {
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 4px;
    transition: border-color 0.2s;
}
.media-item:hover {
    border-color: #0084ff;
}
.media-item.selected {
    border-color: #0084ff;
    background-color: #f0f8ff;
}
.media-item img {
    width: 100%;
    height: 80px;
    object-fit: cover;
}
");
?>

<div class="post-create">
    <?php $form = ActiveForm::begin(['id' => 'post-form']); ?>
    
    <div class="row">
        <!-- Fő tartalom terület -->
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Új bejegyzés hozzáadása</h3>
                </div>
                <div class="card-body">
                    <!-- Cím -->
                    <div class="mb-3">
                        <?= $form->field($model, 'title')->textInput([
                            'maxlength' => true, 
                            'class' => 'form-control form-control-lg',
                            'id' => 'post-title',
                            'placeholder' => 'Add meg a bejegyzés címét'
                        ])->label(false) ?>
                    </div>

                    <!-- Slug -->
                    <div class="mb-3">
                        <label class="form-label">Permalink</label>
                        <div class="input-group">
                            <span class="input-group-text"><?= Yii::$app->params['frontendUrl'] ?? 'http://kozter.test' ?>/</span>
                            <?= $form->field($model, 'slug')->textInput([
                                'maxlength' => true,
                                'id' => 'post-slug'
                            ])->label(false) ?>
                        </div>
                    </div>

                    <!-- Tartalom szerkesztő -->
                    <div class="mb-3">
                        <label class="form-label">Tartalom</label>
                        <?= $form->field($model, 'content')->textarea([
                            'rows' => 15,
                            'id' => 'post-content'
                        ])->label(false) ?>
                    </div>

                    <!-- Kivonat -->
                    <div class="mb-3">
                        <?= $form->field($model, 'excerpt')->textarea([
                            'rows' => 3,
                            'placeholder' => 'Rövid kivonat a bejegyzésről...'
                        ]) ?>
                    </div>

                    <!-- SEO beállítások -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">SEO beállítások</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'seo_title')->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'SEO cím (ha üresen hagyod, a bejegyzés címét használjuk)'
                                    ]) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'seo_canonical_url')->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'Canonical URL (opcionális)'
                                    ]) ?>
                                </div>
                            </div>
                            
                            <?= $form->field($model, 'seo_description')->textarea([
                                'rows' => 3,
                                'placeholder' => 'SEO meta leírás...'
                            ]) ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'seo_keywords')->textInput([
                                        'maxlength' => true,
                                        'placeholder' => 'kulcsszó1, kulcsszó2, kulcsszó3'
                                    ]) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'seo_robots')->dropDownList([
                                        'index,follow' => 'Index, Follow',
                                        'noindex,follow' => 'No Index, Follow',
                                        'index,nofollow' => 'Index, No Follow',
                                        'noindex,nofollow' => 'No Index, No Follow',
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Oldalsáv -->
        <div class="col-md-2">
            <!-- Közzététel -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">Közzététel</h4>
                </div>
                <div class="card-body">
                    <!-- Állapot -->
                    <div class="mb-3">
                        <label class="form-label">Állapot:</label>
                        <div>
                            <strong>Vázlat</strong>
                            <a href="#" class="text-decoration-none ms-2">Szerkesztés</a>
                        </div>
                    </div>

                    <!-- Láthatóság -->
                    <div class="mb-3">
                        <label class="form-label">Láthatóság:</label>
                        <?= $form->field($model, 'visibility')->dropDownList(
                            Post::getVisibilityOptions(),
                            ['id' => 'post-visibility']
                        )->label(false) ?>
                        
                        <div id="password-field" style="display: none;" class="mt-2">
                            <?= $form->field($model, 'password')->passwordInput([
                                'placeholder' => 'Jelszó'
                            ])->label(false) ?>
                        </div>
                    </div>

                    <!-- Azonnali közzététel -->
                    <div class="mb-3">
                        <label class="form-label">Azonnali közzététel:</label>
                        <div>
                            <strong><?= date('Y. M d. H:i') ?></strong>
                            <a href="#" class="text-decoration-none ms-2">Szerkesztés</a>
                        </div>
                    </div>

                    <!-- Hozzászólások -->
                    <div class="mb-3">
                        <?= $form->field($model, 'comment_status')->dropDownList(
                            Post::getCommentStatusOptions()
                        ) ?>
                    </div>

                    <!-- Rejtett mezők -->
                    <?= $form->field($model, 'status')->hiddenInput(['id' => 'post-status'])->label(false) ?>
                    <?= $form->field($model, 'author_id')->hiddenInput()->label(false) ?>

                    <!-- Gombok -->
                    <div class="d-grid gap-2">
                        <button type="button" id="preview-btn" class="btn btn-outline-secondary btn-sm">Előnézet</button>
                        <button type="button" id="draft-btn" class="btn btn-outline-primary btn-sm">Mentés vázlatként</button>
                        <button type="button" id="publish-btn" class="btn btn-primary btn-sm">Közzététel</button>
                    </div>
                </div>
            </div>

            <!-- Kategóriák -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">Kategóriák</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="<?= $category->id ?>" id="category-<?= $category->id ?>">
                                <label class="form-check-label" for="category-<?= $category->id ?>">
                                    <?= Html::encode($category->name) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Nincsenek elérhető kategóriák.</p>
                        <?= Html::a('Új kategória', ['/category/create'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Címkék -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">Címkék</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($tags)): ?>
                        <?php foreach ($tags as $tag): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="<?= $tag->id ?>" id="tag-<?= $tag->id ?>">
                                <label class="form-check-label" for="tag-<?= $tag->id ?>">
                                    <?= Html::encode($tag->name) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Nincsenek elérhető címkék.</p>
                        <?= Html::a('Új címke', ['/tag/create'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kiemelt kép -->
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">Kiemelt kép</h4>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'featured_image_id')->hiddenInput()->label(false) ?>
                    
                    <?php if (!empty($mediaFiles)): ?>
                        <div class="media-selector">
                            <div class="row g-2">
                                <?php foreach ($mediaFiles as $media): ?>
                                    <div class="col-6">
                                        <div class="media-item" data-id="<?= $media->id ?>">
                                            <img src="<?= $media->getFileUrl() ?>" alt="<?= Html::encode($media->alt_text) ?>" class="rounded">
                                            <div class="text-center text-muted small mt-1"><?= Html::encode($media->original_name) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Nincsenek elérhető képek.</p>
                        <?= Html::a('Média feltöltése', ['/media/create'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
