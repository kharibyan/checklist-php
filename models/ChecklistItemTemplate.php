<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checklist_item_template".
 *
 * @property int $id
 * @property int $checklist_template_id
 * @property int $sortorder
 * @property string $item
 * @property int $owner_user_id
 */
class ChecklistItemTemplate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checklist_item_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['checklist_template_id', 'sortorder', 'item'], 'required'],
            [['checklist_template_id', 'sortorder', 'owner_user_id'], 'integer'],
            [['item'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'checklist_template_id' => 'Checklist Template ID',
            'sortorder' => 'Sortorder',
            'item' => 'Item',
            'owner_user_id' => 'Owner User ID',
        ];
    }
}
