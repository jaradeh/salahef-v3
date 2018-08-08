<?php

include_once('admin-form.php');

$form = new epico_AdminForm();
?>

<div class="ep-container theme-settings">
    <input type="hidden" name="action" value="theme_save_options" />
    <div id="ep-wrap">
        <?php echo $form->GetHeader(); ?>
        <div class="theme-settings-body clear-after">
            <?php echo $form->GetBody(); ?>
        </div>
    </div>
</div>