<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
?>

<script type="text/javascript">
    let currentChecklistId = <?= $checklist->id ?>;
</script>

<div>
    <button type=" button" id="btnDelete" class="btn btn-danger" href="<?= Yii::$app->urlManager->createUrl(['user-checklists/delete-checklist']) ?>">
        <span class=" glyphicon glyphicon-trash" aria-hidden="true"></span>Löschen
    </button>

    <h2><?= Html::encode("{$checklist->user_name}") . '→' . Html::encode("{$checklist->name}") ?></h2>

    <div class="form-group">
        <label for="commnet">Kommentar</label>
        <input type="text" class="form-control" value="<?= Html::encode("{$checklist->comment}") ?>" readonly>
    </div>

    <?php if (!empty($checklist->checklistItems)) : ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Item</th>
                    <th>Kommentar</th>
                    <th>Letzte Änderung</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($checklist->checklistItems as $item) : ?>
                    <tr>
                        <td>
                            <span class="
                                        <?php
                                        if ($item->state_id === 1) {
                                            echo 'glyphicon glyphicon-none';
                                        } else if ($item->state_id === 2) {
                                            echo 'glyphicon glyphicon-ok';
                                        } else if ($item->state_id === 3) {
                                            echo 'glyphicon glyphicon-minus';
                                        }
                                        ?>
                                        "></span>
                        </td>
                        <td><?= Html::encode("{$item->item}") ?></td>
                        <td><?= Html::encode("{$item->comment}") ?></td>
                        <td><?= Html::encode("{$checklist->last_change}") ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>