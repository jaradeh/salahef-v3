jQuery(document).ready(function () {
    if (jQuery.fn.editTable) {
        jQuery('.ct_single_size_table:not(.template) .ct_edit_table').each(function () {
            jQuery(this).editTable();
        });
    }
    if (jQuery.fn.wpColorPicker) {
        jQuery('.ct-sg-color').wpColorPicker();
    }

    // add table btn
    jQuery('button.ct-addTable').click(function () {

        var textareas = jQuery('textarea.ct_edit_table');
        var tables = jQuery('table.inputtable, table.wh');

        var copy = textareas
            .last()
            .clone()
            .attr('name', 'ct_size_guide[' + textareas.length + '][table]')
            .insertAfter(tables.last());

        var textAbove = jQuery('[id^=wp-size_table_caption]')
            .last()
            .clone()
            .attr('name', 'wp-size_table_caption' + textareas.length + 'wrap')
            .insertAfter(tables.last());

        textAbove.find('textarea')
            .attr('name', 'ct_size_guide[' + textareas.length + '][caption]')

        var titleAbove = jQuery('p.sg-sizeGuide-title-above')
            .last()
            .clone()
            .insertAfter(tables.last());

        copy.editTable();

    })

    // del table btn
    jQuery('button.ct-delTable').click(function () {
        var textareas = jQuery('textarea.ct_edit_table');
        var tables = jQuery('table.inputtable, table.wh');
        if (tables.length > 1 && textareas.length > 1) {
            tables.last().remove();
            textareas.last().remove();
            jQuery('[id^=wp-size_table_caption' + ( textareas.length - 1 ) +']').remove();
            jQuery('p.sg-sizeGuide-title-above').last().remove();
        }
    })

});