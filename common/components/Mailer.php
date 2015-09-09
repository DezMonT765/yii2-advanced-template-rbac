<?php
namespace common\components;
use common\models\MailTemplates;
use Exception;
use yii\log\Logger;
use yii\mail\BaseMessage;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 23.04.2015
 * Time: 14:08
 */

class Mailer extends \zyx\phpmailer\Mailer
{
    private $_subject = "";

    public function compose($view = null, array $params = [])
    {
        if(!count($params)) $params = ['render'=>true];
        $message = parent::compose($view,$params);
        $message->setSubject($this->_subject);
        return $message;
    }

    public function render($view, $params = [], $layout = false)
    {
        try
        {
            $template = MailTemplates::findOne(['template_type' => $view]);
            if($template instanceof MailTemplates)
            {
                $this->_subject = $template->subject;
                $template_str = $template->template;

                $p = [];
                foreach ((array)$params as $name => $value)
                {
                    if(is_string($value))
                        $p['{' . $name . '}'] = $value;
                }
                $output = strtr($template_str, $p);
            }
            else $output = "";
            if($layout !== false)
            {
                return $this->getView()->render($layout, ['content' => $output, 'message' => $this->_message], $this);
            }
            else
            {
                return $output;
            }
        }
        catch(Exception $e)
        {
            \Yii::$app->log->logger->log($e->getMessage(),Logger::LEVEL_ERROR,"email");
            return "";
        }
    }

    private $_message;
}