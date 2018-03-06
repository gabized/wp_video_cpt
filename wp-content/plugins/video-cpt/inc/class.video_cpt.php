<?php

class video_cpt
{

    public function __construct() {

        add_action('init',array($this, 'create_custom_post_types'));
        add_action('add_meta_boxes',array($this, 'video_meta_box'));
        add_action('save_post_video',array($this,'save_video_meta_boxes'));
        add_shortcode('prefix_video', array($this,'create_shortcode'));
        add_action( 'admin_init', array($this,'add_videos_capabilities'),999);
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_css' ));

    }

    //hook the translation func
    public function hook_translation() {
      add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
    }

    //load the langs >> wip
    public function load_plugin_textdomain() {
      load_plugin_textdomain( 'videocpt', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    }

   //frontend styles
   public function enqueue_css() {

       wp_enqueue_style('video_cpt_styles', VCPT_PATH .'assets/css/video_cpt_styles.css', array(), 'all');

   }

    //register the cpt
    public function create_custom_post_types() {

            $this->labels = array(
                'name'               => __( 'Videos'),
                'singular_name'      => __( 'Video'),
                'menu_name'          => __( 'Videos'),
                'name_admin_bar'     => __( 'Video'),
                'add_new'            => __( 'Add New'),
                'add_new_item'       => __( 'Add New Video' ),
                'new_item'           => __( 'New Video'),
                'edit_item'          => __( 'Edit Video'),
                'view_item'          => __( 'View Video'),
                'all_items'          => __( 'All Videos'),
                'search_items'       => __( 'Search Videos' ),
                'parent_item_colon'  => __( 'Parent Videos:' ),
                'not_found'          => __( 'No Videos found.'),
                'not_found_in_trash' => __( 'No Videos found in Trash.' )
            );

            $this->args = array(
                'labels'             => $this->labels,
                'description'        => __( 'Custom post type for Videos'),
                'public'             => true,
                'publicly_queryable' => false,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_icon'          => 'dashicons-video-alt3',
                'supports'           => 'title',
                'capability_type'     => 'cap_video',
                'map_meta_cap'        => true,
            );

            register_post_type( 'Video', $this->args );

    }


    //add meta boxes for the custom fields we need
    public function video_meta_box() {

        add_meta_box('_subtitle', __('Subtitle'), array($this, 'html_videos_subtitle'), 'video', 'normal', 'high');
        add_meta_box('_desc', __('Description'), array($this, 'html_videos_description'), 'video', 'normal', 'high');
        add_meta_box('_videoID', __('ID'), array($this, 'html_videos_URL'), 'video', 'normal', 'high');
        add_meta_box('_type', __('Type'), array($this, 'html_videos_type'), 'video', 'normal', 'high');

    }

    //subtitle
    public function html_videos_subtitle() {
        global $post;

        // see if we have the subtitle already
        $subtitle = get_post_meta($post->ID, '_subtitle', true);

        echo "<input type='text' name='_subtitle' value='" . $subtitle  . "' class='widefat'/>";

    }

    //video description
    public function html_videos_description() {

        global $post;

        //see if we have any description already
        $desc = get_post_meta($post->ID, '_desc', true);

        echo "<textarea name='_desc' class='widefat'>".$desc."</textarea>";

    }

    //get the video ID >> wip: validate
    public function html_videos_url() {

        global $post;

        // see if we have any id
        $videoID = get_post_meta($post->ID, '_videoID', true);
        echo "<input type='text' name='_videoID' value='" . $videoID  . "' class='widefat'/>";

    }

    //video providers >> wip:validate with the ID's to make sure the ID is valid for the selected provider
    public function html_videos_type() {

        global $post;

        // see if we have a provider already
        $type = get_post_meta($post->ID, '_type', true);

        echo "<select type='text' name='_type' value='" . $type  . "' class='widefat'>";
        echo "<option value='youtube'".selected( $type, 'youtube' )."'>Youtube</option>";
        echo "<option value='vimeo'".selected( $type, 'vimeo' )."'>Vimeo</option>";
        echo "<option value='dailymotion'".selected( $type, 'dailymotion' )."'>Dailymotion</option>";
        echo "</select>";

    }

    //save the meta for the videos
    public function save_video_meta_boxes() {

        global $post;

        // save video type
        if( isset( $_POST['_type'] ) ) {
            $this->type = esc_attr($_POST['_type']);
            update_post_meta( $post->ID, '_type', $this->type);
        }

        // save video subtitle
        if( isset( $_POST['_subtitle'] ) ) {
            $this->subtitle = esc_attr($_POST['_subtitle']);
            update_post_meta( $post->ID, '_subtitle', $this->subtitle);
        }

        // save video description
        if( isset( $_POST['_desc'] ) ) {
            $this->desc = esc_textarea( $_POST['_desc']);
            update_post_meta( $post->ID, '_desc', $this->desc );
        }

        // save video ID
        if( isset( $_POST['_videoID'] ) ) {
            $this->videoID = esc_attr( $_POST['_videoID']);
            update_post_meta( $post->ID, '_videoID', $this->videoID);
        }

    }

    //query string validation
    public function add_notice_query_var( $location ) {

       return add_query_arg( array( 'validate' => 'no'  ), $location );

    }

    public function add_videos_capabilities() {

       //admins and editors can access
        $roles = array('editor','administrator');

         foreach($roles as $the_role) {
           $role = get_role($the_role);
              $role->add_cap( 'read_cap_video');
              $role->add_cap( 'read_private_cap_videos');
              $role->add_cap( 'edit_cap_video' );
              $role->add_cap( 'edit_cap_videos' );
              $role->add_cap( 'edit_others_cap_videos');
              $role->add_cap( 'edit_published_cap_videos');
              $role->add_cap( 'publish_cap_videos');
              $role->add_cap( 'delete_others_cap_videos');
              $role->add_cap( 'delete_private_cap_videos');
              $role->add_cap( 'delete_published_cap_videos');
         }

    }

    //shortcode for the videos.
    public function create_shortcode($atts) {

        $this->attr = shortcode_atts( array(
            'id' => 'POST ID',
            'border_color' => '#999999',
            'border_width' => '8',
        ), $atts ); // default border color and width.

        return $this->make_it_nice($this->attr['id'],$this->attr['border_color'],$this->attr['border_width']);

    }

    //set up the HTML/CSS
    public function make_it_nice($videoID,$border_color,$border_width) {

        $this->videoID = $videoID;
        $this->border_color = $border_color;
        $this->border_width = $border_width;

        $this->title = get_the_title( $this->videoID);
        $this->subtitle = get_post_meta( $this->videoID, '_subtitle', true );
        $this->description = get_post_meta( $this->videoID, '_desc', true );
        $this->type = get_post_meta( $this->videoID, '_type', true );
        $this->id = get_post_meta( $this->videoID, '_videoID', true );

        $this->video_html ='<div class="vcpt_cont">';
        $this->video_html .='<div class="vcpt_iframe_outer">';
        $this->video_html .='<div class="vcpt_iframe">';

        switch ($this->type) {
            case 'youtube':
                $this->video_html .= '<iframe src="https://www.youtube.com/embed/' . $this->id . '" frameborder="0" allowfullscreen style="border:' . $this->border_width . 'px solid ' .$this->border_color. '"></iframe>';
                break;

            case 'vimeo':
                $this->video_html .= '<iframe src="https://player.vimeo.com/video/' . $this->id . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="border:8px solid ' .$this->border_color. '"></iframe>';
                break;

            case 'dailymotion':
                $this->video_html .= '<iframe frameborder="0" src="//www.dailymotion.com/embed/video/' . $tihs->id .'" allowfullscreen style="border:8px solid ' .$this->border_color. '"></iframe>';
                break;
        }

        $this->video_html .= '</div>'; //close iframe cont
        $this->video_html .= '</div>'; //close iframe outer cont
        $this->video_html .= '<div class="vcpt_info">';
        $this->video_html .= '<h3>' . $this->title . '</h3>';
        $this->video_html .= '<div class="vcpt_subtitle">' . $this->subtitle . '</div>';
        $this->video_html .= '<div class="vcpt_desc"><p>' . $this->description . '</p></div>';
        $this->video_html .= '</div>'; //close the desc cont
        $this->video_html .= '</div>'; //close the container
        $this->video_html .= '<div class="clr"></div>';

        return  $this->video_html;

     }



}//end class


//launch it
new video_cpt;
