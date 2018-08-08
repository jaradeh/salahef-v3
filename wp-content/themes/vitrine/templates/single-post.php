<div class="row">
    <div class="span8">
        <?php
        $format = get_post_format();

        if ( false === $format )
            $format = 'standard';

        get_template_part( 'templates/single', "post-$format" );
        ?>
    </div>
    <div class="span3 offset1">
        <?php epico_get_sidebar(); ?>
    </div>
</div>