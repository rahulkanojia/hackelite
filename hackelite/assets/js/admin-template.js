(function ($) {
	"use strict";

    $(document).ready(function () {

        function media_upload(button_class) {

            if(!wp.media){
                return;
            }
            if(!wp.media.editor){
                return;
            }

            var _custom_media = true,
                _orig_send_attachment = wp.media.editor.send.attachment;

            $('body').on('click', button_class, function(e) {
                var button_id ='#'+$(this).attr('id');
                var self = $(button_id);
                var send_attachment_bkp = wp.media.editor.send.attachment;
                var button = $(button_id);
                var id = button.attr('id').replace('_button', '');
                _custom_media = true;
                wp.media.editor.send.attachment = function(props, attachment){
                    if ( _custom_media  ) {
                        $('.custom_media_id').val(attachment.id);
                        $('.custom_media_url').val(attachment.url);
                        $('.custom_media_image').attr('src',attachment.url).css('display','block');
                    } else {
                        return _orig_send_attachment.apply( button_id, [props, attachment] );
                    }
                };
                wp.media.editor.open(button);
                return false;
            });
        }

        if($('.custom_media_button').length){
            media_upload('.custom_media_button');
        }

        $('body').on('change', '.social_resource select', function(){
            var value = $(this).val();
            if(value == '1'){
                $(this).closest('form').find('.custom_social').hide();
            }
            else{
                $(this).closest('form').find('.custom_social').fadeIn();
            }
        });

        if ($('.set_custom_images').length > 0) {

            var file_frame;

            $('.set_custom_images').click(function( event ){
                event.preventDefault();
                $('.set_custom_images').removeClass('active');
                $(this).addClass('active');
                // If the media frame already exists, reopen it.
                if ( file_frame ) {
                    // Open frame
                    file_frame.open();
                    return;
                }
                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select a image',
                    button: {
                        text: 'Use this image',
                    },
                    multiple: false	// Set to true to allow multiple files to be selected
                });
                // When an image is selected, run a callback.
                file_frame.on( 'select', function() {
                    var attachment = file_frame.state().get('selection').first().toJSON();
                    $('.set_custom_images.active').closest('.field-custom-bg-menu').find('input').val(attachment.url);
                });
                // Finally, open the modal
                file_frame.open();
            });

        }

        var inwave_sidebar_name2 = $('.cmb_id_inwave_sidebar_name_2');
        inwave_sidebar_name2.hide();
        $('select#page_template').change(function(){
           var value = $(this).val();
           var inwave_sidebar_position = $('.cmb_id_inwave_sidebar_position');
           var inwave_sidebar_name = $('.cmb_id_inwave_sidebar_name, .cmb_id_inwave_sidebar_sticky');
           var inwave_sidebar_name2 = $('.cmb_id_inwave_sidebar_name_2');
           var inwave_sidebar_title = $('.cmb_id_inwave_sidebar_options_title');
           var group_page_heading = $('.cmb_id_inwave_page_heading_options_title, .cmb_id_inwave_show_pageheading, .cmb_id_inwave_pageheading_bg, .cmb_id_inwave_page_title_bg_color, .cmb_id_inwave_breadcrumbs');
            if(value == 'page-templates/home-page.php'){
                group_page_heading.hide();
                inwave_sidebar_position.hide();
                inwave_sidebar_title.hide();
                inwave_sidebar_name.hide();
                inwave_sidebar_name2.hide();
            }else if(value == 'page-templates/full-width-page.php') {
                group_page_heading.show();
                inwave_sidebar_position.hide();
                inwave_sidebar_title.hide();
                inwave_sidebar_name.hide();
                inwave_sidebar_name2.hide();
            }else if(value == 'page-templates/two-sidebar-page.php'){
                group_page_heading.show();
                inwave_sidebar_title.show();
                inwave_sidebar_name.show();
                inwave_sidebar_name2.show();
            }else if(value == 'page-templates/left-sidebar.php' || value == 'page-templates/right-sidebar.php'){
                group_page_heading.show();
                inwave_sidebar_title.show();
                inwave_sidebar_name.show();
                inwave_sidebar_name2.hide();
            }else{
                group_page_heading.show();
                inwave_sidebar_title.show();
                inwave_sidebar_name.show();
                inwave_sidebar_name2.hide();
            }
        }).trigger('change');


    })
})(jQuery);

