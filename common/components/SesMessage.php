<?php
namespace common\components;
use Aws\Ses\SesClient;
use common\helpers\Helper;
use common\helpers\HelperImage;
use common\models\MailTemplates;
use Exception;
use Yii;
use yii\base\NotSupportedException;
use yii\log\Logger;
use yii\mail\BaseMailer;
use yii\mail\BaseMessage;
use yii\mail\MessageInterface;

/**
 * Created by PhpStorm.
 * User: DezMonT
 * Date: 23.04.2015
 * Time: 14:08
 */

class SesMessage extends BaseMessage
{
    public $charset;
    public $attachments = [];
    public $config = [
        'Destination' => [ // REQUIRED
        'BccAddresses' => [],
        'CcAddresses' => [],
        'ToAddresses' => [],
    ],
    'Message' => [ // REQUIRED
        'Body' => [ // REQUIRED
            'Html' => [
                'Charset' => '',
                'Data' => '', // REQUIRED
            ],
            'Text' => [
                'Charset' => '',
                'Data' => '', // REQUIRED
            ],
        ],
        'Subject' => [ // REQUIRED
            'Charset' => '',
            'Data' => '', // REQUIRED
        ],
    ],
    'ReplyToAddresses' => [],
    'ReturnPath' => '',
    'ReturnPathArn' => '',
    'Source' => '', // REQUIRED
    'SourceArn' => '',

    ];

    public $raw_config = [
        'Destinations' => [],
        'FromArn' => '',
        'RawMessage' => [ // REQUIRED
                          'Data' => '',
        ],
        'ReturnPathArn' => '',
        'Source' => '',
        'SourceArn' => '',
    ];

    public function getConfig() {
        return Helper::recursive_clear($this->config);
    }

    public function getRawConfig() {
        return Helper::recursive_clear($this->raw_config);
    }
    /**
     * Returns the character set of this message.
     * @return string the character set of this message.
     */
    public function getCharset()
    {
        return $this->charset;
    }



    /**
     * Sets the character set of this message.
     * @param string $charset character set name.
     * @return $this self reference.
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        $this->config['Message']['Body']['Html']['Charset'] = $charset;
        $this->config['Message']['Body']['Text']['Charset'] = $charset;
        $this->config['Message']['Subject']['Charset'] = $charset;
        return $this;
    }


    /**
     * Returns the message sender.
     * @return string the sender
     */
    public function getFrom()
    {
        return $this->config['Source'];
    }


    /**
     * Sets the message sender.
     * @param string|array $from sender email address.
     * You may pass an array of addresses if this message is from multiple people.
     * You may also specify sender name in addition to email address using format:
     * `[email => name]`.
     * @return $this self reference.
     */
    public function setFrom($from)
    {
        $this->config['Source'] = $from;
        return $this;
    }


    /**
     * Returns the message recipient(s).
     * @return array the message recipients
     */
    public function getTo()
    {
        return $this->config['Destination']['ToAddresses'];
    }


    /**
     * Sets the message recipient(s).
     * @param string|array $to receiver email address.
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format:
     * `[email => name]`.
     * @return $this self reference.
     */
    public function setTo($to)
    {
        if(is_array($to))
            array_merge($this->config['Destination']['ToAddresses'],$to);
        else {
            $this->config['Destination']['ToAddresses'][] = $to;
        }
        return $this;
    }

    public function encodeRecipients($recipient)
    {
        if (is_array($recipient)) {
            return join(', ', array_map(array($this, 'encodeRecipients'), $recipient));
        }
        return $recipient;
    }


    /**
     * Returns the reply-to address of this message.
     * @return string the reply-to address of this message.
     */
    public function getReplyTo()
    {
        return $this->config['ReplyToAddresses'];
    }


    /**
     * Sets the reply-to address of this message.
     * @param string|array $replyTo the reply-to address.
     * You may pass an array of addresses if this message should be replied to multiple people.
     * You may also specify reply-to name in addition to email address using format:
     * `[email => name]`.
     * @return $this self reference.
     */
    public function setReplyTo($replyTo)
    {
        $this->config['ReplyToAddresses'][] = $replyTo;
        return $this;
    }


    /**
     * Returns the Cc (additional copy receiver) addresses of this message.
     * @return array the Cc (additional copy receiver) addresses of this message.
     */
    public function getCc()
    {
        return $this->config['Destination']['CcAddresses'];
    }


    /**
     * Sets the Cc (additional copy receiver) addresses of this message.
     * @param string|array $cc copy receiver email address.
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format:
     * `[email => name]`.
     * @return $this self reference.
     */
    public function setCc($cc)
    {
        $this->config['Destination']['CcAddresses'][] = $cc;
        return $this;
    }


    /**
     * Returns the Bcc (hidden copy receiver) addresses of this message.
     * @return array the Bcc (hidden copy receiver) addresses of this message.
     */
    public function getBcc()
    {
        return $this->config['Destination']['BccAddresses'];
    }


    /**
     * Sets the Bcc (hidden copy receiver) addresses of this message.
     * @param string|array $bcc hidden copy receiver email address.
     * You may pass an array of addresses if multiple recipients should receive this message.
     * You may also specify receiver name in addition to email address using format:
     * `[email => name]`.
     * @return $this self reference.
     */
    public function setBcc($bcc)
    {
        $this->config['Destination']['BccAddresses'][] = $bcc;
        return $this;
    }


    /**
     * Returns the message subject.
     * @return string the message subject
     */
    public function getSubject()
    {
       return $this->config['Message']['Subject']['Data'];
    }


    /**
     * Sets the message subject.
     * @param string $subject message subject
     * @return $this self reference.
     */
    public function setSubject($subject)
    {
        $this->config['Message']['Subject']['Data'] = $subject;
        return $this;
    }


    public function setRawMessage($content) {
        $this->raw_config['RawMessage']['Data'] = $content;
    }

    public function getRawMessage() {
        return $this->raw_config['RawMessage']['Data'];
    }

    /**
     * Sets message plain text content.
     * @param string $text message plain text content.
     * @return $this self reference.
     */
    public function setTextBody($text)
    {
        $this->config['Message']['Body']['Text']['Data'] = $text;
        return $this;
    }


    /**
     * Sets message HTML content.
     * @param string $html message HTML content.
     * @return $this self reference.
     */
    public function setHtmlBody($html)
    {
        $this->config['Message']['Body']['Html']['Data'] = $html;
        return $this;
    }

    public function getHtmlBody() {
       return $this->config['Message']['Body']['Html']['Data'];
    }

    public function getTextBody () {
        return $this->config['Message']['Body']['Text']['Data'];
    }

    public function hasInlineAttachments()
    {
        foreach($this->attachments as $attachment) {
            if($attachment['attachmentType'] != 'attachment')
                return true;
        }
        return false;
    }


    /**
     * Add email attachment by directly passing the content
     *
     * @param string $name      The name of the file attachment as it will appear in the email
     * @param string $data      The contents of the attachment file
     * @param string $mimeType  Specify custom MIME type
     * @param string $contentId Content ID of the attachment for inclusion in the mail message
     * @param string $attachmentType    Attachment type: attachment or inline
     * @return SesMessage $this
     */
    public function addAttachmentFromData($name, $data, $mimeType = 'application/octet-stream', $contentId = null, $attachmentType = 'attachment') {
        $this->attachments[$name] = array(
            'name' => $name,
            'mimeType' => $mimeType,
            'data' => $data,
            'contentId' => $contentId,
            'attachmentType' => ($attachmentType == 'inline' ? 'inline; filename="' . $name . '"' : $attachmentType),
        );
        return $this;
    }
    /**
     * Add email attachment by passing file path
     *
     * @param string $name      The name of the file attachment as it will appear in the email
     * @param string $path      Path to the attachment file
     * @param string $mimeType  Specify custom MIME type
     * @param string $contentId Content ID of the attachment for inclusion in the mail message
     * @param string $attachmentType    Attachment type: attachment or inline
     * @return  boolean Status of the operation
     */
    public function addAttachmentFromFile($name, $path, $mimeType = 'application/octet-stream', $contentId = null, $attachmentType = 'attachment') {
        if (file_exists($path) && is_file($path) && is_readable($path)) {
            $this->addAttachmentFromData($name, file_get_contents($path), $mimeType, $contentId, $attachmentType);
            return true;
        }
        return false;
    }

    public function generateRawMessage()
    {
        $boundary = uniqid(rand(), true);
        $raw_message = "";
        $raw_message .= 'To:' . $this->encodeRecipients($this->getTo()) . "\n";
        $raw_message .= 'From:' . $this->encodeRecipients($this->getFrom()) . "\n";
        if(!empty($this->getReplyTo())) $raw_message .= 'Reply-To:' . $this->encodeRecipients($this->getReplyTo()) . "\n";
        if (!empty($this->getCc())) {
            $raw_message .= 'CC: ' . $this->encodeRecipients($this->getCc()) . "\n";
        }
        if (!empty($this->getBcc())) {
            $raw_message .= 'BCC: ' . $this->encodeRecipients($this->getBcc()) . "\n";
        }
        if($this->getSubject() != null && strlen($this->getSubject()) > 0) {
            $raw_message .= 'Subject: ' .$this->getSubject() . "\n";
        }
        $raw_message .= 'MIME-Version: 1.0' . "\n";
        $raw_message .= 'Content-type: ' . ($this->hasInlineAttachments() ? 'multipart/related' : 'Multipart/Mixed') . '; boundary="' . $boundary . '"' . "\n";
        $raw_message .= "\n--{$boundary}\n";
        $raw_message .= 'Content-type: Multipart/Alternative; boundary="alt-' . $boundary . '"' . "\n";
        if($this->getTextBody() != null && strlen($this->getTextBody()) > 0) {
            $charset = empty($this->charset) ? '' : "; charset=\"{$this->charset}\"";
            $raw_message .= "\n--alt-{$boundary}\n";
            $raw_message .= 'Content-Type: text/plain' . $charset . "\n\n";
            $raw_message .= $this->getTextBody() . "\n";
        }
        if($this->getHtmlBody() != null && strlen($this->getHtmlBody()) > 0) {
            $charset = empty($this->charset) ? '' : "; charset=\"{$this->charset}\"";
            $raw_message .= "\n--alt-{$boundary}\n";
            $raw_message .= 'Content-Type: text/html' . $charset . "\n\n";
            $raw_message .= $this->getHtmlBody() . "\n";
        }
        $raw_message .= "\n--alt-{$boundary}--\n";
        foreach($this->attachments as $attachment) {
            $raw_message .= "\n--{$boundary}\n";
            $raw_message .= 'Content-Type: ' . $attachment['mimeType'] . '; name="' . $attachment['name'] . '"' . "\n";
            $raw_message .= 'Content-Disposition: ' . $attachment['attachmentType'] . "\n";
            if(!empty($attachment['contentId'])) {
                $raw_message .= 'Content-ID: ' . $attachment['contentId'] . '' . "\n";
            }
            $raw_message .= 'Content-Transfer-Encoding: base64' . "\n";
            $raw_message .= "\n" . chunk_split(base64_encode($attachment['data']), 76, "\n") . "\n";
        }
        $raw_message .= "\n--{$boundary}--\n";
        $this->setRawMessage($raw_message);
    }


    /**
     * Attaches existing file to the email message.
     * @param string $fileName full file name
     * @param array $options options for embed file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * @throws NotSupportedException
     * @return $this self reference.
     */
    public function attach($fileName, array $options = [])
    {
        $name = isset($options['fileName']) ? $options['fileName'] : Yii::$app->security->generateRandomString(16);
        $content_type = isset($options['contentType']) ? $options['contentType'] : 'application/octet-stream';
        self::addAttachmentFromFile($name,$fileName,$content_type);
        return $this;
    }


    /**
     * Attach specified content as file for the email message.
     * @param string $content attachment file content.
     * @param array $options options for embed file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * @throws NotSupportedException
     * @return $this self reference.
     */
    public function attachContent($content, array $options = [])
    {
        throw new NotSupportedException();
    }


    /**
     * Attach a file and return it's CID source.
     * This method should be used when embedding images or other data in a message.
     * @param string $fileName file name.
     * @param array $options options for embed file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * @throws NotSupportedException
     * @return string attachment CID.
     */
    public function embed($fileName, array $options = [])
    {
        throw new NotSupportedException();
    }


    /**
     * Attach a content as file and return it's CID source.
     * This method should be used when embedding images or other data in a message.
     * @param string $content attachment file content.
     * @param array $options options for embed file. Valid options are:
     *
     * - fileName: name, which should be used to attach file.
     * - contentType: attached file MIME type.
     *
     * @throws NotSupportedException
     * @return string attachment CID.
     */
    public function embedContent($content, array $options = [])
    {
        throw new NotSupportedException();
    }


    /**
     * Returns string representation of this message.
     * @return string the string representation of this message.
     */
    public function toString()
    {
        return json_encode($this->config);
    }
}