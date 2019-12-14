<?php

/**
 * Class PmsgThumbs
 *
 * @property int account_id
 * @property string talk_hash
 * @property string avatar
 * @property string title
 *
 */
class PmsgThumbs extends ActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pmsg_thumbs';
    }

    public function getUserThumbs($account_id)
    {
        $criteria = new CDbCriteria;

        $criteria->addColumnCondition(['t.account_id' => $account_id]);
        $criteria->select = ['t.talk_hash `hash`', 't.avatar', 't.title', '(m.id is not null) `incoming`'];
        $criteria->join =
            'INNER JOIN `pmsg_cc` cc ON cc.account_id = t.account_id and cc.talk_hash = t.talk_hash '.
            'LEFT JOIN `pmsg` m ON m.talk_hash = t.talk_hash and m.msg_time > cc.last_visit';
        $criteria->distinct = true;

        return $this->getCommandBuilder()->createFindCommand($this->tableName(), $criteria)->queryAll();
    }
}
