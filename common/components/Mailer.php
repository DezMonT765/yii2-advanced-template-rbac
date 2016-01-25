<?php
namespace common\components;
use common\models\MailTemplates;
use Exception;
use yii\db\ActiveRecord;
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

    public $class = 'common\models\MailTemplates';
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
            /**@var $class ActiveRecord*/
            $class = $this->class;
            if(!($view instanceof $class))
                $template = $class::findOne(['template_type' => $view]);
            else $template = $view;
            if($template instanceof $class)
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