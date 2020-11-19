<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "checklist_schedule".
 *
 * @property int $id
 * @property int $checklist_template_id
 * @property int $owner_user_id Wer soll als Owner verknüpft werden?
 * @property int $assign_to_user_id Für wen?
 * @property string|null $start_date Wenn nicht angegeben, dann ASAP eine Cehckliste erzeugen
 * @property string|null $end_date Wenn nicht angegeben, dann werden zeitlich unbegrenzt Checklisten aus den Templates erzeugt
 * @property string|null $last_schedule Hier wird der Zeitpunkt eingetragen, an dem zuletzt per Schedule eine Checkliste erzeugt wurde
 * @property int $interval_count
 * @property string $interval_unit Ausprägungen day, week, month, year
 */
class ChecklistSchedule extends \yii\db\ActiveRecord
{
    public $template_name;
    public $owner_name;
    public $assigned_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checklist_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['checklist_template_id', 'owner_user_id', 'assign_to_user_id', 'interval_count'], 'required'],
            [['checklist_template_id', 'owner_user_id', 'assign_to_user_id', 'interval_count'], 'integer'],
            [['start_date', 'end_date', 'last_schedule'], 'safe'],
            [['interval_unit'], 'string', 'max' => 20],
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
            'owner_user_id' => 'Owner User ID',
            'assign_to_user_id' => 'Assign To User ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'last_schedule' => 'Last Schedule',
            'interval_count' => 'Interval Count',
            'interval_unit' => 'Interval Unit',
        ];
    }

    public function getTemplate()
    {
        return $this->hasOne(ChecklistTemplate::class, ['id' => 'checklist_template_id']);
    }

    public function getOwner()
    {
        return $this->hasOne(UserTable::class, ['id' => 'owner_user_id']);
    }

    public function getAssigned()
    {
        return $this->hasOne(UserTable::class, ['id' => 'assign_to_user_id']);
    }
}
