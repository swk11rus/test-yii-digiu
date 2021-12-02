<?php

namespace common\models;

use common\models\query\MenuQuery;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property int $user_id
 *
 * @property User $user
 *
 * @method makeRoot()
 * @method appendTo(Menu $menu)
 * @method parents(int $int)
 */

class Menu extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'menu';
    }

    public function rules(): array
    {
        return [
            [['lft', 'rgt', 'depth', 'user_id'], 'required'],
            [['lft', 'rgt', 'depth', 'user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function behaviors(): array
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::class,
//                 'treeAttribute' => 'tree',
                 'leftAttribute' => 'lft',
                 'rightAttribute' => 'rgt',
                 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'user_id' => 'UserID'
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function getUser(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function find(): MenuQuery
    {
        return new MenuQuery(get_called_class());
    }
}