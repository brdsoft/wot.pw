<?php

class Pmsg extends ActiveRecord
{
    public static function talkHashFromIds($ids = [])
    {
        $ids = array_map('intval', array_values($ids));
        sort($ids);
        return md5(implode(':', $ids));
    }

    public static function subscribe($account_id, $talk_hash)
    {
        $cc = new PmsgCc();

        $cc->talk_hash = $talk_hash;
        $cc->account_id = $account_id;

        try {
            return $cc->insert();
        } catch (CDbException $e) {
            return true;
        }
    }

    public static function createTalk($accounts = [])
    {
        $talk_hash = static::talkHashFromIds(array_map(function ($account) {
            /** @var Accounts $account */
            return $account->id;
        }, $accounts));

        foreach ($accounts as $account) {
            /** @var Accounts $account */
            static::subscribe($account->id, $talk_hash);
        }

        return $talk_hash;
    }

    public static function createDialog(Accounts $speaker, Accounts $listener)
    {
        $talk_hash = static::createTalk([$speaker, $listener]);

        $thumb = new PmsgThumbs();
        $thumb->account_id = $speaker->id;
        $thumb->talk_hash = $talk_hash;
        $thumb->avatar = $listener->getAvatar();
        $thumb->title = $listener->nickname;

        try {
            $thumb->save();
        } catch (CDbException $e) {
            // ignore
        }

        return $talk_hash;
    }

    public function lastMessages($talk_hash, $max_time = 0, $limit = 20) {
        $criteria = new CDbCriteria;

        $criteria->addColumnCondition(['talk_hash' => $talk_hash]);
        $criteria->join = 'join `accounts` a on a.id = m.talker_id';
        $criteria->order = 'm.msg_time desc';
        $criteria->limit = $limit;
        $criteria->alias = 'm';
        $criteria->select = ['m.content `content`', 'a.nickname `nick`', 'unix_timestamp(m.msg_time) `time`'];

        if ($max_time) {
            $criteria->addCondition('unix_timestamp(m.msg_time) < :max_time');
            $criteria->params[':max_time'] = $max_time;
        }

        return $this->getCommandBuilder()->createFindCommand($this->tableName(), $criteria)->queryAll();
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName() {
        return 'pmsg';
    }
}
