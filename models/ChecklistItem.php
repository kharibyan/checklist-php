<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checklist_item".
 *
 * @property int $id
 * @property string|null $last_change
 * @property int $checklist_id
 * @property int $sortorder
 * @property string $item
 * @property int $owner_user_id Wer hat dieses Item angelegt (aus Template oder der aktuelle User wenn nachträglich angelegt)
 * @property int $state_id
 * @property string|null $comment
 */
class ChecklistItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checklist_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['last_change'], 'safe'],
            [['checklist_id', 'sortorder', 'item'], 'required'],
            [['checklist_id', 'sortorder', 'owner_user_id', 'state_id'], 'integer'],
            [['item'], 'string', 'max' => 200],
            [['comment'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'last_change' => 'Last Change',
            'checklist_id' => 'Checklist ID',
            'sortorder' => 'Sortorder',
            'item' => 'Item',
            'owner_user_id' => 'Owner User ID',
            'state_id' => 'State ID',
            'comment' => 'Comment',
        ];
    }
}
