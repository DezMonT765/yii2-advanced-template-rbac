<div class="alert alert-<?=$general_color?> in fade">
    <?=Yii::t('app',$general_message)?>
    <a class="details-link"  >Details</a>
    <?if (count($succStore)) :?>
        <pre style="display: none">
        <?var_dump($succStore);?>
    </pre>
    <?endif?>
    <?if (count($warnStore)) :?>
        <pre style="display: none">
        <?var_dump($warnStore);?>
    </pre>
    <?endif?>
    <?if (count($errStore)) :?>
        <pre style="display: none">
        <?var_dump($errStore);?>
    </pre>
    <?endif?>
    <a class="hide-link" style="display:none">Hide...</a>
</div>
<script>
    $('.details-link').click(function()
    {
        $(this).attr('style','display:none');
        $('.hide-link').attr('style','display:inline');
        $(this).siblings('pre').attr('style','display:block');
    });
    $('.hide-link').click(function()
    {
        $(this).attr('style','display:none');
        $('.details-link').attr('style','display:inline');
        $(this).siblings('pre').attr('style','display:none');
    });
    $(document).load(function(){
        $("html,body").stop().animate({
            scrollTop: $("html").offset().top
        }, 'fast' );
    });

</script>

