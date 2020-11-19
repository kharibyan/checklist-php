<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Benutzer Checklisten';
$this->registerJsFile('js/user-checklists.js', ['depends' => [yii\web\JqueryAsset::class]]);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <select class="form-control" id="selectUser">
                <option></option>
                <?php foreach ($userList as $user) : ?>
                    <option value="<?= $user->id ?>"><?= Html::encode("{$user->name}") ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-scrollable" style="padding-left: 0;">
            <table class="table table-hover" id="checklist-list">
                <?php foreach ($checklists as $checklist) : ?>
                    <tr id="<?= $checklist->id ?>">
                        <td class="user-checklists-td">
                            <span class="
                            <?php
                            if ($checklist->state_id === 1) {
                                echo 'glyphicon glyphicon-none';
                            } else if ($checklist->state_id === 2) {
                                echo 'glyphicon glyphicon-ok';
                            }
                            ?>
                            "></span>
                        </td>
                        <td class="user-checklists-td">
                            <a href="#"><?= Html::encode("{$checklist->name}") ?> <span>(<?= Html::encode("{$checklist->user_name}") ?>)</span></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div id="details-view" class="col-md-8">

        </div>
    </div>
</div>