/*
* Admin Script
*/
function add_image(obj) {
    var parent=jQuery(obj).parent().parent('div.field_row');
    var inputField = jQuery(parent).find("input.meta_image_url");

    tb_show('', 'media-upload.php?TB_iframe=true');

    window.send_to_editor = function(html) {
        var url = jQuery(html).find('img').attr('src');
        inputField.val(url);
        jQuery(parent)
        .find("div.image_wrap")
        .html('<img src="'+url+'" height="48" width="48" />');

        // inputField.closest('p').prev('.awdMetaImage').html('<img height=120 width=120 src="'+url+'"/><p>URL: '+ url + '</p>');

        tb_remove();
    };

    return false;
}
jQuery(function($) {

    // Add Color Picker to all inputs that have 'color-field' class
    $( '.aft-color-picker' ).wpColorPicker();
    $(".aft_radio_select").click(function(){
        $(".aft_radio_select").removeClass("aft_selected");
        $(this).addClass("aft_selected");
    });
});
