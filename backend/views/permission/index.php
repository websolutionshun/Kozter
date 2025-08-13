<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $permissions array */
/* @var $roles common\models\Role[] */
/* @var $rolePermissions array */

$this->title = 'Jogosultságkezelés';
$this->params['breadcrumbs'][] = 'Jogosultságok';


?>

<div class="permission-index">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jogosultságok kezelése</h3>

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
                                            <div class="fw-semibold text-sm mb-1"><?= Html::encode($role->name) ?></div>
                                            <div class="form-check">
                                                <input class="form-check-input role-master-checkbox" type="checkbox" 
                                                       data-role-id="<?= $role->id ?>" 
                                                       onchange="toggleRoleAllPermissions(<?= $role->id ?>, this)">
                                                <label class="form-check-label text-muted small">Mind</label>
                                            </div>
                                        </div>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($permissions as $category => $categoryPermissions): ?>
                                <tr class="table-active">
                                    <td colspan="<?= count($roles) + 2 ?>" class="text-uppercase text-muted fw-semibold small py-2">
                                        <?= Html::encode($category) ?>
                                    </td>
                                </tr>
                                <?php foreach ($categoryPermissions as $permission): ?>
                                    <tr>
                                        <td class="py-1">
                                            <div>
                                                <div class="fw-medium"><?= Html::encode($permission->description) ?></div>
                                                <div class="text-muted small mt-1"><?= Html::encode($permission->name) ?></div>
                                            </div>
                                        </td>
                                        <td class="text-center py-1">
                                            <span class="badge bg-blue-lt text-blue"><?= Html::encode($permission->category) ?></span>
                                        </td>
                                        <?php foreach ($roles as $role): ?>
                                            <td class="text-center py-1">
                                                <div class="form-check form-switch d-flex justify-content-center">
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


</script> 