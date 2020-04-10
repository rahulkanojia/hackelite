<?php
class Inwave_Contact_Info_Footer extends WP_Widget
{

    function __construct() {
        parent::__construct(
            'inwave-contact-info',
            esc_html__('Inwave Contact Information', 'injob'),
            array('description' => esc_html__('Widget display about us information in footer.', 'injob'))
        );
    }

    function widget($args, $instance) {
        if(is_array($instance) && count($instance) > 1){
            extract($args);
            // widget content
            global $inwave_theme_option;
            if(!isset($instance['social_resource'])){
                $instance['social_resource'] = '';
            }
            if(!isset($instance['custom_social'])){
                $instance['custom_social'] = '';
            }
            $logo_img = Inwave_Helper::getPostOption('image-information');
            $title = apply_filters( 'widget_title', $instance['title'] );
            $description = empty( $instance['description'] ) ? '' : $instance['description'];
            $images = empty( $instance['image_uri'] ) ? '' : $instance['image_uri'];
            $link = empty( $instance['link'] ) ? '' : $instance['link'];
            $text_link = empty( $instance['text_link'] ) ? '' : $instance['text_link'];
            $social_resource = empty( $instance['social_resource'] ) ? '' : $instance['social_resource'];
            $socials = empty( $instance['custom_social'] ) ? '' : $instance['custom_social'];
            $no_title = '';
            if (!$title) {
                $no_title = 'no-title';
            }

            echo wp_kses_post($before_widget);
            echo '<div class="widget-info-footer ' . $no_title .'">';
            echo wp_kses_post($before_title. $title .$after_title);
            ?>
            <?php if ($logo_img) {
                echo '<a class="iw-widget-about-us" href="' .esc_url(home_url('/')). '">
                    <img src="' .esc_url($logo_img). '" alt=""/>
                </a>';
            }
            elseif ($images) {
                echo '<a class="iw-widget-about-us" href="' .esc_url(home_url('/')). '">
                    <img src="' .esc_url($images). '" alt=""/>
                </a>';
            }
            ?>
            <p><?php echo esc_html($description); ?></p>
            <a class="link_page_about_us" href="<?php echo esc_url($link); ?>"><?php echo esc_html($text_link); ?> </a>
            <?php if($social_resource ==  '1'){
                echo '<ul class="iw-social-footer-all">';
                if (count(Inwave_Helper::getThemeOption('social_links')) > 1) {
                    $social_links = Inwave_Helper::getThemeOption('social_links');
                    unset($social_links[0]);
                    foreach ($social_links as $social_link) {
                        $class = explode(".com", $social_link['link']);
                        $class = str_replace(array('https://', 'http://', 'www.'), '', $class[0]);
                        if ($class == 'plus.google') {
                            $class = 'google-plus';
                        }
                        echo '<li><a class="'.$class.'" target="_blank" href="' . esc_url($social_link['link']) . '" title="' . esc_attr($social_link['title']) . '"><i class="fa ' . esc_attr($social_link['icon']) . '"></i></a></li>';
                    }
                }
                echo '</ul>';
            }else{
                $socials = explode("\n", $instance['custom_social']);
                echo '<ul class="iw-social-footer-all">';
                if(count($socials)){
                    foreach($socials as $social){
                        $social = explode("|", $social);
                        $class = explode(".com", $social[1]);
                        $class = str_replace(array('https://', 'http://'), '', $class[0]);
                        if ($class == 'plus.google') {
                            $class = 'google-plus';
                        }

                        if(count($social) == 2){
                            echo '<li><a class="'.$class.'" href="' . esc_url($social[1]) . '"><i class="fa ' . esc_attr($social[0]) . '"></i></a></li>';
                        }
                    }
                }
                echo '</ul>';
            } ?>

            <?php
            echo '</div>';
            echo wp_kses_post($after_widget);
        }

    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['description'] = strip_tags($new_instance['description']);
        $instance['image_uri'] = strip_tags($new_instance['image_uri']);
        $instance['link'] = strip_tags($new_instance['link']);
        $instance['text_link'] = strip_tags($new_instance['text_link']);
        $instance['social_resource'] = strip_tags($new_instance['social_resource']);
        $instance['custom_social'] = strip_tags($new_instance['custom_social']);

        return $instance;
    }
    function form($instance) {
        $default = array(
            'title'         => 'Title',
            'description'   => '',
            'image_uri'     => '',
            'link'          => '',
            'text_link'          => '',
            'social_resource'   => '',
            'custom_social'     => ''
        );
        $instance = wp_parse_args((array)$instance, $default);
        $title = esc_attr($instance['title']);
        $description = esc_attr($instance['description']);
        $image_uri = esc_attr($instance['image_uri']);
        $link = esc_attr($instance['link']);
        $text_link = esc_attr($instance['text_link']);
        ?>

        <p> <?php esc_html_e('Title', 'injob'); ?><input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>"/></p>
        <p> <?php esc_html_e('Description', 'injob'); ?><textarea class="widefat" rows="5" cols="10" class="widefat" id="<?php echo esc_attr($this->get_field_id('description')); ?> "name="<?php echo esc_attr($this->get_field_name('description'));?>"> <?php echo esc_html($description) ?> </textarea></p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('image_uri')); ?>"><?php esc_html_e('Image', 'injob'); ?></label><br/>

            <?php
            if ($instance['image_uri'] != '') :
                echo '<img class="custom_media_image" src="' . esc_attr($image_uri) . '" /><br />';
            endif;
            ?>

            <input type="text" class="widefat custom_media_url"
                   name="<?php echo esc_attr($this->get_field_name('image_uri')); ?>"
                   id="<?php echo esc_attr($this->get_field_id('image_uri')); ?>"
                   value="<?php echo esc_attr($instance['image_uri']); ?>">

            <input type="button" class="button button-primary custom_media_button" id="custom_media_button"
                   name="<?php echo esc_attr($this->get_field_name('image_uri')); ?>" value="<?php esc_html_e('Upload Image', 'injob'); ?>"/>
        </p>
        <p> <?php esc_html_e('Action URL', 'injob'); ?><input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('link')); ?>" name="<?php echo esc_attr($this->get_field_name('link')); ?>" value="<?php echo esc_attr($link);?>"/></p>
        <p> <?php esc_html_e('Link Text', 'injob'); ?><input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('text_link')); ?> " name="<?php echo esc_attr($this->get_field_name('text_link'));?>" value="<?php echo esc_attr($text_link);?>"/></p>
        <p class="social_resource"><label for="<?php echo esc_attr($this->get_field_id('social_resource')); ?>"><?php echo esc_html__('Social Resource', 'injob'); ?></label><br/>
            <select name="<?php echo esc_attr($this->get_field_name('social_resource')); ?>" id="<?php echo esc_attr($this->get_field_id('social_resource')); ?>" class="widefat">
                <option value="1" <?php echo (esc_html($instance['social_resource']) == '1' ? 'selected' : ''); ?>><?php echo esc_html__('Theme config', 'injob'); ?></option>
                <option value="2" <?php echo (esc_html($instance['social_resource']) == '2' ? 'selected' : ''); ?>><?php echo esc_html__('Custom', 'injob'); ?></option>
            </select>
        </p>
        <p class="custom_social" style="<?php echo (esc_html($instance['social_resource']) == '2' ? '' : 'display: none;'); ?>" >
            <label for="<?php echo esc_attr($this->get_field_id('custom_social')); ?>"><?php echo esc_html__('Custom Social', 'injob'); ?></label><br/>
            <textarea rows="10" cols="70" name="<?php echo esc_attr($this->get_field_name('custom_social')); ?>" id="<?php echo esc_attr($this->get_field_id('custom_social')); ?>" class="widefat"><?php echo esc_html($instance['custom_social']); ?></textarea>
            <span>One Social [fontawesome_icon|URL] per one line </span><br>
            <span>Example: fa-facebook|http://facebook.com</span>
        </p>
        <?php
    }
}
function inwave_contact_info_widget() {
    register_widget('Inwave_Contact_Info_Footer');
}
add_action('widgets_init', 'inwave_contact_info_widget');