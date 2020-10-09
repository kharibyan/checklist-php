<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
?>

<div>
    <form>
        <div class="form-group">
            <label for="templateName">Name</label>
            <input type="text" class="form-control" id="templateName" value="<?= Html::encode("{$checklistTemplate->name}") ?>">
        </div>

        <button type="submit" class="btn btn-primary ">Speichern</button>
    </form>
</div>