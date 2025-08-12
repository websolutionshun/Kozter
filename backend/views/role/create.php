<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Role */
/* @var $form yii\widgets\ActiveForm */
/* @var $permissions common\models\Permission[] */
/* @var $selectedPermissions array */

$this->title = 'Új szerepkör létrehozása';
$this->params['breadcrumbs'][] = ['label' => 'Szerepkörök', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="role-create">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Új szerepkör létrehozása
                    </h3>
                    <div class="card-actions">
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
                                <label class="form-label text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 8h.01"/>
                                        <path d="M11 12h1v4h1"/>
                                    </svg>
                                    Tipp
                                </label>
                                <small class="form-hint">
                                    Használjon érthető, egyértelmű nevet a szerepkör számára. Ez fog megjelenni a felhasználói felületen.
                                </small>
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
                            <h4 class="mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <rect x="3" y="11" width="18" height="10" rx="2"/>
                                    <circle cx="12" cy="16" r="1"/>
                                    <path d="M7 11v-4a5 5 0 0 1 10 0v4"/>
                                </svg>
                                Jogosultságok
                            </h4>
                            <p class="text-muted mb-4">Válassza ki, mely jogosultságokkal rendelkezzen ez a szerepkör.</p>

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
                                                    <h5 class="card-title"><?= Html::encode($category) ?></h5>
                                                </div>
                                                <div class="card-body">
                                                    <?php foreach ($categoryPermissions as $permission): ?>
                                                        <label class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
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
                                    <h4 class="alert-title">Fontos tudnivalók</h4>
                                    <div class="text-muted">
                                        A jogosultságok meghatározzák, hogy a szerepkörrel rendelkező felhasználók mit tehetnek a rendszerben. 
                                        Csak a szükséges jogosultságokat adja meg a biztonsági kockázatok minimalizálása érdekében.
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
                            <?= Html::a('Mégse', ['index'], ['class' => 'btn']) ?>
                            <?= Html::submitButton('
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l5 5l10 -10"/>
                                </svg>
                                Szerepkör létrehozása', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kategória szintű select all funkció
    const categoryHeaders = document.querySelectorAll('.card-header h5');
    
    categoryHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.title = 'Kattintson ide az összes kiválasztásához/megszüntetéséhez';
        
        header.addEventListener('click', function() {
            const card = this.closest('.card');
            const checkboxes = card.querySelectorAll('input[type="checkbox"]');
            const checkedCount = card.querySelectorAll('input[type="checkbox"]:checked').length;
            const shouldCheck = checkedCount === 0;
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = shouldCheck;
            });
        });
    });
});
</script> 