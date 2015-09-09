<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $user common\models\User */

$this->title = $model->email;
$user = Yii::$app->user->identity;
if($user->email_verification_status == User::EMAIL_NOT_VERIFIED):?>
    <br>
    <p>
        <?php echo Yii::t('message','You have not yet confirmed your email address. Please do so to ensure your account will remain active.')?>
        <a href="<?=Url::to('/user/get-verification-mail')?>"><?php echo Yii::t('messages','Click here to resend the confirmation email.')?></a>
    </p>
<?php endif?>
<div class="user-view">

    <br>


    <?= DetailView::widget([
                               'model' => $model,
                               'attributes' => [
                                   'id',
                                   'email:email',
                                   [
                                       'attribute'=>'status',
                                       'value'=>$model->getCurrentStatus()
                                   ],
                                   'created_at:datetime',
                                   'updated_at:datetime',
                                   [
                                       'attribute'=>'role',
                                       'value'=>$model->getCurrentRole()
                                   ],
                               ],
                           ]) ?>

</div>
