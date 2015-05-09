jQuery(document).ready(function($){
 
 
    var custom_uploader;
 
 
    $('#upload_image_button').click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#upload_image').val(attachment.url);
            $('#myimage').attr({src:attachment.url});
            $('#img-msg').css("display", "block");
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
 
 
});
//----------------------------------------------------
jQuery(document).ready(function ($) {
    var current_url = window.location;
    var loc = window.location.href,
            index = loc.indexOf('#');

    if (index > 0) {
        current_url = loc.substring(0, index);
    }
    var magic_url = current_url + '&jazzpopup_magic_data=1';
    console.log(magic_url);
    $('#magicsuggest').magicSuggest({
        data: magic_url ,
        ajaxConfig: {
            xhrFields: {
                withCredentials: true,
            }
        }
    });
//    jQuery( document ).tooltip({
//        content: function() {
//        var element = $( this );
//            if ( element.is( "[help]" ) ) {
//                return 'The Minimum width is 280px & Max is 500px';
//            }
//            if ( element.is( "[help1]" ) ) {
//                return 'The Minimum height of the Like Box is 130px';
//            }
//        }
//        
//    });
});


jQuery(document).ready(function ($) {
    
    $('#magicsuggest').magicSuggest({
        // [...] // configuration options
    });
});
//----------------------------------------------------
function wpjazzpopup_switchonoff(val) {
    var path = jQuery(val).attr("src");
    var file = path.split('/').pop();
    var file2 = path.split(file);
    console.log(file2[0]);
    var on = '';
    var off = '';
    if (file == 'on.png') {
        on = true;
    } else {
        off = true;
    }
    if (off)
    {
        jQuery.post('', {'wpjazzpopup_switchonoff': 1}, function (e) {
            if (e == 'error') {
                error('error');
            } else {
                jQuery('#jazz_circ').css("background", "#0f0");
                jQuery(val).attr("src", file2[0] + 'on.png');
            }
        });
    }
    if (on) {
        jQuery.post('', {'wpjazzpopup_switchonoff': 0}, function (e) {
            if (e == 'error') {
                error('error');
            } else {
                jQuery('#jazz_circ').css("background", "#f00");
                jQuery(val).attr("src", file2[0] + 'off.png');
            }
        });
    }
    //alert(val);
}
//----------------------------------------------------

//        jQuery(document).ready(function () {
//            jQuery("input[type=checkbox]").switchButton({
//                width: 80,
//                height: 30,
//                on_label: 'ON',
//                off_label: 'OFF',
//                button_width: 40
//            });
//        });
        function get_tinymce_content() {
            if (jQuery("#wp-content-wrap").hasClass("tmce-active")) {
                return tinyMCE.activeEditor.getContent();
            } else {
                return jQuery('#content').val();
            }
        }
        jQuery(document).ready(function () {
            jQuery('button[data-reveal-id]').live('click', function (e) {

                var remove = '';
                var animation = '';
                var position = '';
                var content = '';
                var tmp_image = jQuery('#upload_image').val();
                var tmp_video = jQuery('#textarea_data').val();
                var tmp_remove = jQuery('#wp-remove').val()
                var tmp_background = jQuery('#wp-background').val();
                var tmp_animation = jQuery('#wp-animation').val();
                var tmp_width = jQuery('#wp-width').val();
                var position = jQuery('#wp-position').val();

                if (jQuery("#text").is(':checked')) {
    //                    var iframe = document.getElementById('content_ifr');
    //                    console.log(iframe);
    //                    var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
    //                    var con = innerDoc.getElementById('tinymce').innerHTML;
                    // console.log(get_tinymce_content());
                    content = get_tinymce_content();
                }
                if (jQuery("#image").is(':checked')) {
                    content = tmp_image;
                }
                if (jQuery("#video").is(':checked')) {
                    content = tmp_video;
                    tmp_background = 'none';
                }
                if (jQuery("#all").is(':checked')) {
                    content = get_tinymce_content();
                }
                jQuery('#test-popup').html(content);
                if (tmp_remove == 1)
                {
                    remove = false;
                } else {
                    remove = true;
                }
                if (tmp_animation == 1) {
                    animation = 'mfp-zoom-in';
                }
                if (tmp_animation == 2) {
                    animation = 'mfp-newspaper';
                }
                if (tmp_animation == 3) {
                    animation = 'mfp-move-horizontal';
                }
                if (tmp_animation == 4) {
                    animation = 'mfp-move-from-top';
                }
                if (tmp_animation == 5) {
                    animation = 'mfp-3d-unfold';
                }
                if (tmp_animation == 6) {
                    animation = 'mfp-zoom-out';
                }

                jQuery('#test-popup').css('background', tmp_background);
                jQuery('#test-popup').css('max-width', tmp_width+'px');
                console.log(content);
                if (content == null || content == '' || content == '<p><br data-mce-bogus="1"></p>' || content == 'http://') {
                    alert('There is no content to display');
                    return;
                }
                if (jQuery("#image").is(':checked')) {
                    console.log(content);
                    jQuery.jazzPopup.open({
                        items: {src: content},
                        type: "image",
                        removalDelay: 500,
                        closeOnBgClick: remove,
                        callbacks: {
                            beforeOpen: function () {
                                this.st.image.markup = this.st.image.markup.replace("mfp-figure", "mfp-figure mfp-with-anim");
                                this.st.mainClass = animation;
                            }}
                    });
                } else {
                    jQuery.jazzPopup.open({
                        items: {src: "#test-popup"},
                        type: "inline",
                        removalDelay: 500,
                        closeOnBgClick: remove,
                        callbacks: {
                            beforeOpen: function () {
                                this.st.image.markup = this.st.image.markup.replace("mfp-figure", "mfp-figure mfp-with-anim");
                                this.st.mainClass = animation;
                            }}
                    });
                }
            });
        })
        jQuery(document).ready(function () {
            jQuery('.btn-file :file').on('fileselect', function (event, numFiles, label) {

                var input = jQuery(this).parents('.input-group').find(':text'),
                        log = numFiles > 1 ? numFiles + ' files selected' : label;

                if (input.length) {
                    input.val(log);
                } else {
                    if (log)
                        alert(log);
                }

            });
        });


 