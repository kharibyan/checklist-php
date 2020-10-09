<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checklist_template".
 *
 * @property int $id
 * @property string $name
 * @property int $owner_user_id
 */
class ChecklistTemplate extends \yii\db\ActiveRecord
{

    public $checklistItemTemplates;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checklist_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['owner_user_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'owner_user_id' => 'Owner User ID',
        ];
    }
}
