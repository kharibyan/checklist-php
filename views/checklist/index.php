<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Checklisten';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-8">
        </div>
    </div>

    <div class="row">

        <div class="col-md-4" style="padding-left: 0;">
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
                                <table class="table">
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