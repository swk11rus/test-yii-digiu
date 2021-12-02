<?php

namespace backend\models;

use common\exceptions\ModelNotValidateException;
use common\models\Menu;
use common\models\User;
use yii\base\Model;

class RegisterForm extends Model
{
    const SCENARIO_FIRST_USER = 'first-user';

    public $email;
    public $parentPartnerId;
    public $password;

    public $selfPartnerId;

    private User $user;

    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            [['parentPartnerId'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['parentPartnerId'], 'existsPartnerId', 'on' => self::SCENARIO_DEFAULT],
            [['selfPartnerId'], 'required', 'on' => self::SCENARIO_FIRST_USER],
            [['parentPartnerId', 'selfPartnerId'], 'match', 'pattern' => '/^[0-9]{10}$/', 'message' => 'Должно быть 10 цифр'],
            [['email', 'password'], 'string'],
            [['email'], 'email'],
            [['parentPartnerId', 'selfPartnerId'], 'string', 'max' => \Yii::$app->params['sizePartnerId']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'selfPartnerId' => 'Партнерский ID первого пользователя',
            'parentPartnerId' => 'Идентификатор партнера'
        ];
    }

    public function existsPartnerId($attribute): void
    {
        if (!User::findByPartnerId($this->parentPartnerId)) {
            $this->addError($attribute, 'Данный PartnerID не зарегистрирован в системе');
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->createUser();
            $this->createMenu();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('error', $e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * @throws ModelNotValidateException
     * @throws \yii\base\Exception
     */
    private function createUser()
    {
        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);

        if ($this->scenario === self::SCENARIO_FIRST_USER) {
            $user->partner_id = $this->selfPartnerId;
        }

        if (!$user->save()) {
            throw new ModelNotValidateException($user);
        }

        $this->user = $user;
    }

    /**
     * @throws ModelNotValidateException
     */
    private function createMenu()
    {
        $menu = new Menu([
            'user_id' => $this->user->id,
            'depth' => 0,
            'rgt' => 3,
            'lft' => 0,
        ]);

        if ($this->scenario === self::SCENARIO_FIRST_USER) {
            $menu->makeRoot();
        } else {
            $partner = User::findByPartnerId($this->parentPartnerId);
            $menu->appendTo($partner->menu);
        }

        if (!$menu->save()) {
            throw new ModelNotValidateException($menu);
        }
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

}