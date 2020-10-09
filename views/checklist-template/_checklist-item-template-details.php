<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
?>


<div>
    <form>
        <div class="form-group">
            <label for="templateItem">Item</label>
            <input type="text" class="form-control" id="templateItem" value="<?= Html::encode("{$checklistItemTemplate->item}") ?>">
        </div>

        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>
</div>