<?php

namespace common\models;

use common\components\Utility;
use common\models\query\UserQuery;
use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string $email
 * @property string $date_created
 * @property string $password write-only password
 * @property string $partner_id [varchar(10)]
 *
 * @property-read int $authKey
 * @property Menu $menu
 */
class User extends ActiveRecord implements IdentityInterface
{

    public static function tableName(): string
    {
        return 'user';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_created',
                'updatedAtAttribute' => false,
                'value' => Utility::getDateNow(),
            ]
        ];
    }

    /**
     * @throws \Exception
     */
    public function rules(): array
    {
        return [
            [['partner_id'], 'default', 'value' => function () {
                return $this->generatePartnerId();
            }],
            [['email', 'password_hash', 'partner_id'], 'required'],
            [['date_created'], 'safe'],
            [['email', 'password_hash'], 'string', 'max' => 255],
            [['partner_id'], 'string'],
            [['partner_id'], 'match', 'pattern' => '/^[0-9]{10}$/', 'message' => 'Должно быть 10 цифр'],
            [['email'], 'unique'],
            [['partner_id'], 'unique'],
        ];
    }


    /**
     * @throws \Exception
     */
    public function generatePartnerId(): string
    {
        $key = random_int(0, 9999999999);
        $partnerId = str_pad((string)$key, Yii::$app->params['sizePartnerId'], "0", STR_PAD_LEFT);

        if ($this->isExistPartnerId($partnerId)) {
            $this->generatePartnerId();
        }

        return $partnerId;
    }

    private function isExistPartnerId(string $partnerId): bool
    {
        return User::find()->byPartnerId($partnerId)->exists();
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail(string $email): ?User
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getMenu(): ActiveQuery
    {
        return $this->hasOne(Menu::class, ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getAuthKey()
    {
        return $this->id;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function isExists(): bool
    {
        return User::find()->exists();
    }

    public static function isNotExists(): bool
    {
        return !User::isExists();
    }

    public static function findByPartnerId(string $parentPartnerId): ?User
    {
        return self::findOne(['partner_id' => $parentPartnerId]);
    }

    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
    }
}
