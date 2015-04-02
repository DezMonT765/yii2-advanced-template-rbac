<?php use \common\components\Alert;?>
<div class="alert alert-<?=$general_color?> alert-dismissible" style="margin-top: 20px">

    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <?if (count($succStore)) :?>
        <?=Alert::recursiveFind($succStore,'msg');?>
    <?endif?>
    <?if (count($warnStore)) :?>
        <?=Alert::recursiveFind($warnStore,'msg');?>
    <?endif?>
    <?if (count($errStore)) :?>
        <?=Alert::recursiveFind($errStore,'msg');?>
    <?endif?>
</div>
<script>


</script>

