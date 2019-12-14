<?php

/**
 * Class PmsgCc
 *
 * @property string talk_hash
 * @property int account_id
 * @property string last_visit
 *
 */
class PmsgCc extends ActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pmsg_cc';
    }

    public static function visit($talk_hash, $account_id)
    {
        $model = static::model();

        $prev = Yii::app()->db->createCommand()
            ->select('unix_timestamp(last_visit) `last_visit`')
            ->from($model->tableName())
            ->where('talk_hash = :th and account_id = :aid', [':th' => $talk_hash, ':aid' => $account_id])
            ->queryRow();

        if ($prev) {
            static::model()->updateAll(
                ['last_visit' => new CDbExpression('NOW()')],
                'talk_hash = :th and account_id = :aid',
                [':th' => $talk_hash, ':aid' => $account_id]
            );

            return $prev['last_visit'];
        }

        return null;
    }
}
