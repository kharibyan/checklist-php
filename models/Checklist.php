<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checklist".
 *
 * @property int $id
 * @property string $created_at
 * @property string|null $last_change
 * @property int $state_id
 * @property int $assigned_to_user_id Für wen ist die Liste
 * @property int $checklist_template_id Welches Template ist Basis für die Liste
 * @property string $name Wird vorbelegt mit dem Namen aus Template, kann aber verändert werden
 * @property int $owner_user_id Wer hat diese Checkliste aus dem Templat heraus angelegt?
 * @property string|null $comment
 */
class Checklist extends \yii\db\ActiveRecord
{

    public $checklistItems;
    public $ready;
    public $user_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checklist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'last_change'], 'safe'],
            [['state_id', 'assigned_to_user_id', 'checklist_template_id', 'owner_user_id'], 'integer'],
            [['assigned_to_user_id', 'checklist_template_id', 'name', 'owner_user_id'], 'required'],
            [['name'], 'string', 'max' => 100],
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
            'created_at' => 'Created At',
            'last_change' => 'Last Change',
            'state_id' => 'State ID',
            'assigned_to_user_id' => 'Assigned To User ID',
            'checklist_template_id' => 'Checklist Template ID',
            'name' => 'Name',
            'owner_user_id' => 'Owner User ID',
            'comment' => 'Comment',
        ];
    }

    public function getChecklistItems()
    {
        return $this->hasMany(ChecklistItem::class, ['checklist_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(UserTable::class, ['id' => 'assigned_to_user_id']);
    }
}
