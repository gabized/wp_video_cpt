<?php

class video_shortcode_but
{

    private $videos;

    public function __construct() {

        $this->videos = get_posts(array('post_type'=>'video'),OBJECT);

        add_action('init',array($this, 'shortcode_btn_init'));

        foreach ( array('post.php','post-new.php') as $hook ) {
            add_action( "admin_head-$hook", array($this,'my_admin_head' ));
        }

    }

    public function shortcode_btn_init() {
        add_filter("mce_external_plugins", array($this,"enqueue_plugin_scripts"));
        add_filter("mce_buttons", array($this,"register_buttons_editor"));

    }


    public function enqueue_plugin_scripts($plugin_array) {
        $plugin_array["video_shortcode_button_plugin"] =  VCPT_PATH . "assets/js/video_cpt_shortcode.js";
        return $plugin_array;
    }

	public function register_buttons_editor($buttons) {
        array_push($buttons, "video_shortcode_button");
        return $buttons;
    }

    public function my_admin_head() {
        ?>
        <script type='text/javascript'>
            var my_plugin = {
                'videos': '<?php echo json_encode( $this->videos); ?>',
            };
        </script>
        <?php
    }


}

//launch it.
new video_shortcode_but;
