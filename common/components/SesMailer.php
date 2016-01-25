<?php
namespace common\components;
use Aws\Ses\SesClient;
use yii\log\Logger;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 23.04.2015
 * Time: 14:08
 * @property SesClient $adapter
 */
class SesMailer extends Mailer
{
    private $_subject = "";

    public $config = [];

    public $adapter;

    public $messageClass = 'common\components\SesMessage';

    public function init() {

        $this->adapter = new SesClient($this->config);
    }


    /**
     * Sends the specified message.
     * This method should be implemented by child classes with the actual email sending logic.
     * @param SesMessage $message the message to be sent
     * @return boolean whether the message is sent successfully
     */
    protected function sendMessage($message)
    {
        if(count($message->attachments)) {
            $message->generateRawMessage();
            \Yii::getLogger()->log($message->getRawMessage(),Logger::LEVEL_INFO);
            $result = $this->adapter->sendRawEmail($message->getRawConfig());
        }
        else
        {
            $result = $this->adapter->sendEmail($message->getConfig());
        }
        \Yii::getLogger()->log(json_encode($result),Logger::LEVEL_INFO);
        return true;
    }
}