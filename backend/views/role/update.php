<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Role */
/* @var $form yii\widgets\ActiveForm */
/* @var $permissions common\models\Permission[] */
/* @var $selectedPermissions array */

$this->title = 'Szerepkör szerkesztése: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Szerepkörök', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Szerkesztés';
?>

<div class="role-update">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                            <path d="M16 5l3 3"/>
                        </svg>
                        Szerepkör szerkesztése
                    </h3>
                    <div class="card-actions">
                        <?= Html::a('
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                            </svg>
                            Megtekintés', ['view', 'id' => $model->id], ['class' => 'btn btn-white me-2']) ?>
                        <?= Html::a('
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <polyline points="15,6 9,12 15,18"/>
                            </svg>
                            Vissza', ['index'], ['class' => 'btn btn-secondary']) ?>
                    </div>
                </div>

                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'card-body'],
                ]); ?>

                    <?php if (in_array($model->name, ['admin', 'szerkesztő', 'szerző'])): ?>
                        <div class="alert alert-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 9v2m0 4v.01"/>
                                <path d="M5 19h14a2 2 0 0 0 1.414 -3.414l-7 -7a2 2 0 0 0 -2.828 0l-7 7a2 2 0 0 0 1.414 3.414"/>
                            </svg>
                            <h4 class="alert-title">Alapértelmezett szerepkör</h4>
                            <div class="text-muted">
                                Ez egy alapértelmezett szerepkör. A módosítások hatással lehetnek a rendszer működésére.
                                Kérjük, körültekintően járjon el a változtatások során.
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'pl. Szerkesztő, Tartalomkezelő...',
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Létrehozás ideje</label>
                                <div class="form-control-plaintext">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <polyline points="12,7 12,12 15,15"/>
                                    </svg>
                                    <?= date('Y.m.d H:i', $model->created_at) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($model, 'description')->textarea([
                                'rows' => 3,
                                'placeholder' => 'Rövid leírás a szerepkör funkcióiról és felelősségi köréről...',
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <rect x="3" y="11" width="18" height="10" rx="2"/>
                                        <circle cx="12" cy="16" r="1"/>
                                        <path d="M7 11v-4a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    Jogosultságok
                                </h4>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 12l5 5l10 -10"/>
                                        </svg>
                                        Összes kiválasztása
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M18 6l-12 12"/>
                                            <path d="M6 6l12 12"/>
                                        </svg>
                                        Kiválasztás törlése
                                    </button>
                                </div>
                            </div>
                            <p class="text-muted mb-4">Módosítsa a szerepkörhöz tartozó jogosultságokat.</p>

                            <?php if (!empty($permissions)): ?>
                                <?php
                                // Jogosultságok kategóriák szerint csoportosítva
                                $groupedPermissions = [];
                                foreach ($permissions as $permission) {
                                    $category = $permission->category ?: 'Általános';
                                    $groupedPermissions[$category][] = $permission;
                                }
                                ?>

                                <div class="row">
                                    <?php foreach ($groupedPermissions as $category => $categoryPermissions): ?>
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h5 class="card-title category-header" style="cursor: pointer;">
                                                        <?= Html::encode($category) ?>
                                                        <small class="text-muted">
                                                            (<span class="selected-count">0</span>/<?= count($categoryPermissions) ?>)
                                                        </small>
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <?php foreach ($categoryPermissions as $permission): ?>
                                                        <label class="form-check">
                                                            <input class="form-check-input permission-checkbox" type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="<?= $permission->id ?>"
                                                                   <?= in_array($permission->id, $selectedPermissions) ? 'checked' : '' ?>>
                                                            <span class="form-check-label">
                                                                <?= Html::encode($permission->name) ?>
                                                                <?php if ($permission->description): ?>
                                                                    <small class="form-hint"><?= Html::encode($permission->description) ?></small>
                                                                <?php endif; ?>
                                                            </span>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="alert alert-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 8h.01"/>
                                        <path d="M11 12h1v4h1"/>
                                    </svg>
                                    <h4 class="alert-title">Változások hatása</h4>
                                    <div class="text-muted">
                                        A jogosultságok módosítása azonnal hatályba lép az összes felhasználónál, akik ezzel a szerepkörrel rendelkeznek.
                                        Jelenleg <strong><?= count($model->users) ?> felhasználó</strong> rendelkezik ezzel a szerepkörrel.
                                    </div>
                                </div>

                            <?php else: ?>
                                <div class="empty">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 8v4"/>
                                            <path d="M12 16h.01"/>
                                        </svg>
                                    </div>
                                    <p class="empty-title">Nincsenek jogosultságok</p>
                                    <p class="empty-subtitle text-muted">
                                        A rendszerben még nincsenek definiált jogosultságok.
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent mt-auto">
                        <div class="btn-list justify-content-end">
                            <?= Html::a('Mégse', ['view', 'id' => $model->id], ['class' => 'btn']) ?>
                            <?= Html::submitButton('
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l5 5l10 -10"/>
                                </svg>
                                Módosítások mentése', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Számláló frissítése
    function updateCounts() {
        document.querySelectorAll('.card').forEach(card => {
            const checkboxes = card.querySelectorAll('.permission-checkbox');
            const checkedBoxes = card.querySelectorAll('.permission-checkbox:checked');
            const countElement = card.querySelector('.selected-count');
            
            if (countElement) {
                countElement.textContent = checkedBoxes.length;
            }
        });
    }
    
    // Inizializálás
    updateCounts();
    
    // Checkbox változás figyelése
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateCounts);
    });
    
    // Összes kiválasztása
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
        updateCounts();
    });
    
    // Kiválasztás törlése
    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateCounts();
    });
    
    // Kategória szintű toggle
    document.querySelectorAll('.category-header').forEach(header => {
        header.addEventListener('click', function() {
            const card = this.closest('.card');
            const checkboxes = card.querySelectorAll('.permission-checkbox');
            const checkedCount = card.querySelectorAll('.permission-checkbox:checked').length;
            const shouldCheck = checkedCount === 0;
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = shouldCheck;
            });
            updateCounts();
        });
    });
});
</script> 