<?php use \common\components\Alert;?>
<div class="alert alert-<?=$general_color?> alert-dismissible" style="margin-top: 20px">

    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <?if (count($success_store)) :?>
        <?=Alert::recursiveFind($success_store,'msg');?>
    <?endif?>
    <?if (count($warning_store)) :?>
        <?=Alert::recursiveFind($warning_store,'msg');?>
    <?endif?>
    <?if (count($error_store)) :?>
        <?=Alert::recursiveFind($error_store,'msg');?>
    <?endif?>
</div>
<script>


</script>

