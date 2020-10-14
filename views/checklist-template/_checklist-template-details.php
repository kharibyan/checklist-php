<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
?>

<div>
    <form action="<?= Yii::$app->urlManager->createUrl(['checklist-template/save-template-changes']) ?>" method="post">
        <input type="hidden" name="checklist_template_id" value="<?= Html::encode("{$checklistTemplate->id}") ?>" />
        <div class="form-group">
            <label for="templateName">Name</label>
            <input type="text" class="form-control" name="name" value="<?= Html::encode("{$checklistTemplate->name}") ?>">
        </div>

        <button type="submit" class="btn btn-primary ">Speichern</button>
    </form>
</div>