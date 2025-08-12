<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $permissions array */
/* @var $roles common\models\Role[] */
/* @var $rolePermissions array */

$this->title = 'Jogosultságkezelés';
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="permission-index">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jogosultságok kezelése</h3>
                    <div class="card-actions">
                        <div class="btn-list">
                            <button type="button" class="btn btn-secondary" onclick="selectAllPermissions()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9,11 12,14 22,4"/>
                                    <path d="M21 12v7a2 2 0 0 1 -2 2H5a2 2 0 0 1 -2 -2V5a2 2 0 0 1 2 -2h11"/>
                                </svg>
                                Mind kijelölés
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="deselectAllPermissions()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                </svg>
                                Mind törlés
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Jogosultság neve</th>
                                <th class="text-center">Kategória</th>
                                <?php foreach ($roles as $role): ?>
                                    <th class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="font-weight-medium"><?= Html::encode($role->name) ?></div>
                                            <div class="form-check">
                                                <input class="form-check-input role-master-checkbox" type="checkbox" 
                                                       data-role-id="<?= $role->id ?>" 
                                                       onchange="toggleRoleAllPermissions(<?= $role->id ?>, this)">
                                                <label class="form-check-label text-muted">Mind</label>
                                            </div>
                                        </div>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($permissions as $category => $categoryPermissions): ?>
                                <tr class="table-active">
                                    <td colspan="<?= count($roles) + 2 ?>" class="text-uppercase text-muted font-weight-medium">
                                        <strong><?= Html::encode($category) ?></strong>
                                    </td>
                                </tr>
                                <?php foreach ($categoryPermissions as $permission): ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <div class="font-weight-medium"><?= Html::encode($permission->description) ?></div>
                                                <div class="text-muted small"><?= Html::encode($permission->name) ?></div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-azure"><?= Html::encode($permission->category) ?></span>
                                        </td>
                                        <?php foreach ($roles as $role): ?>
                                            <td class="text-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input permission-checkbox" type="checkbox" 
                                                           data-role-id="<?= $role->id ?>"
                                                           data-permission-id="<?= $permission->id ?>"
                                                           <?= in_array($permission->id, $rolePermissions[$role->id] ?? []) ? 'checked' : '' ?>
                                                           onchange="togglePermission(<?= $role->id ?>, <?= $permission->id ?>, this)">
                                                </div>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                            Módosítások automatikusan mentődnek
                        </div>
                        <div class="btn-list">
                            <?= Html::a('
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/>
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/>
                                </svg>
                                Szerepkörök kezelése', ['/role/index'], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M12 1v6m0 6v6"/>
                                    <path d="M21 12h-6m-6 0H3"/>
                                </svg>
                                Felhasználók kezelése', ['/user/index'], ['class' => 'btn btn-secondary']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('permission-checkbox')) {
        const roleId = e.target.dataset.roleId;
        const roleCheckboxes = document.querySelectorAll(`input[data-role-id="${roleId}"].permission-checkbox`);
        const checkedCount = Array.from(roleCheckboxes).filter(cb => cb.checked).length;
        const masterCheckbox = document.querySelector(`.role-master-checkbox[data-role-id="${roleId}"]`);
        
        if (masterCheckbox) {
            masterCheckbox.checked = checkedCount === roleCheckboxes.length;
            masterCheckbox.indeterminate = checkedCount > 0 && checkedCount < roleCheckboxes.length;
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.role-master-checkbox').forEach(master => {
        const roleId = master.dataset.roleId;
        const roleCheckboxes = document.querySelectorAll(`input[data-role-id="${roleId}"].permission-checkbox`);
        const checkedCount = Array.from(roleCheckboxes).filter(cb => cb.checked).length;
        
        master.checked = checkedCount === roleCheckboxes.length;
        master.indeterminate = checkedCount > 0 && checkedCount < roleCheckboxes.length;
    });
});

function getCsrfToken() {
    let csrfMeta = document.querySelector('meta[name="csrf-token"]') || 
                   document.querySelector('meta[name="_csrf"]') ||
                   document.querySelector('meta[name="_csrf-backend"]') ||
                   document.querySelector('meta[name="csrf_token"]');
    
    if (csrfMeta) {
        return csrfMeta.content;
    }
    
    let csrfInput = document.querySelector('input[name="_csrf"]') ||
                    document.querySelector('input[name="_csrf-backend"]') ||
                    document.querySelector('input[name="YII_CSRF_TOKEN"]');
    
    if (csrfInput) {
        return csrfInput.value;
    }
    
    return null;
}

function togglePermission(roleId, permissionId, checkbox) {
    const enabled = checkbox.checked;
    const csrfToken = getCsrfToken();
    
    if (!csrfToken) {
        checkbox.checked = !enabled;
        alert('Biztonsági hiba: CSRF token nem található.');
        return;
    }
    
    const formData = new FormData();
    formData.append('role_id', roleId);
    formData.append('permission_id', permissionId);
    formData.append('enabled', enabled);
    formData.append('_csrf-backend', csrfToken);
    
    fetch('<?= Url::to(['permission/toggle']) ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            checkbox.checked = !enabled;
            alert('Hiba: ' + data.message);
        }
    })
    .catch(error => {
        checkbox.checked = !enabled;
        alert('Hiba történt a kérés során.');
    });
}

function toggleRoleAllPermissions(roleId, masterCheckbox) {
    const checked = masterCheckbox.checked;
    const permissionCheckboxes = document.querySelectorAll(`input[data-role-id="${roleId}"].permission-checkbox`);
    
    permissionCheckboxes.forEach(checkbox => {
        if (checkbox.checked !== checked) {
            checkbox.checked = checked;
            togglePermission(roleId, checkbox.dataset.permissionId, checkbox);
        }
    });
}

function selectAllPermissions() {
    const allCheckboxes = document.querySelectorAll('.permission-checkbox');
    
    allCheckboxes.forEach(checkbox => {
        if (!checkbox.checked) {
            checkbox.checked = true;
            togglePermission(checkbox.dataset.roleId, checkbox.dataset.permissionId, checkbox);
        }
    });
    
    document.querySelectorAll('.role-master-checkbox').forEach(master => {
        master.checked = true;
    });
}

function deselectAllPermissions() {
    const allCheckboxes = document.querySelectorAll('.permission-checkbox');
    
    allCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            checkbox.checked = false;
            togglePermission(checkbox.dataset.roleId, checkbox.dataset.permissionId, checkbox);
        }
    });
    
    document.querySelectorAll('.role-master-checkbox').forEach(master => {
        master.checked = false;
    });
}
</script> 