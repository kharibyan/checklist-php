<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property int $id
 * @property string $created_at
 * @property int $checklist_item_id
 * @property int $user_id
 * @property int $state_id_old
 * @property int $state_id_new
 * @property string|null $comment
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['checklist_item_id', 'user_id', 'state_id_new'], 'required'],
            [['checklist_item_id', 'user_id', 'state_id_old', 'state_id_new'], 'integer'],
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
            'checklist_item_id' => 'Checklist Item ID',
            'user_id' => 'User ID',
            'state_id_old' => 'State Id Old',
            'state_id_new' => 'State Id New',
            'comment' => 'Comment',
        ];
    }
}
