<?php

namespace common\models\query;

use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * This is the ActiveQuery class for [[\common\models\Menu]].
 *
 * @see \common\models\Menu
 */
class MenuQuery extends \yii\db\ActiveQuery
{

    public function behaviors(): array
    {
        return [
            NestedSetsQueryBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     * @return \common\models\Menu[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Menu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
