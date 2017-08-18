<?php

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Multiple checkbox customizer
 *  
 * @package     iPress\Controls
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */

/**
 * Multiple checkbox customize control class
 * 
 * @package iPress\Controls
 */
class WP_Customize_Control_Checkbox_Multiple extends WP_Customize_Control {

    /**
     * The type of customize control being rendered
     * 
     * @var string $type
     */
    public $type = 'checkbox-multiple';

    /**
     * Enqueue scripts/styles
     */
    public function enqueue() {
        wp_enqueue_script( 'customiser-checkbox-multiple', IPRESS_CONTROLS_JS_DIR . '/checkbox-multiple.js', [ 'jquery' ] );
    }

    /**
     * Displays the control content
     */
    public function render_content() {

        // No options?
        if ( empty( $this->choices ) ) { return; }
?>

        <?php if ( !empty( $this->label ) ) : ?>
            <span class="customize-control-title"><?= sprintf( esc_html( '%s', 'ipress' ), $this->label ); ?></span>
        <?php endif; ?>

        <?php if ( !empty( $this->description ) ) : ?>
            <span class="description customize-control-description"><?= sprintf( '%s', $this->description ); ?></span>
        <?php endif; ?>

        <?php $multi_values = !is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>

        <ul>
            <?php foreach ( $this->choices as $value => $label ) : ?>

                <li>
                    <label>
                        <input type="checkbox" name="<?= $this->type . '-' . $value; ?>" id="<?= $this->type . '_' . $value; ?>" class="<?= $this->type . '-checkbox'; ?>" value="<?= esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> /> 
                        <?= esc_html( $label ); ?>
                    </label>
                </li>

            <?php endforeach; ?>
        </ul>

        <input type="hidden" id="<?= $this->id; ?>" <?php $this->link(); ?> class="<?= $this->type . '-hidden'; ?>" value="<?= esc_attr( implode( ',', $multi_values ) ); ?>" />
<?php 
    }
}

//end
