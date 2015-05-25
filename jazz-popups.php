<?php
/*
  Plugin Name: Jazz Popups
  Description: Jazz Popups allow you to add special announcement, message or offers in form of text, image and video.
  Author: <a href="http://crudlab.com/">CRUDLab</a>
  Version: 1.7.0
 */
require_once( ABSPATH . "wp-includes/pluggable.php" );
add_action('admin_menu', 'test_plugin_setup_menu');
//register_uninstall_hook( __FILE__, 'uninstall_hook');
register_deactivation_hook(__FILE__, 'uninstall_hook');

function jazzpopup_box_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=jazz-plugin&edit=1">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'jazzpopup_box_settings_link' );

if ( ! function_exists ( 'jazzpopup_links' ) ) {
	function jazzpopup_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			if ( ! is_network_admin() )
				$links[] = '<a href="admin.php?page=jazz-plugin&edit=1">' . __( 'Settings') . '</a>';
                                $links[] = '<a href="https://wordpress.org/plugins/jazz-popups/faq/" target="_blank">' . __( 'FAQ' ) . '</a>';
                                $links[] = '<a href="https://crudlab.com/submit-ticket/" target="_blank">' . __( 'Support' ) . '</a>';
		}
		return $links;
	}
}
add_filter( 'plugin_row_meta', 'jazzpopup_links', 10, 2 );

function uninstall_hook() {
    global $wpdb;
    $thetable = $wpdb->prefix . "notify";
    //Delete any options that's stored also?
    $wpdb->query("DROP TABLE IF EXISTS $thetable");
}

function test_plugin_setup_menu() {
    global $wpdb;
    $table = $wpdb->prefix . 'notify';
    $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    if ($myrows[0]->status == 0) {
        add_menu_page('Jazz Popups', 'Jazz Popups <span id="jazz_circ" class="update-plugins count-1" style="background:#F00"><span class="plugin-count">&nbsp&nbsp</span></span>', 'manage_options', 'jazz-plugin', 'test_init', plugins_url("/images/ico.png", __FILE__));
    } else {
        add_menu_page('Jazz Popups', 'Jazz Popups <span id="jazz_circ" class="update-plugins count-1" style="background:#0F0"><span class="plugin-count">&nbsp&nbsp</span></span>', 'manage_options', 'jazz-plugin', 'test_init', plugins_url("/images/ico.png", __FILE__));
    }
}

//add_filter('the_content', 'lightbox');
add_filter('wp_footer', 'lightbox', 100);

function abwb() {
    wp_register_style('jazz_css', plugins_url('/jazz-popup/jazz-popup_new.css', __FILE__));
    wp_enqueue_style('jazz_css');
    wp_enqueue_script('jquery-ui-core', array('jquery'));
    wp_enqueue_script('pluginscript1', plugins_url('/jazz-popup/jquery.jazz-popup.js', __FILE__), array('jquery'));
    //wp_enqueue_script('pluginscript2', plugins_url('/jazz-popup/jquery.jazz-popup.min.js', __FILE__), array('jquery'));
    wp_enqueue_script('pluginscript3', plugins_url('/js/customcookie.js', __FILE__), array('jquery'));
}

add_action('wp_enqueue_scripts', 'abwb');

function lightbox() {
    
    $content = '';
    $post_id = get_the_ID();
    global $wpdb;
    $table = $wpdb->prefix . 'notify';
    $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    $data = $myrows[0]->data;
    $type = $myrows[0]->type;
    $status = $myrows[0]->status;
    $display = $myrows[0]->display;
    $user = $myrows[0]->user;
    $when_display = $myrows[0]->when_display;
    $except_ids = $myrows[0]->except_ids;
    $seconds = $myrows[0]->time;
    $str = $content;
    $width = $myrows[0]->width . 'px';
    $time_query = ($seconds > 0) ? ' setTimeout( "jQuery.jazzPopup.close()",' . ($seconds * 1000) . ' ); ' : '';
    if ($status == 0) {
        $str = $content;
    } else {
        if ($type == 1) {
            $type = 'image';
            if ($myrows[0]->width > 0) {
                echo '<style>img.mfp-img{width:' . $width . ';}</style>';
            }
        } else {
            $type = 'inline';
    }}
        $background = $myrows[0]->bg_color;
        if ($myrows[0]->type == 2) {
            $background = 'none';
        }

        $position = 'z-index: 9999999;position: fixed;';
        $animation = 'none';
        $remove = 'false';
        if ($myrows[0]->position == 1) {
            $position = 'z-index: 9999999;position: fixed;top: 0;';
        }
        if ($myrows[0]->position == 2) {
            $position = 'z-index: 9999999;position: fixed;bottom: 0;top:auto';
        }
        if ($myrows[0]->animation == 1) {
            $animation = 'mfp-zoom-in';
        }
        if ($myrows[0]->animation == 2) {
            $animation = 'mfp-newspaper';
        }
        if ($myrows[0]->animation == 3) {
            $animation = 'mfp-move-horizontal';
        }
        if ($myrows[0]->animation == 4) {
            $animation = 'mfp-move-from-top';
        }
        if ($myrows[0]->animation == 5) {
            $animation = 'mfp-3d-unfold';
        }
        if ($myrows[0]->animation == 6) {
            $animation = 'mfp-zoom-out';
        }

        if ($myrows[0]->remove == 2 || $myrows[0]->remove == 3) {
            $remove = 'true';
        }
		$padding = '';
		if($type == 'image' || $type == 2){
			$padding = 'padding:0 !important;';
		}

        $lightbox = '<div id="test-popup" class="white-popup mfp-with-anim mfp-hide" style="'.$padding.' background:' . $background . '; max-width:' . $width . '">' . $data . '<img class="jazzclosebutton" src="' . plugins_url("/images/crox.png", __FILE__) . '" onclick="jQuery.jazzPopup.close();"></div>';
        if ($type != 'image') {
            $data = '#test-popup';
        }
        if ($when_display == 0) {

            $lightbox .= '<script>jQuery(document).ready(function () {jQuery.jazzPopup.open({ items: { src: "' . $data . '", crox: "' . plugins_url("/images/crox.png", __FILE__) . '" }, type: "' . $type . '", removalDelay: 500,closeOnBgClick: ' . $remove . ', callbacks: {beforeOpen: function() {this.st.image.markup = this.st.image.markup.replace("mfp-figure", "mfp-figure mfp-with-anim");this.st.mainClass = "' . $animation . '" ;}}  }); ' . $time_query . '  });</script>';
        } else {
            $lightbox .= '<script>jQuery(document).ready(function () {if(checkCookie()){jQuery.jazzPopup.open({ items: { src: "' . $data . '" }, type: "' . $type . '", removalDelay: 500,closeOnBgClick: ' . $remove . ', callbacks: {beforeOpen: function() {this.st.image.markup = this.st.image.markup.replace("mfp-figure", "mfp-figure mfp-with-anim");this.st.mainClass = "' . $animation . '" ;}}  }); ' . $time_query . ' }})</script>';
        }
        
        if ($status == 0) {
        $str = $content;
    } else {
        if ($content == NULL) {
            //$str = $lightbox;
        }
        if (($user == 0 && is_user_logged_in()) || ($user == 1 && !is_user_logged_in()) || $user == 2) {
            
            if ($display & 2) {
                if (is_page() && !defined('is_front_page')) {
                    $str = $content . $lightbox;
                }
            }
            if ($display & 1) {
                if (is_front_page()) {
                    $str = $content . $lightbox;
                }
            }
            if ($display & 4) {
                if (is_single()) {
                    $str = $content . $lightbox;
                }
            }
            if ($display & 8) {
                //$str = $content . $fb;
            }
        }
    }
    $except_check = true;
    if ($display & 8) {
        @$expect_ids_arrays = split(',', $except_ids);
        foreach ($expect_ids_arrays as $id) {
            if (trim($id) == $post_id) {
                $except_check = false;
            }
        }
    }
    
   
    if ($except_check) {
        echo $str;
    } else {
        echo $content;
    }
        
        

//        if ($user == 1) {
//            if ($display == 3 && is_user_logged_in()) {
//                $str = $content . $lightbox;
//            }
//            if ($display == 2 && is_user_logged_in()) {
//                if (is_front_page()) {
//                    $str = $content . $lightbox;
//                }
//            }
//            if ($display == 1 && is_user_logged_in()) {
//                if (is_single()) {
//                    $str = $content . $lightbox;
//                }
//            }
//            if ($display == 4 && is_user_logged_in()) {
//                $str = $content . $lightbox;
//            }
//        }
//        if ($user == 2) {
//            if ($display == 3 && !is_user_logged_in()) {
//                $str = $content . $lightbox;
//            }
//            if ($display == 2 && !is_user_logged_in()) {
//                if (is_front_page()) {
//                    $str = $content . $lightbox;
//                }
//            }
//            if ($display == 1 && !is_user_logged_in()) {
//                if (is_single()) {
//                    $str = $content . $lightbox;
//                }
//            }
//            if ($display == 4 && is_user_logged_in()) {
//                $str = $content . $lightbox;
//            }
//        }
//        if ($user == 3) {
//            if ($display == 3) {
//                $str = $content . $lightbox;
//            }
//            if ($display == 2) {
//                if (is_front_page()) {
//                    $str = $content . $lightbox;
//                }
//            }
//            if ($display == 1) {
//                if (is_single()) {
//                    $str = $content . $lightbox;
//                }
//            }
//            if ($display == 4 && is_user_logged_in()) {
//                $str = $content . $lightbox;
//            }
//        }
//    }
//    $except_check = true;
//    if ($display == 4) {
//        @$expect_ids_arrays = split(',', $except_ids);
//        foreach ($expect_ids_arrays as $id) {
//            if (trim($id) == $post_id) {
//                $except_check = false;
//            }
//        }
//    }
//    if ($except_check) {
//        echo $str;
//    }
}

if (isset($_REQUEST['jazzpopup_magic_data'])) {
    $data = '';
    $args = array(
        'post_type' => 'any',
        'post_status' => 'publish',
        'posts_per_page' => -1
    );
    $posts = get_posts($args);
    foreach ($posts as $post) {
        $data[] = array('id' => $post->ID, 'name' => $post->post_title);
    }

    echo json_encode($data);
    exit();
}

if (isset($_REQUEST['notify_save'])) {
//    print_r($_REQUEST);
//    die;
    global $wpdb;
    $type = '';
    $radio_value = sanitize_text_field($_REQUEST['notify']);
    $status = 1;
    $display = sanitize_text_field($_REQUEST['display']);
    $user = sanitize_text_field($_REQUEST['user']);
    $bg_color = sanitize_text_field($_REQUEST['bg_color']);
    $width = sanitize_text_field($_REQUEST['width']);
    $position = sanitize_text_field($_REQUEST['position']);
    $animation = sanitize_text_field($_REQUEST['animation']);
    $when_display = sanitize_text_field($_REQUEST['when_display']);
    $remove = sanitize_text_field($_REQUEST['remove']);
    $ul = '0';
    global $current_user;
    get_currentuserinfo();
    if (isset($current_user)) {
        $ul = $current_user->ID;
    }
    $user_id = $ul;
    if ($radio_value == 'text') {
        $type = 0;
    }
    if ($radio_value == 'image') {
        $type = 1;
    }
    if ($radio_value == 'video') {
        $type = 2;
    }
    if ($radio_value == 'all') {
        $type = 3;
    }
    $data = ($_REQUEST['content']);
    $table = $wpdb->prefix . 'notify';
    $data1 = array(
        'type' => $type,
        'radio_value' => $radio_value,
        'created' => current_time('mysql'),
        'data' => $data,
        'status' => $status,
        'display' => $display,
        'user' => $user,
        'bg_color' => $bg_color,
        'width' => $width,
        'position' => $position,
        'animation' => $animation,
        'when_display' => $when_display,
        'remove' => $remove,
        'user_id' => $user_id
    );
    $wpdb->insert($table, $data1);
    $lastid = $wpdb->insert_id;
    header('Location:' . $_SERVER['PHP_SELF'] . '?page=jazz-plugin&edit=' . $lastid);
}

if (isset($_REQUEST['notify_update'])) {
    global $wpdb;
    $type = '';
    $display = $_REQUEST['display'];
    $display_val = 0;
    foreach ($display as $d) {
        $display_val += @sanitize_text_field($d);
    }
    $except_ids = (isset($_REQUEST['except_ids'])) ? $_REQUEST['except_ids'] : '';
    if ($except_ids != NULL) {
        $except_ids = implode(', ', $except_ids);
    }
    $radio_value = @sanitize_text_field($_REQUEST['notify']);
    $user = @sanitize_text_field($_REQUEST['user']);
    $bg_color = @sanitize_text_field($_REQUEST['bg_color']);
    $width = @sanitize_text_field($_REQUEST['width']);
    $position = @sanitize_text_field($_REQUEST['position']);
    $animation = @sanitize_text_field($_REQUEST['animation']);
    $when_display = @sanitize_text_field($_REQUEST['when_display']);
    $remove = @sanitize_text_field($_REQUEST['remove']);
    $edit_id = @sanitize_text_field($_REQUEST['update_id']);
    $seconds = @sanitize_text_field($_REQUEST['seconds']);
    $ul = '0';
    global $current_user;
    get_currentuserinfo();
    if (isset($current_user)) {
        $ul = $current_user->ID;
    }
    $user_id = $ul;
    if ($radio_value == 'text') {
        $type = 0;
    }
    if ($radio_value == 'image') {
        $type = 1;
    }
    if ($radio_value == 'video') {
        $type = 2;
    }
    if ($radio_value == 'all') {
        $type = 3;
    }
    $data = ($_REQUEST['content']);
    $data = stripcslashes($data);
    $table = $wpdb->prefix . 'notify';
	if($width < 1){$width = 500;}
    $data1 = array(
        'type' => $type,
        'radio_value' => $radio_value,
        'data' => $data,
        'display' => $display_val,
        'user' => $user,
        'bg_color' => $bg_color,
        'width' => $width,
        'position' => $position,
        'animation' => $animation,
        'when_display' => $when_display,
        'remove' => $remove,
        'user_id' => $user_id,
        'time' => $seconds,
        'except_ids' => $except_ids
    );
    $v = $wpdb->update($table, $data1, array('id' => $edit_id));
    header('Location:' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
}

if (isset($_REQUEST['wpjazzpopup_switchonoff'])) {
    global $wpdb;
    $val = $_REQUEST['wpjazzpopup_switchonoff'];
    $data = array(
        'status' => $val
    );
    $table = $wpdb->prefix . 'notify';
    if ($wpdb->update($table, $data, array('id' => 1))) {
        echo $val;
    } else {
        echo 'error';
    };
    die;
}

if (isset($_REQUEST['trash_announce_id'])) {
    $id = $_REQUEST['trash_announce_id'];
    $data = array(
        'status' => 0
    );
    $table = $wpdb->prefix . 'notify';
    $wpdb->update($table, $data, array('id' => $id));
    header('Location:' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
}

if (isset($_REQUEST['restore_announce_id'])) {
    $id = $_REQUEST['restore_announce_id'];
    $data = array(
        'status' => 1
    );
    $table = $wpdb->prefix . 'notify';
    $wpdb->update($table, $data, array('id' => $id));
    header('Location:' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
}
if (isset($_REQUEST['delete_announce_id'])) {
    $id = $_REQUEST['delete_announce_id'];
    $table = $wpdb->prefix . 'notify';
    $wpdb->delete($table, array('id' => $id));
    header('Location:' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
}
if (isset($_REQUEST['publish_announce_id'])) {
    $id = $_REQUEST['publish_announce_id'];
    $data = array('active' => 0);
    $table = $wpdb->prefix . 'notify';
    $wpdb->update($table, $data, array('active' => 1));
    $data1 = array('active' => 1);
    $wpdb->update($table, $data1, array('id' => $id));
    header('Location:' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
}

function test_init() {
    if (!isset($_REQUEST['edit'])) {
        echo '<script>location = location+"&edit=1"</script>';
    }
    global $wpdb;
    add_filter('admin_head', 'ShowTinyMCE');
    $check = array();
    $setting = array('media_buttons' => false);
    $table = $wpdb->prefix . 'notify';
    if (!isset($_REQUEST['edit'])) {
        header('Location:' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '&edit=1');
    }
    if (!(isset($_REQUEST['new']) || isset($_REQUEST['edit']))) {
        $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");
    } else if (isset($_REQUEST['edit'])) {
        $edit_id = $_REQUEST['edit'];
        $str = "SELECT * FROM $table WHERE id = 1";
        $myrows = $wpdb->get_results($str);
    }
    $data = '';
    $data_array = array();
    $user[$myrows[0]->user] = ' selected="selected"';
    $position[$myrows[0]->position] = ' selected="selected"';
    $animation[$myrows[0]->animation] = ' selected="selected"';
    $remove[$myrows[0]->remove] = ' selected="selected"';
    $when_display[$myrows[0]->when_display] = ' selected="selected"';
    if ($myrows != NULL && !isset($_REQUEST['notify'])) {
        $_REQUEST['notify'] = $myrows[0]->radio_value;
    }
    if ($myrows != NULL) {
        $data_array[$myrows[0]->type] = $myrows[0]->data;
    }
    if ($_REQUEST['notify'] == 'text') {
        $check[0] = 'checked';
        $data = @$data_array['0'];
        $setting = array('media_buttons' => false);
    }
    if ($_REQUEST['notify'] == 'image') {
        $check[1] = 'checked';
    }
    if ($_REQUEST['notify'] == 'video') {
        $check[2] = 'checked';
    }
    if ($_REQUEST['notify'] == 'all') {
        $check[3] = 'checked';
        $data = @$data_array['3'];
        $setting = array('media_buttons' => true);
    }
    if ($myrows[0]->display & 1) {
        $display[1] = 'checked';
    };
    if ($myrows[0]->display & 2) {
        $display[2] = 'checked';
    };
    if ($myrows[0]->display & 4) {
        $display[4] = 'checked';
    };
    if ($myrows[0]->display & 8) {
        $display[8] = 'checked';
    };
    ?>
    <div id="test-popup" class="white-popup mfp-with-anim mfp-hide"></div>
    
        <form method="post" action=""  enctype="multipart/form-data"> 
        <div class="container">
        <div class="row">
            <div class="plugin-wrap col-md-12">
                <div class="plugin-notify">
                    <div class="forms-wrap">
                        <div class="colmain">
                            <div class="what">
                                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                                    <?php if (isset($_REQUEST['new'])) { ?>
                                        <input type="hidden" name="new" value="<?php echo $_REQUEST['new']; ?>">
                                    <?php } else if (isset($_REQUEST['edit'])) { ?>
                                        <input type="hidden" name="edit" value="<?php echo $_REQUEST['edit']; ?>">
                                    <?php } ?>
                                        
                                    <div class="control-group where">
                                        <label for="radios" class="control-label">What would you like to display?</label>
                                        <div class="controls">
                                            <div class="radios-wrap">
                                                <label for="text" class="radio inline">
                                                    <input type="radio" id="text"  value="text" <?php echo @$check[0]; ?> onchange="if(this.checked){location='?page=jazz-plugin&edit=1&notify=text';}" name="notify">
                                                    Text
                                                </label>
                                            </div>
                                            <div class="radios-wrap">
                                                <label for="image" class="radio inline">
                                                    <input type="radio" id="image"  value="image" <?php echo @$check[1]; ?> onchange="if(this.checked){location='?page=jazz-plugin&edit=1&notify=image';}" name="notify">
                                                    Image
                                                </label>
                                            </div>
                                            <div class="radios-wrap">
                                                <label for="video" class="radio inline">
                                                    <input type="radio" id="video" value="video" <?php echo @$check[2]; ?> onchange="if(this.checked){location='?page=jazz-plugin&edit=1&notify=video';}" name="notify">
                                                    Video
                                                </label>
                                            </div>
                                            <div class="radios-wrap">
                                                <label for="all" class="radio inline">
                                                    <input type="radio" id="all" value="all" <?php echo @$check[3]; ?> onchange="if(this.checked){location='?page=jazz-plugin&edit=1&notify=all';}" name="notify">
                                                    All
                                                </label>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                <div class="form-types-wrap">
                                    <?php /*
                                    if (!isset($_REQUEST['new'])) {
                                        ?>
                                        <form method="get" action="">
                                            <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                                            <input type="hidden" name="new" value="<?php echo $_REQUEST['new']; ?>">
                                            <!--<button  type="submit" class="btn btn-info" >Create New</button>-->
                                        </form>
                                        <?php
                                    } */
                                    
                                    if ($_REQUEST['notify'] == 'text' || $_REQUEST['notify'] == 'all') {
                                        ?>
                                        
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Write Text you want to add</h3>
                                                </div>

                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <?php
                                                        wp_editor($data, 'content', $setting);
                                                        ?>
                                                    </div>
                                                    <div class="form-group pull-left buttons">
                                                        <?php
                                                        if ($myrows[0]->id > 0) {
                                                            ?>
                                                            <input type="hidden" name="update_id" value="<?php echo $myrows[0]->id; ?>">
                                                            
                                                            <button  name="notify_update" type="submit" class="btn btn-success">Update</button>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <button  name="notify_save" type="submit" class="btn btn-success">Save</button>
                                                        <?php } ?>
                                                        <button  type="button" data-reveal-id="myModal" class="btn btn-primary">Preview</button>
                                                    </div>
                                                    <div class="wpfblbox_form-group wpfblbox_switch" style="float: right;">
        <?php
        $img = '';
        if ($myrows[0]->status == 0) {
            $img = 'off.png';
        } else {
            $img = 'on.png';
        }
        ?>
                                                                <img onclick="wpjazzpopup_switchonoff(this)" src="<?php echo plugins_url('/images/' . $img, __FILE__); ?>"> 
                                                            </div>
                                                </div>

                                            </div>
                                            <?php
                                        }if ($_REQUEST['notify'] == 'image') {
                                            if (@$data_array[1] == NULL) {
                                                $value = 'http://';
                                                $image = '';
                                            } else {
                                                $value = $data_array[1];
                                                $image = '<div class"col-md-2 pull-right" style="margin-top:15px; margin-bottom:15px;"><image style="width:150px" src = "' . $value . '"></div>';
                                            }
                                            ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title">Add image you want to display</h3>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col" style="width:100%">
                                                            <label for="">Upload Image</label>
        <?php
        echo '<div class"col-md-2 pull-right" style="margin-top:15px; margin-bottom:15px;"><image id="myimage" style="width:150px" src = "' . $value . '" onError="this.onerror=null;this.src=\'' . plugins_url("/images/no-image.png", __FILE__) . '\'; "></div>';
        ?>
                                                            <div class="input-group">
                                                                <span class="input-group-btn" style="float:left;">
                                                                    <input id="upload_image_button" class="btn btn-primary" type="button" value="Browse" />
                                                                </span>
                                                                <input id="upload_image" style="float:left; width: 75%; margin-left: -3px; display: none;" type="text" size="36" class="form-control" name="content" onchange="jQuery('#myimage').attr({src: this.value})" value="<?php echo $value; ?>" /> 
                                                            </div>
                                                            <br>
                                                            <br>
                                                            <div class="form-group pull-left buttons">
                                                                <?php
                                                                if ($myrows[0]->id > 0) {
                                                                    ?>
                                                                    <input type="hidden" name="update_id" value="<?php echo $myrows[0]->id; ?>">
                                                                    <button  name="notify_update" type="submit" class="btn btn-success">Update</button>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <button  name="notify_save" type="submit" class="btn btn-success">Save</button>
        <?php } ?>
                                                                <button data-reveal-id="myModal" type="button" class="btn btn-primary">Preview</button>
                                                                <span id="img-msg" style="display:none;margin-top: 7px;color: #f00;"> Don't forget to press update button to save image.</span>
                                                            </div>
                                                            <div class="wpfblbox_form-group wpfblbox_switch" style="float: right;">
        <?php
        $img = '';
        if ($myrows[0]->status == 0) {
            $img = 'off.png';
        } else {
            $img = 'on.png';
        }
        ?>
                                                                <img onclick="wpjazzpopup_switchonoff(this)" src="<?php echo plugins_url('/images/' . $img, __FILE__); ?>"> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
    <?php }if ($_REQUEST['notify'] == 'video') {
        ?>
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title">Add video embed code</h3>
                                                        </div>

                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <label for="email">Video</label>
                                                                <textarea id="textarea_data" class="form-control" name="content" style="height: 75px;"><?php echo @$data_array[2]; ?></textarea>
                                                            </div>
                                                            <div class="form-group pull-left buttons">
                                                                <?php
                                                                if (@$myrows[0]->id > 0) {
                                                                    ?>
                                                                    <input type="hidden" name="update_id" value="<?php echo @$myrows[0]->id; ?>">
                                                                    <button  name="notify_update" type="submit" class="btn btn-success">Update</button>
            <?php
        } else {
            ?>
                                                                    <button  name="notify_save" type="submit" class="btn btn-success">Save</button>
                                                                <?php } ?>
                                                                <button data-reveal-id="myModal" type="button"  class="btn btn-primary">Preview</button>
                                                            </div>


                                                            <div class="wpfblbox_form-group wpfblbox_switch" style="float: right;">
        <?php
        $img = '';
        if ($myrows[0]->status == 0) {
            $img = 'off.png';
        } else {
            $img = 'on.png';
        }
        ?>
                                                                <img onclick="wpjazzpopup_switchonoff(this)" src="<?php echo plugins_url('/images/' . $img, __FILE__); ?>"> 
                                                            </div>


                                                        </div>

                                                    </div>
                                                <?php }
                                                ?>
    <?php
    $notify = '';
    if (isset($_REQUEST['notify'])) {
        $notify = $_REQUEST['notify'];
    } else {
        $notify = $myrows[0]->radio_value;
    }
    ?>
                                                <input type="hidden" name="notify" value="<?php echo $notify; ?>">
												<div class="control-group where" style="margin-top:5px;">
												<ul>
                                                <li>If you enjoy our plugin, please give it 5 stars. [<a href="https://wordpress.org/plugins/jazz-popups/" target="_blank">Rate the plugin</a>]
                                                </li><li>
												If you're having issue, please submit ticket. [<a href="https://crudlab.com/submit-ticket/" target="_blank">Submit Ticket</a>]
                                                </li></ul>
												<div class="clearfix"></div>
                                                </div>
												</div>
                                                </div>
                                                </div>
                                                <div class="col">
                                                    <div class="where" style="margin-bottom:7px;">
                                                        <a href="https://www.2checkout.com/checkout/purchase?sid=102444448&quantity=1&product_id=1">
                                                            <img style="width:100%;" src="<?php echo plugins_url('/images/donate.jpg', __FILE__); ?>"> 
                                                        </a>
                                                    </div>
                                                    <div class="where">
                                                            <div class="control-group">
                                                                <label class="control-label">Settings</label>
                                                                
                                                                
                                                                <tr>
                                                <td style="width: 160px; vertical-align: top;text-align: right; padding-right: 15px;">
                                                    <label style="margin-top:8px;">Where to display? </label>
                                                </td>
                                                <td>
                                                    <div class="wpfblbox_form-group">
                                                        <input type="checkbox" id="display1" name="display[]" <?php echo @$display['1']; ?> value="1" class="wpfblbox_form-control wpfblbox_check" style="float:left; margin-top:1px"><label for="display1">Homepage</label>
                                                        <input type="checkbox" id="display2" name="display[]" <?php echo @$display['2']; ?> value="2" class="wpfblbox_form-control wpfblbox_check" style="float:left; margin-top:1px"><label for="display2">All pages</label>
                                                        <input type="checkbox" id="display4" name="display[]" <?php echo @$display['4']; ?> value="4" class="wpfblbox_form-control wpfblbox_check" style="float:left; margin-top:1px"><label for="display4">All posts</label>
                                                        <input type="checkbox" id="display8" onchange="if (this.checked) {
                                                                        jQuery('.wpfblbox_exclude').show(200)
                                                                    } else {
                                                                        jQuery('.wpfblbox_exclude').hide(200)
                                                                    }" name="display[]" <?php echo @$display['8']; ?> value="8" class="wpfblbox_form-control wpfblbox_check" style="float:left; margin-top:1px"><label for="display8">Exclude following pages and posts</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: top;width: 160px; padding-top: 10px;text-align: right; padding-right: 15px;">
                                                    <label class="wpfblbox_exclude" style="display:<?php
                                                    if ($myrows[0]->display & 8) {
                                                        echo 'block';
                                                    } else {
                                                        echo 'none';
                                                    }
                                                    ?>">Exclude Page/Post</label>
                                                </td>
                                                <td>
                                                    <div class="wpfblbox_form-group wpfblbox_exclude" style="display:<?php
                                                    if ($myrows[0]->display & 8) {
                                                        echo 'block';
                                                    } else {
                                                        echo 'none';
                                                    }
                                                    ?>">
                                                        <div id="magicsuggest" value="[<?php echo $myrows[0]->except_ids; ?>]" name="except_ids[]" style="width:auto !important; background: #fff; border: thin solid #cccccc;"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                                                
                                                                
                                                                <div class="field-wrap">
                                                                    <label>Logged in or Non Logged in user: </label>
                                                                    <div class="form-group">
                                                                        <select class="form-control" name="user">
                                                                            <option <?php echo @$user[0]; ?> value="0">Logged In</option>
                                                                            <option <?php echo @$user[1]; ?> value="1">Non Logged In</option>
                                                                            <option <?php echo @$user[2]; ?> value="2">Both</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="field-wrap">


                                                                    <div class="button appearance" type="button" onclick="jQuery('#collapseExample').slideToggle(200);" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                                        Appearance

                                                                        <div class="clearfix"></div>
                                                                    </div>
                                                                    <div class="collapse" id="collapseExample">
                                                                        <div class="well">
                                                                            <div class="inner-field">
                                                                                <label>Background Color:</label>
                                                                                <input id="wp-background" name="bg_color" value="<?php echo $myrows[0]->bg_color; ?>" type="color" class="form-control" style="cursor:pointer">
                                                                            </div>
    <?php /* ?><div class="inner-field">
      <label>Announcement Position:</label>
      <select class="form-control" name="position" id="wp-position">
      <option <?php echo $position[1]; ?> value="1">Top</option>
      <option <?php echo $position[2]; ?> value="2">Bottom</option>
      <option <?php echo $position[3]; ?> value="3">Center</option>
      </select>
      </div> <?php */ ?>
                                                                        </div>
                                                                    </div>

                                                                </div>
																
																
                                                                            <div class="inner-field">
                                                                                <label>Popup Width:</label>
                                                                                <input id="wp-width" name="width" value="<?php echo $myrows[0]->width; ?>" type="text" class="form-control">
                                                                            </div>
																
                                                                <div class="field-wrap">
																
                                                                    
                                                                 <label>Animation: </label>
                                                                    <div class="form-group">
                                                                        <select class="form-control" name="animation" id="wp-animation">
                                                                            <option <?php echo @$animation[0]; ?> value="0">None</option>
                                                                            <option <?php echo @$animation[1]; ?> value="1">Zoom</option>
                                                                            <option <?php echo @$animation[2]; ?> value="2">Newspaper</option>
                                                                            <option <?php echo @$animation[3]; ?> value="3">Horizontal move</option>
                                                                            <option <?php echo @$animation[4]; ?> value="4">Move from top</option>
                                                                            <option <?php echo @$animation[5]; ?> value="5">3d unfold</option>
                                                                            <option <?php echo @$animation[6]; ?> value="6">Zoom-out</option>
                                                                        </select>
                                                                   </div>
                                                                </div>
                                                                <div class="field-wrap">
                                                                    <label>Display for the first time only </label>
                                                                    <div class="form-group">
                                                                        <div class="switch" id="basic">
                                                                            <select class="form-control" name="when_display">
                                                                                <option <?php echo @$when_display[0]; ?> value="0">All Time</option>
                                                                                <option <?php echo @$when_display[1]; ?> value="1">First Time only</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="field-wrap">
                                                                    <label style="width:100%">Automatically close pop up after how many seconds?</label>
                                                                    <div class="form-group">
                                                                        <input type="text" value="<?php echo $myrows[0]->time; ?>" name="seconds" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="field-wrap">
                                                                    <label>User can remove announcement </label>
                                                                    <div class="form-group">
                                                                        <select class="form-control" name="remove" id="wp-remove">
                                                                            <option <?php echo @$remove[1]; ?> value="1">By clicking on cross icon.</option>
                                                                            <option <?php echo @$remove[2]; ?> value="2">By clicking anywhere on the page.</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="buttons">
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="col col-adv">

                                                </div>
                                                <div class="clearfix"></div>
                                                </div>

                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                            </form> 
                                            <?php
                                        }

//-------------------------------------- database --------------------
                                        global $jal_db_version;
                                        $jal_db_version = '1';

                                        function jal_install() {
                                            global $wpdb;
                                            global $jal_db_version;

                                            $table_name = $wpdb->prefix . 'notify';

                                            $charset_collate = $wpdb->get_charset_collate();

                                            $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}$table_name");

                                            // status: 1=active, 0 unactive
                                            // type: 1=text, 2=image, 3=video, 4=any
                                            // display: 1=all other page, 2= home page, 3=all pages
                                            // user: 1:logged in, 2: not logged in, 3: both
                                            // position: 1:top, 2:bottom, 3:center
                                            // animation 1:fad and pop, 2:fad
                                            // when_display: 1=first time only, 0=all time
                                            // remove = 1= by clicking cross, 2= by clicking any where, 3= both
                                            $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
                type int,
                radio_value varchar (255) DEFAULT '' NOT NULL,
                data varchar (4000) DEFAULT '' NOT NULL,
                display int, 
                user int,
                bg_color varchar (11) DEFAULT '#ffffff' NOT NULL,
                width int,
                position int,
                animation int,
                when_display int,
                time int,
                remove int, 
                status int, 
                user_id int,
                active int,
                except_ids varchar(255),
		created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		last_modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

                                            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                                            dbDelta($sql);

                                            add_option('jal_db_version', $jal_db_version);
                                        }

                                        function jazzpopup_update_db_check() {
                                            global $jal_db_version;
                                            if (get_site_option('jal_db_version') != $jal_db_version) {
                                                jazzpopup_update_db_check();
                                            }
                                        }

//add_action('plugins_loaded', 'jazzpopup_update_db_check');                                


                                        function jal_install_data() {
                                            global $wpdb;

                                            $type = '0';
                                            $radio_value = 'text';
                                            $data = 'Congratulations, you just completed the installation. Welcome to Jazz Popup!';

                                            $table_name = $wpdb->prefix . 'notify';

                                            $ul = '0';
                                            global $current_user;
                                            get_currentuserinfo();
                                            if (isset($current_user)) {
                                                $ul = $current_user->ID;
                                            }
                                            $user_id = $ul;
                                            $table = $wpdb->prefix . 'notify';
                                            $myrows = $wpdb->get_results("SELECT * FROM $table WHERE id = 1");

                                            if ($myrows == NULL) {
                                                $wpdb->insert(
                                                        $table_name, array(
                                                    'created' => current_time('mysql'),
                                                    'last_modified' => current_time('mysql'),
                                                    'radio_value' => $radio_value,
                                                    'type' => $type,
                                                    'data' => $data,
                                                    'status' => 1,
                                                    'display' => 3,
                                                    'user' => 3,
                                                    'bg_color' => '#ffffff',
                                                    'width' => 500,
                                                    'position' => 3,
                                                    'time' => 6000,
                                                    'animation' => 2,
                                                    'when_display' => 0,
                                                    'remove' => 3,
                                                    'except_ids' => '',
                                                    'user_id' => $user_id,
                                                    'active' => 1
                                                        )
                                                );
                                            }
                                        }

                                        register_activation_hook(__FILE__, 'jal_install');
                                        register_activation_hook(__FILE__, 'jal_install_data');

//--------------------------------------------------------------------
                                        function ShowTinyMCE() {
                                            // conditions here
                                            wp_enqueue_script('common');
                                            wp_enqueue_script('jquery-color');
                                            wp_print_scripts('editor');
                                            if (function_exists('add_thickbox'))
                                                add_thickbox();
                                            wp_print_scripts('media-upload');
                                            if (function_exists('wp_tiny_mce'))
                                                wp_tiny_mce();
                                            wp_admin_css();
                                            wp_enqueue_script('utils');
                                            do_action("admin_print_styles-post-php");
                                            do_action('admin_print_styles');
                                        }

                                        function my_enqueue($hook) {
                                            //only for our special plugin admin page
                                            wp_register_style('css1', plugins_url('/css/style.css', __FILE__));
                                            wp_enqueue_style('css1');
                                            wp_register_style('jazz_css', plugins_url('/jazz-popup/jazz-popup_new.css', __FILE__));
                                            wp_enqueue_style('jazz_css');
                                            wp_register_style('wpjazzpopup_magicsuggest-min', plugins_url('/css/magicsuggest-min.css', __FILE__));
                                            wp_enqueue_style('wpjazzpopup_magicsuggest-min');
                                            wp_enqueue_script('jquery-ui-core', array('jquery'));
                                            wp_enqueue_script('pluginscript1', plugins_url('/jazz-popup/jquery.jazz-popup.js', __FILE__), array('jquery'));
                                            wp_enqueue_script('pluginscript2', plugins_url('/jazz-popup/jquery.jazz-popup.min.js', __FILE__), array('jquery'));
                                            wp_register_script('wpjazzpopup_magicsuggest', plugins_url('/js/magicsuggest-min.js', __FILE__), array('jquery'));
                                            wp_enqueue_script('wpjazzpopup_magicsuggest');
                                        }

                                        add_action('admin_enqueue_scripts', 'my_enqueue');
                                        add_action('admin_enqueue_scripts', 'my_admin_scripts');

                                        function my_admin_scripts() {
                                            if (isset($_GET['page']) && $_GET['page'] == 'jazz-plugin') {
                                                wp_enqueue_media();
                                                wp_register_script('my-admin-js', plugins_url('/js/upload-image.js', __FILE__), array('jquery'));
                                                wp_enqueue_script('my-admin-js');
                                            }
                                        }
                                        ?>