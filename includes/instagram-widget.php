<?php

/**
* Instagram Widget
*/
class SigWidget extends WP_Widget
{

    function __construct()
    {
        $sig_widget = array(
            'sig_widget',
            'classname' => 'sig_widget',
            'description' => __('Simple Instagram Widget')
        );
        parent::__construct('sig_widget', 'Simple Instagram Widget', $sig_widget);
    }

    public function widget( $args, $instance )
    {
        // output
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        echo do_shortcode( '[sig count="'.($instance['count'] ? $instance['count'] : 6).'" size="thumbnail" class="'.($instance['class'] ? $instance['class'] : '').' '.($instance['styles'] ? '' : 'sig-widget').'" disable_styles="'.($instance['styles'] ? $instance['styles'] : '').'" ]' );

        echo $args['after_widget'];
    }

    public function form( $instance )
    {
        $title  = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Latest Posts' );
        $count  = ! empty( $instance['count'] ) ? $instance['count'] : '6';
        $class  = ! empty( $instance['class'] ) ? $instance['class'] : '';
        $styles = ! empty( $instance['styles'] ) ? $instance['styles'] : '';
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_attr_e( 'Number of media:' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" value="<?php echo esc_attr( $count ); ?>" max="20">
        </p>

        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"><?php esc_attr_e( 'Custom Class:' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'class' ) ); ?>" type="text" value="<?php echo esc_attr( $class ); ?>">
        </p>

        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'styles' ) ); ?>"><?php esc_attr_e( 'Disable built in styles:' ); ?></label>
        <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'styles' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'styles' ) ); ?>" value="true" <?php echo($styles == 'true' ? 'checked':''); ?>>
        </p>

        <?php
    }

    public function update( $new_instance, $old_instance )
    {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['count'] = ( ! empty( $new_instance['count'] ) ) ? $new_instance['count'] : '';
        $instance['class'] = ( ! empty( $new_instance['class'] ) ) ? strip_tags( $new_instance['class'] ) : '';
        $instance['styles'] = ( ! empty( $new_instance['styles'] ) ) ? strip_tags( $new_instance['styles'] ) : '';

        return $instance;
    }

}

add_action( 'widgets_init', function(){
    register_widget( 'SigWidget' );
});