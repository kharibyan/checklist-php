<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Checklisten Templates';
$this->registerJsFile('js/checklist-template.js', ['depends' => [yii\web\JqueryAsset::class]]);
?>

<script type="text/javascript">
    let currentChecklistId = <?= isset($currentChecklist) ?  $currentChecklist : 0 ?>;
    let currentItemId = <?= isset($currentItem) ? $currentItem : 0 ?>;
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <button type="button" class="btn btn-default" data-toggle="modal" href="#modalCreateTemplate">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Neues Template
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" href="#modalAssignTemplate">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>Zuweisen
            </button>
        </div>
        <div class="col-md-8">
            <button type="button" class="btn btn-default" data-toggle="modal" href="#modalCreateItem">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Neues Item
            </button>
            <button type="button" id="btnMoveUp" class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl(['checklist-template/item-move-up']) ?>">
                <span class=" glyphicon glyphicon-arrow-up" aria-hidden="true""></span>Nach Oben
            </button>
            <button type=" button" id="btnMoveDown" class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl(['checklist-template/item-move-down']) ?>">
                    <span class=" glyphicon glyphicon-arrow-down" aria-hidden="true"></span>Nach Unten
            </button>
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>Löschen <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a id="linkDeleteItem" href="<?= Yii::$app->urlManager->createUrl(['checklist-template/delete-item']) ?>">Item Löschen</a></li>
                    <!-- <li role="separator" class="divider"></li> -->
                    <li><a id="linkDeleteTemplate" href="<?= Yii::$app->urlManager->createUrl(['checklist-template/delete-template']) ?>">Template Löschen</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-scrollable" style="padding-left: 0;">
            <div id="checklist-template-accordion" class="panel-group">
                <?php foreach ($checklistTemplates as $template) : ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" id=<?= $template->id ?> data-toggle="collapse" data-parent="#checklist-template-accordion" data-target=<?= "#collapse" . $template->id ?> aria-expanded="false">
                            <h4 class="panel-title">
                                <a href="#">
                                    <?= Html::encode("{$template->name}") ?>
                                </a>
                            </h4>
                        </div>
                        <div id=<?= "collapse" . $template->id ?> class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table template-table table-hover">
                                    <?php foreach ($template->checklistItemTemplates as $itemTemplate) : ?>
                                        <tr id=<?= $itemTemplate->id ?>>
                                            <td>
                                                <a href="#"><?= Html::encode("{$itemTemplate->item}") ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="details-view" class="col-md-8">
        </div>
    </div>
</div>

<!-- Zuweisen - Modal -->
<div class="modal fade" id="modalAssignTemplate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Checkliste an Benutzer zuweisen</h4>
            </div>
            <form action="<?= Yii::$app->urlManager->createUrl(['checklist-template/assign-template']) ?>" method="post" id="formAssignTemplate">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="checklist_template_id">Template wählen</label>
                        <select class="form-control" id="checklist_template_id" required="required" name="checklist_template_id">
                            <option></option>
                            <?php foreach ($checklistTemplates as $template) : ?>
                                <option value="<?= $template->id ?>"><?= Html::encode("{$template->name}") ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="checklist_name">Name</label>
                        <input type="text" class="form-control" id="checklist_name" name="name" required="required" value="" />
                    </div>
                    <div class="form-group">
                        <label for="assigned_to_user_id">Benutzer wählen</label>
                        <select class="form-control" id="assigned_to_user_id" name="assigned_to_user_id" required="required">
                            <option></option>
                            <?php foreach ($userList as $user) : ?>
                                <option value="<?= $user->id ?>"><?= Html::encode("{$user->name}") ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="foo" type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary">Zuweisen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Neues Template - Modal -->
<div class="modal fade" id="modalCreateTemplate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Neues Template erstellen</h4>
            </div>
            <form action="<?= Yii::$app->urlManager->createUrl(['checklist-template/create-template']) ?>" method="post" id="formCreateTemplate">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="checklist_template_id">Template wählen (optional)</label>
                        <select class="form-control" id="checklist_template_id" name="checklist_template_id">
                            <option></option>
                            <?php foreach ($checklistTemplates as $template) : ?>
                                <option value="<?= $template->id ?>"><?= Html::encode("{$template->name}") ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="checklist_name">Name</label>
                        <input type="text" class="form-control" id="checklist_name" name="name" required="required" value="" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="foo" type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary">Erstellen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Neues Item - Modal -->
<div class="modal fade" id="modalCreateItem" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Neues Item erstellen</h4>
            </div>
            <form action="<?= Yii::$app->urlManager->createUrl(['checklist-template/create-item']) ?>" method="post" id="formCreateItem">

                <input type="hidden" id="checklist_template_id" name="checklist_template_id" value="0" />

                <div class="modal-body">
                    <div class="form-group">
                        <label for="item_name">Name</label>
                        <input type="text" class="form-control" id="item_name" name="name" required="required" value="" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="foo" type="button" class="btn btn-default" data-dismiss="modal">Schließen</button>
                    <button type="submit" class="btn btn-primary">Erstellen</button>
                </div>
            </form>
        </div>
    </div>
</div>