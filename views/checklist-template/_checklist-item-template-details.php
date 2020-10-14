<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
?>

<div>
    <form action="<?= Yii::$app->urlManager->createUrl(['checklist-template/save-item-changes']) ?>" method="post">
        <input type="hidden" name="item_template_id" value="<?= Html::encode("{$checklistItemTemplate->id}") ?>" />
        <div class="form-group">
            <label for="templateItem">Item</label>
            <input type="text" class="form-control" name="item" value="<?= Html::encode("{$checklistItemTemplate->item}") ?>">
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>
</div>