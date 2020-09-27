<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $loginname
 * @property string $name
 * @property int $admin
 */
class UserTable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['loginname', 'name'], 'required'],
            [['admin'], 'integer'],
            [['loginname'], 'string', 'max' => 50],
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
            'loginname' => 'Loginname',
            'name' => 'Name',
            'admin' => 'Admin',
        ];
    }

    public static function getFullUserInfoById($id)
    {
        return static::findOne(['id' => $id]);
    }
}
