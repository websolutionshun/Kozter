<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Media;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Média kezelése';
?>

<div class="media-index">
    <div class="page-header d-print-none">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Tartalom kezelése
                    </div>
                    <h2 class="page-title">
                        <?= Html::encode($this->title) ?>
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <?= Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg> Új média feltöltése', ['create'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-fluid">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Feltöltött médiák</h3>
                        </div>
                        
                        <!-- Drag & Drop feltöltés terület -->
                        <div class="card-body">
                            <div id="drop-zone" class="border border-dashed rounded p-4 mb-4 text-center" style="border-color: #dee2e6; background-color: #f8f9fa;">
                                <div class="drop-zone-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-muted mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                        <path d="M12 11v6"/>
                                        <path d="M9 14l3 -3l3 3"/>
                                    </svg>
                                    <h4 class="text-muted mb-1">Húzd ide a fájlokat feltöltéshez</h4>
                                    <p class="text-muted mb-0">vagy kattints a böngészéshez</p>
                                    <input type="file" id="file-input" multiple accept="image/*,video/*" style="display: none;">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-outline-primary" id="browse-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"/>
                                                <path d="M3 7l9 6l9 -6"/>
                                            </svg>
                                            Fájlok tallózása
                                        </button>
                                    </div>
                                </div>
                                <div id="upload-progress" style="display: none;">
                                    <div class="progress mb-2">
                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted">Feltöltés folyamatban...</small>
                                </div>
                            </div>
                        </div>

                        <?php Pjax::begin(['id' => 'media-grid-pjax']); ?>
                        
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'summary' => '<div class="card-body border-top py-3"><p class="text-muted mb-0">Összesen {totalCount} média, {begin}-{end} megjelenítve</p></div>',
                            'tableOptions' => ['class' => 'table table-vcenter card-table'],
                            'headerRowOptions' => ['class' => 'thead-light'],
                            'columns' => [
                                [
                                    'class' => 'yii\grid\CheckboxColumn',
                                    'checkboxOptions' => function ($model) {
                                        return ['value' => $model->id];
                                    }
                                ],
                                [
                                    'label' => 'Előnézet',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->media_type === Media::TYPE_IMAGE) {
                                            return Html::img($model->getFileUrl(), [
                                                'class' => 'rounded',
                                                'style' => 'width: 60px; height: 60px; object-fit: cover;'
                                            ]);
                                        } elseif ($model->media_type === Media::TYPE_VIDEO) {
                                            return '<div class="avatar avatar-lg" style="background-color: #206bc4;"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4z"/><path d="M3 6m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z"/></svg></div>';
                                        } else {
                                            return '<div class="avatar avatar-lg" style="background-color: #74889b;"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/></svg></div>';
                                        }
                                    },
                                    'contentOptions' => ['style' => 'width: 80px;']
                                ],
                                [
                                    'attribute' => 'original_name',
                                    'label' => 'Fájlnév',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return Html::a(Html::encode($model->original_name), ['view', 'id' => $model->id], ['class' => 'text-reset']);
                                    }
                                ],
                                [
                                    'attribute' => 'media_type',
                                    'label' => 'Típus',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        $colors = [
                                            Media::TYPE_IMAGE => 'success',
                                            Media::TYPE_VIDEO => 'primary',
                                            Media::TYPE_AUDIO => 'warning',
                                            Media::TYPE_DOCUMENT => 'info',
                                            Media::TYPE_OTHER => 'secondary',
                                        ];
                                        $color = $colors[$model->media_type] ?? 'secondary';
                                        return '<span class="badge bg-' . $color . '-lt">' . $model->getMediaTypeName() . '</span>';
                                    }
                                ],
                                [
                                    'attribute' => 'file_size',
                                    'label' => 'Méret',
                                    'value' => function ($model) {
                                        return $model->getHumanFileSize();
                                    }
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'label' => 'Feltöltve',
                                    'format' => ['datetime', 'php:Y.m.d H:i'],
                                    'contentOptions' => ['style' => 'width: 150px;']
                                ],
                                [
                                    'attribute' => 'status',
                                    'label' => 'Állapot',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->status === Media::STATUS_ACTIVE 
                                            ? '<span class="badge bg-success-lt">Aktív</span>'
                                            : '<span class="badge bg-secondary-lt">Inaktív</span>';
                                    },
                                    'contentOptions' => ['style' => 'width: 100px;']
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'header' => 'Műveletek',
                                    'template' => '{view} {update} {delete}',
                                    'buttons' => [
                                        'view' => function ($url, $model) {
                                            return Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/></svg>', $url, [
                                                'title' => 'Megtekintés',
                                                'class' => 'btn btn-sm btn-outline-primary me-1'
                                            ]);
                                        },
                                        'update' => function ($url, $model) {
                                            return Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/><path d="M16 5l3 3"/></svg>', $url, [
                                                'title' => 'Szerkesztés',
                                                'class' => 'btn btn-sm btn-outline-secondary me-1'
                                            ]);
                                        },
                                        'delete' => function ($url, $model) {
                                            return Html::a('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>', $url, [
                                                'title' => 'Törlés',
                                                'class' => 'btn btn-sm btn-outline-danger',
                                                'data' => [
                                                    'confirm' => 'Biztosan törölni szeretnéd ezt a médiát?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },
                                    ],
                                    'contentOptions' => ['style' => 'width: 120px; text-align: center;']
                                ],
                            ],
                        ]); ?>
                        
                        <?php Pjax::end(); ?>
                        
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">
                                Kiválasztott elemek törlése:
                                <?= Html::button('Kiválasztottak törlése', [
                                    'class' => 'btn btn-outline-danger btn-sm ms-2',
                                    'id' => 'bulk-delete-btn',
                                    'disabled' => true
                                ]) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#drop-zone {
    transition: all 0.3s ease;
    cursor: pointer;
}

#drop-zone.dragover {
    border-color: #0054a6 !important;
    background-color: #e3f2fd !important;
}

#drop-zone:hover {
    border-color: #74889b;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Drag & Drop functionality
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('file-input');
    const browseBtn = document.getElementById('browse-btn');
    const uploadProgress = document.getElementById('upload-progress');
    const progressBar = uploadProgress.querySelector('.progress-bar');
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);
    
    // Handle browse button
    browseBtn.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('click', () => fileInput.click());
    
    // Handle file input change
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight() {
        dropZone.classList.add('dragover');
    }
    
    function unhighlight() {
        dropZone.classList.remove('dragover');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }
    
    function handleFiles(files) {
        if (files.length === 0) return;
        
        // Show progress
        document.querySelector('.drop-zone-content').style.display = 'none';
        uploadProgress.style.display = 'block';
        
        let completed = 0;
        const total = files.length;
        
        Array.from(files).forEach(file => {
            uploadFile(file, () => {
                completed++;
                const progress = (completed / total) * 100;
                progressBar.style.width = progress + '%';
                
                if (completed === total) {
                    // Reload grid
                    $.pjax.reload({container: '#media-grid-pjax'});
                    
                    // Reset UI
                    setTimeout(() => {
                        document.querySelector('.drop-zone-content').style.display = 'block';
                        uploadProgress.style.display = 'none';
                        progressBar.style.width = '0%';
                        fileInput.value = '';
                    }, 1000);
                }
            });
        });
    }
    
    function uploadFile(file, callback) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->csrfToken ?>');
        
        fetch('<?= \yii\helpers\Url::to(['ajax-upload']) ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('File uploaded successfully:', data.data.original_name);
            } else {
                console.error('Upload failed:', data.message);
            }
            callback();
        })
        .catch(error => {
            console.error('Upload error:', error);
            callback();
        });
    }
    
    // Bulk delete functionality
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const checkboxes = document.querySelectorAll('input[name="selection[]"]');
    
    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('input[name="selection[]"]:checked');
        bulkDeleteBtn.disabled = checkedBoxes.length === 0;
    }
    
    // Add event listeners to existing checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkDeleteButton);
    });
    
    // Handle new checkboxes after PJAX reload
    $(document).on('pjax:complete', function() {
        const newCheckboxes = document.querySelectorAll('input[name="selection[]"]');
        newCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkDeleteButton);
        });
        updateBulkDeleteButton();
    });
    
    bulkDeleteBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('input[name="selection[]"]:checked');
        if (checkedBoxes.length === 0) return;
        
        if (confirm('Biztosan törölni szeretnéd a kiválasztott médiákat?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= \yii\helpers\Url::to(['bulk-delete']) ?>';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= Yii::$app->request->csrfParam ?>';
            csrfInput.value = '<?= Yii::$app->request->csrfToken ?>';
            form.appendChild(csrfInput);
            
            // Add selected IDs
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selection[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>
