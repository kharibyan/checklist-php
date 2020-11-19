<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Checklisten';
$this->registerJsFile('js/checklist.js', ['depends' => [yii\web\JqueryAsset::class]]);
?>

<script type="text/javascript">
    let currentChecklistId = <?= isset($currentChecklist) ?  $currentChecklist : 0 ?>;
    let currentItemId = <?= isset($currentItem) ? $currentItem : 0 ?>;
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <button type="button" class="btn btn-default" data-toggle="modal" href="#modalCreateChecklist">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Neue Checkliste
            </button>
        </div>
        <div class="col-md-8">
            <button type="button" class="btn btn-default" data-toggle="modal" href="#modalCreateItem">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Neues Item
            </button>
            <button type="button" id="btnMoveUp" class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl(['checklist/item-move-up']) ?>">
                <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>Nach Oben
            </button>
            <button type="button" id="btnMoveDown" class="btn btn-default" href="<?= Yii::$app->urlManager->createUrl(['checklist/item-move-down']) ?>">
                <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>Nach Unten
            </button>
        </div>
    </div>

    <div class="row">

        <div class="col-md-4 col-scrollable" style="padding-left: 0;">


            <div id="checklist-accordion" class="panel-group">
                <?php foreach ($checklists as $checklist) : ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" id=<?= $checklist->id ?> data-toggle="collapse" data-parent="#checklist-accordion" data-target=<?= "#collapse" . $checklist->id ?> aria-expanded="false">
                            <h4 class="panel-title">
                                <a href="#">
                                    <?= Html::encode("{$checklist->name}") ?>
                                </a>
                            </h4>
                        </div>
                        <div id=<?= "collapse" . $checklist->id ?> class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <?php foreach ($checklist->checklistItems as $checklistItem) : ?>
                                        <tr id=<?= $checklistItem->id ?>>
                                            <td>
                                                <span class="
                                        <?php
                                        if ($checklistItem->state_id === 1) {
                                            echo 'glyphicon glyphicon-none';
                                        } else if ($checklistItem->state_id === 2) {
                                            echo 'glyphicon glyphicon-ok';
                                        } else if ($checklistItem->state_id === 3) {
                                            echo 'glyphicon glyphicon-minus';
                                        }
                                        ?>
                                        "></span>
                                            </td>
                                            <td>
                                                <a href="#"><?= Html::encode("{$checklistItem->item}") ?></a>
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

<!-- Neue Checkliste - Modal -->
<div class="modal fade" id="modalCreateChecklist" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Neue Checkliste erstellen</h4>
            </div>
            <form action="<?= Yii::$app->urlManager->createUrl(['checklist/create-checklist']) ?>" method="post" id="formCreateChecklist">
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
            <form action="<?= Yii::$app->urlManager->createUrl(['checklist/create-item']) ?>" method="post" id="formCreateItem">

                <input type="hidden" id="checklist_id" name="checklist_id" value="0" />

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