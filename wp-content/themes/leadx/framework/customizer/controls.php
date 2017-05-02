<?php
/**
 * Customizer controls
 */

/**
 * Alpha Color Picker Customizer Control
 *
 * This control adds a second slider for opacity to the stock WordPress color picker,
 * and it includes logic to seamlessly convert between RGBa and Hex color values as
 * opacity is added to or removed from a color.
 */
class LeadX_Customize_Alpha_Color_Control extends WP_Customize_Control {
	/**
	 * Official control name.
	 */
	public $type = 'alpha-color';
	/**
	 * Add support for palettes to be passed in.
	 *
	 * Supported palette values are true, false, or an array of RGBa and Hex colors.
	 */
	public $palette;
	/**
	 * Add support for showing the opacity value on the slider handle.
	 */
	public $show_opacity;
	/**
	 * Enqueue scripts and styles.
	 *
	 * Ideally these would get registered and given proper paths before this control object
	 * gets initialized, then we could simply enqueue them here, but for completeness as a
	 * stand alone class we'll register and enqueue them here.
	 */
	public function enqueue() {
		wp_enqueue_script(
				'alpha-color-picker',
				get_template_directory_uri() . '/framework/customizer/assets/alpha-color-picker.js',
				array( 'jquery', 'wp-color-picker' ),
				'1.0.0',
				true
		);
		wp_enqueue_style(
				'alpha-color-picker',
				get_template_directory_uri() . '/framework/customizer/assets/alpha-color-picker.css',
				array( 'wp-color-picker' ),
				'1.0.0'
		);
	}
	/**
	 * Render the control.
	 */
	public function render_content() {
		// Process the palette
		if ( is_array( $this->palette ) ) {
			$palette = implode( '|', $this->palette );
		} else {
			// Default to true.
			$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
		}
		// Support passing show_opacity as string or boolean. Default to true.
		$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';
		// Begin the output. ?>
		<label>
			<?php // Output the label and description if they were passed in.
			if ( isset( $this->label ) && '' !== $this->label ) {
				echo '<span class="customize-control-title">' . sanitize_text_field( $this->label ) . '</span>';
			}
			if ( isset( $this->description ) && '' !== $this->description ) {
				echo '<span class="description customize-control-description">' . sanitize_text_field( $this->description ) . '</span>';
			} ?>
			<input class="alpha-color-control" type="text" data-show-opacity="<?php echo esc_attr($show_opacity); ?>" data-palette="<?php echo esc_attr( $palette ); ?>" data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php $this->link(); ?>  />
		</label>
		<?php
	}
}

 /**
 * Slider UI control
 */
class LeadX_Customize_Sliderui_Control extends WP_Customize_Control {
	/**
	 * Official control name.
	 */
	public $type = 'leadx_slider_ui';
	
	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-core', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-slider',array( 'jquery' ) );
	}
	public function render_content() {
		$this_val = $this->value() ? $this->value() : '0'; 
		$this_val = intval($this_val,10); ?>
		<label>
			<span class="customize-control-title">
				<?php echo esc_html( $this->label ); ?>
			</span>
			<?php if ( '' != $this->description ) { ?>
				<span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
			<?php } ?>
			 <input type="text" id="input_<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($this_val); ?>" <?php $this->link(); ?>/>
		</label>
		<div id="slider_<?php echo esc_attr($this->id); ?>" class="ttbase-slider-ui"></div>
		<script>
			jQuery(document).ready(function($) {
				$( "#slider_<?php echo esc_js($this->id); ?>" ).slider({
					value : <?php echo esc_js($this_val); ?>,
					min   : <?php echo esc_js($this->choices['min']); ?>,
					max   : <?php echo esc_js($this->choices['max']); ?>,
					step  : <?php echo esc_js($this->choices['step']); ?>,
					slide : function( event, ui ) { $( "#input_<?php echo esc_js($this->id); ?>" ).val(ui.value).keyup(); }
				});
				$( "#input_<?php echo esc_js($this->id); ?>" ).val( $( "#slider_<?php echo esc_js($this->id); ?>" ).slider( "value" ) );
				$( "#input_<?php echo esc_js($this->id); ?>" ).keyup(function() {
					$( "#slider_<?php echo esc_js($this->id); ?>" ).slider( "value", $(this).val() );
				});
			});
		</script>
		<?php
	}
}

/**
 * Adds textarea support to the theme customizer
 */
class LeadX_Customize_Textarea_Control extends WP_Customize_Control {
	public $type = 'textarea';

	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
		</label>
		<?php
	}
}

/**
 * Google Fonts Control
 *
 */
class LeadX_Fonts_Dropdown_Custom_Control extends WP_Customize_Control {
	public function render_content() {
		$this_val = $this->value(); ?>
	<label>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<select <?php $this->link(); ?>>
			<option value="" <?php if( ! $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Default', 'leadx' ); ?></option>

			<option value="Arial, Helvetica, sans-serif" <?php if( "Arial, Helvetica, sans-serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Arial, Helvetica, sans-serif', 'leadx' ); ?></option>
			<option value="Arial Black, Gadget, sans-serif" <?php if( "Arial Black, Gadget, sans-serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Arial Black, Gadget, sans-seri', 'leadx' ); ?>f</option>
			<option value="Bookman Old Style, serif" <?php if( "Bookman Old Style, serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bookman Old Style, serif', 'leadx' ); ?></option>
			<option value="Comic Sans MS, cursive" <?php if( "Comic Sans MS, cursive" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Comic Sans MS, cursive', 'leadx' ); ?></option>
			<option value="Courier, monospace" <?php if( "Courier, monospace" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Courier, monospace', 'leadx' ); ?></option>
			<option value="Garamond, serif" <?php if( "Garamond, serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Garamond, serif', 'leadx' ); ?></option>
			<option value="Georgia, serif" <?php if( "Georgia, serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Georgia, serif', 'leadx' ); ?></option>
			<option value="Impact, Charcoal, sans-serif" <?php if( "Impact, Charcoal, sans-serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Impact, Charcoal, sans-serif', 'leadx' ); ?></option>
			<option value="Lucida Console, Monaco, monospace" <?php if( "Lucida Console, Monaco, monospace" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lucida Console, Monaco, monospace', 'leadx' ); ?></option>
			<option value="Lucida Sans Unicode, Lucida Grande, sans-serif" <?php if( "Lucida Sans Unicode, Lucida Grande, sans-serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lucida Sans Unicode, Lucida Grande, sans-serif', 'leadx' ); ?></option>
			<option value="MS Sans Serif, Geneva, sans-serif" <?php if( "MS Sans Serif, Geneva, sans-serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'MS Sans Serif, Geneva, sans-serif', 'leadx' ); ?></option>
			<option value="MS Serif, New York, sans-serif" <?php if( "MS Serif, New York, sans-serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'MS Serif, New York, sans-serif', 'leadx' ); ?></option>
			<option value="Palatino Linotype, 'Book Antiqua, Palatino, serif" <?php if( "Palatino Linotype, 'Book Antiqua, Palatino, serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Palatino Linotype, Book Antiqua, Palatino, serif', 'leadx' ); ?></option>
			<option value="Tahoma, Geneva, sans-serif" <?php if( "Tahoma, Geneva, sans-serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Tahoma, Geneva, sans-serif', 'leadx' ); ?></option>
			<option value="Times New Roman, Times, serif" <?php if( "Times New Roman, Times, serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Times New Roman, Times, serif', 'leadx' ); ?></option>
			<option value="Trebuchet MS, Helvetica, sans-serif" <?php if( "Trebuchet MS, Helvetica, sans-serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Trebuchet MS, Helvetica, sans-serif', 'leadx' ); ?></option>
			<option value="Verdana, Geneva, sans-serif" <?php if( "Verdana, Geneva, sans-serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Verdana, Geneva, sans-serif', 'leadx' ); ?></option>
			<option value="ABeeZee" <?php if( "ABeeZee" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'ABeeZee', 'leadx' ); ?></option>
			<option value="Abel" <?php if( "Abel" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Courier, monospace', 'leadx' ); ?>Abel</option>
			<option value="Abril Fatface" <?php if( "Abril Fatface" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Abril Fatface', 'leadx' ); ?></option>
			<option value="Aclonica" <?php if( "Aclonica" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Aclonica', 'leadx' ); ?></option>
			<option value="Acme" <?php if( "Acme" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Acmee', 'leadx' ); ?></option>
			<option value="Actor" <?php if( "Actor" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Actor', 'leadx' ); ?>Actor</option>
			<option value="Adamina" <?php if( "Adamina" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Adamina', 'leadx' ); ?></option>
			<option value="Advent Pro" <?php if( "Advent Pro" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Advent Pro', 'leadx' ); ?></option>
			<option value="Aguafina Script" <?php if( "Aguafina Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Aguafina Script', 'leadx' ); ?></option>
			<option value="Akronim" <?php if( "Akronim" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Akronim', 'leadx' ); ?></option>
			<option value="Aladin" <?php if( "Aladin" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Aladin', 'leadx' ); ?></option>
			<option value="Aldrich" <?php if( "Aldrich" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Aldrich', 'leadx' ); ?></option>
			<option value="Alef" <?php if( "Alef" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Alef', 'leadx' ); ?></option>
			<option value="Alegreya" <?php if( "Alegreya" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Alegreya', 'leadx' ); ?></option>
			<option value="Alegreya SC" <?php if( "Alegreya SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Alegreya SC', 'leadx' ); ?></option>
			<option value="Alegreya Sans" <?php if( "Alegreya Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Alegreya Sans', 'leadx' ); ?></option>
			<option value="Alegreya Sans SC" <?php if( "Alegreya Sans SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Alegreya Sans SC', 'leadx' ); ?></option>
			<option value="Alex Brush" <?php if( "Alex Brush" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Alex Brush', 'leadx' ); ?></option>
			<option value="Alfa Slab One" <?php if( "Alfa Slab One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Alfa Slab One', 'leadx' ); ?></option>
			<option value="Alice" <?php if( "Alice" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Alice', 'leadx' ); ?></option>
			<option value="Alike" <?php if( "Alike" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Alike', 'leadx' ); ?></option>
			<option value="Alike Angular" <?php if( "Alike Angular" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Alike Angular', 'leadx' ); ?></option>
			<option value="Allan" <?php if( "Allan" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Allan', 'leadx' ); ?></option>
			<option value="Allerta" <?php if( "Allerta" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Allerta', 'leadx' ); ?></option>
			<option value="Allerta Stencil" <?php if( "Allerta Stencil" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Allerta Stencil', 'leadx' ); ?></option>
			<option value="Allura" <?php if( "Allura" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Allura', 'leadx' ); ?></option>
			<option value="Almendra" <?php if( "Almendra" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Almendra', 'leadx' ); ?></option>
			<option value="Almendra Display" <?php if( "Almendra Display" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Almendra Display', 'leadx' ); ?></option>
			<option value="Almendra SC" <?php if( "Almendra SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Almendra SC', 'leadx' ); ?></option>
			<option value="Amarante" <?php if( "Amarante" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Amarante', 'leadx' ); ?></option>
			<option value="Amaranth" <?php if( "Amaranth" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Amaranth', 'leadx' ); ?></option>
			<option value="Amatic SC" <?php if( "Amatic SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Amatic SC', 'leadx' ); ?></option>
			<option value="Amethysta" <?php if( "Amethysta" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Amethysta', 'leadx' ); ?></option>
			<option value="Anaheim" <?php if( "Anaheim" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Anaheim', 'leadx' ); ?></option>
			<option value="Andada" <?php if( "Andada" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Andada', 'leadx' ); ?></option>
			<option value="Andika" <?php if( "Andika" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Andika', 'leadx' ); ?></option>
			<option value="Angkor" <?php if( "Angkor" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Angkor', 'leadx' ); ?></option>
			<option value="Annie Use Your Telescope" <?php if( "Annie Use Your Telescope" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Annie Use Your Telescope', 'leadx' ); ?></option>
			<option value="Anonymous Pro" <?php if( "Anonymous Pro" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Anonymous Pro', 'leadx' ); ?></option>
			<option value="Antic" <?php if( "Antic" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Antic', 'leadx' ); ?></option>
			<option value="Antic Didone" <?php if( "Antic Didone" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Antic Didone', 'leadx' ); ?></option>
			<option value="Antic Slab" <?php if( "Antic Slab" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Antic Slab', 'leadx' ); ?></option>
			<option value="Anton" <?php if( "Anton" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Anton', 'leadx' ); ?></option>
			<option value="Arapey" <?php if( "Arapey" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Arapey', 'leadx' ); ?></option>
			<option value="Arbutus" <?php if( "Arbutus" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Arbutus', 'leadx' ); ?></option>
			<option value="Arbutus Slab" <?php if( "Arbutus Slab" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Arbutus Slab', 'leadx' ); ?></option>
			<option value="Architects Daughter" <?php if( "Architects Daughter" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Architects Daughter', 'leadx' ); ?></option>
			<option value="Archivo Black" <?php if( "Archivo Black" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Archivo Black', 'leadx' ); ?></option>
			<option value="Archivo Narrow" <?php if( "Archivo Narrow" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Archivo Narrow', 'leadx' ); ?></option>
			<option value="Arimo" <?php if( "Arimo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Arimo', 'leadx' ); ?></option>
			<option value="Arizonia" <?php if( "Arizonia" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Arizonia', 'leadx' ); ?></option>
			<option value="Armata" <?php if( "Armata" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Armata', 'leadx' ); ?></option>
			<option value="Artifika" <?php if( "Artifika" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Artifika', 'leadx' ); ?></option>
			<option value="Arvo" <?php if( "Arvo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Arvo', 'leadx' ); ?></option>
			<option value="Asap" <?php if( "Asap" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Asap', 'leadx' ); ?></option>
			<option value="Asset" <?php if( "Asset" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Asset', 'leadx' ); ?></option>
			<option value="Astloch" <?php if( "Astloch" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Astloch', 'leadx' ); ?></option>
			<option value="Asul" <?php if( "Asul" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Asul', 'leadx' ); ?></option>
			<option value="Atomic Age" <?php if( "Atomic Age" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Atomic Age', 'leadx' ); ?></option>
			<option value="Aubrey" <?php if( "Aubrey" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Aubrey', 'leadx' ); ?></option>
			<option value="Audiowide" <?php if( "Audiowide" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Audiowide', 'leadx' ); ?></option>
			<option value="Autour One" <?php if( "Autour One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Autour One', 'leadx' ); ?></option>
			<option value="Average" <?php if( "Average" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Average', 'leadx' ); ?></option>
			<option value="Average Sans" <?php if( "Average Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Average Sans', 'leadx' ); ?></option>
			<option value="Averia Gruesa Libre" <?php if( "Averia Gruesa Libre" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Averia Gruesa Libre', 'leadx' ); ?></option>
			<option value="Averia Libre" <?php if( "Averia Libre" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Averia Libre', 'leadx' ); ?></option>
			<option value="Averia Sans Libre" <?php if( "Averia Sans Libre" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Averia Sans Libre', 'leadx' ); ?></option>
			<option value="Averia Serif Libre" <?php if( "Averia Serif Libre" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Averia Serif Libre', 'leadx' ); ?></option>
			<option value="Bad Script" <?php if( "Bad Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bad Script', 'leadx' ); ?></option>
			<option value="Balthazar" <?php if( "Balthazar" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Balthazar', 'leadx' ); ?></option>
			<option value="Bangers" <?php if( "Bangers" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bangers', 'leadx' ); ?></option>
			<option value="Basic" <?php if( "Basic" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Basic', 'leadx' ); ?></option>
			<option value="Battambang" <?php if( "Battambang" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Battambang', 'leadx' ); ?></option>
			<option value="Baumans" <?php if( "Baumans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Baumans', 'leadx' ); ?></option>
			<option value="Bayon" <?php if( "Bayon" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bayon', 'leadx' ); ?></option>
			<option value="Belgrano" <?php if( "Belgrano" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Belgrano', 'leadx' ); ?></option>
			<option value="Belleza" <?php if( "Belleza" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Belleza', 'leadx' ); ?></option>
			<option value="BenchNine" <?php if( "BenchNine" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'BenchNine', 'leadx' ); ?></option>
			<option value="Bentham" <?php if( "Bentham" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bentham', 'leadx' ); ?></option>
			<option value="Berkshire Swash" <?php if( "Berkshire Swash" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Berkshire Swash', 'leadx' ); ?></option>
			<option value="Bevan" <?php if( "Bevan" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bevan', 'leadx' ); ?></option>
			<option value="Bigelow Rules" <?php if( "Bigelow Rules" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bigelow Rules', 'leadx' ); ?></option>
			<option value="Bigshot One" <?php if( "Bigshot One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bigshot One', 'leadx' ); ?></option>
			<option value="Bilbo" <?php if( "Bilbo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bilbo', 'leadx' ); ?></option>
			<option value="Bilbo Swash Caps" <?php if( "Bilbo Swash Caps" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bilbo Swash Caps', 'leadx' ); ?></option>
			<option value="Bitter" <?php if( "Bitter" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bitter', 'leadx' ); ?></option>
			<option value="Black Ops One" <?php if( "Black Ops One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Black Ops One', 'leadx' ); ?></option>
			<option value="Bokor" <?php if( "Bokor" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bokor', 'leadx' ); ?></option>
			<option value="Bonbon" <?php if( "Bonbon" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bonbon', 'leadx' ); ?></option>
			<option value="Boogaloo" <?php if( "Boogaloo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Boogaloo', 'leadx' ); ?></option>
			<option value="Bowlby One" <?php if( "Bowlby One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bowlby One', 'leadx' ); ?></option>
			<option value="Bowlby One SC" <?php if( "Bowlby One SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bowlby One SC', 'leadx' ); ?></option>
			<option value="Brawler" <?php if( "Brawler" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Brawler', 'leadx' ); ?></option>
			<option value="Bree Serif" <?php if( "Bree Serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bree Serif', 'leadx' ); ?></option>
			<option value="Bubblegum Sans" <?php if( "Bubblegum Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bubblegum Sans', 'leadx' ); ?></option>
			<option value="Bubbler One" <?php if( "Bubbler One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Bubbler One', 'leadx' ); ?></option>
			<option value="Buda" <?php if( "Buda" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Buda', 'leadx' ); ?></option>
			<option value="Buenard" <?php if( "Buenard" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Buenard', 'leadx' ); ?></option>
			<option value="Butcherman" <?php if( "Butcherman" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Butcherman', 'leadx' ); ?></option>
			<option value="Butterfly Kids" <?php if( "Butterfly Kids" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Butterfly Kids', 'leadx' ); ?></option>
			<option value="Cabin" <?php if( "Cabin" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cabin', 'leadx' ); ?></option>
			<option value="Cabin Condensed" <?php if( "Cabin Condensed" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cabin Condensed', 'leadx' ); ?></option>
			<option value="Cabin Sketch" <?php if( "Cabin Sketch" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cabin Sketch', 'leadx' ); ?></option>
			<option value="Caesar Dressing" <?php if( "Caesar Dressing" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Caesar Dressing', 'leadx' ); ?></option>
			<option value="Cagliostro" <?php if( "Cagliostro" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cagliostro', 'leadx' ); ?></option>
			<option value="Calligraffitti" <?php if( "Calligraffitti" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Calligraffitti', 'leadx' ); ?></option>
			<option value="Cambo" <?php if( "Cambo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cambo', 'leadx' ); ?></option>
			<option value="Candal" <?php if( "Candal" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Candal', 'leadx' ); ?></option>
			<option value="Cantarell" <?php if( "Cantarell" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cantarell', 'leadx' ); ?></option>
			<option value="Cantata One" <?php if( "Cantata One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cantata One', 'leadx' ); ?></option>
			<option value="Cantora One" <?php if( "Cantora One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cantora One', 'leadx' ); ?></option>
			<option value="Capriola" <?php if( "Capriola" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Capriola', 'leadx' ); ?></option>
			<option value="Cardo" <?php if( "Cardo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cardo', 'leadx' ); ?></option>
			<option value="Carme" <?php if( "Carme" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Carme', 'leadx' ); ?></option>
			<option value="Carrois Gothic" <?php if( "Carrois Gothic" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Carrois Gothic', 'leadx' ); ?></option>
			<option value="Carrois Gothic SC" <?php if( "Carrois Gothic SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Carrois Gothic SC', 'leadx' ); ?></option>
			<option value="Carter One" <?php if( "Carter One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Carter One', 'leadx' ); ?></option>
			<option value="Caudex" <?php if( "Caudex" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Caudex', 'leadx' ); ?></option>
			<option value="Cedarville Cursive" <?php if( "Cedarville Cursive" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cedarville Cursive', 'leadx' ); ?></option>
			<option value="Ceviche One" <?php if( "Ceviche One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ceviche One', 'leadx' ); ?></option>
			<option value="Changa One" <?php if( "Changa One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Changa One', 'leadx' ); ?></option>
			<option value="Chango" <?php if( "Chango" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Chango', 'leadx' ); ?></option>
			<option value="Chau Philomene One" <?php if( "Chau Philomene One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Chau Philomene One', 'leadx' ); ?></option>
			<option value="Chela One" <?php if( "Chela One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Chela One', 'leadx' ); ?></option>
			<option value="Chelsea Market" <?php if( "Chelsea Market" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Chelsea Market', 'leadx' ); ?></option>
			<option value="Chenla" <?php if( "Chenla" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Chenla', 'leadx' ); ?></option>
			<option value="Cherry Cream Soda" <?php if( "Cherry Cream Soda" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cherry Cream Soda', 'leadx' ); ?></option>
			<option value="Cherry Swash" <?php if( "Cherry Swash" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cherry Swash', 'leadx' ); ?></option>
			<option value="Chewy" <?php if( "Chewy" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Chewy', 'leadx' ); ?></option>
			<option value="Chicle" <?php if( "Chicle" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Chicle', 'leadx' ); ?></option>
			<option value="Chivo" <?php if( "Chivo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Chivo', 'leadx' ); ?></option>
			<option value="Cinzel" <?php if( "Cinzel" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cinzel', 'leadx' ); ?></option>
			<option value="Cinzel Decorative" <?php if( "Cinzel Decorative" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cinzel Decorative', 'leadx' ); ?></option>
			<option value="Clicker Script" <?php if( "Clicker Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Clicker Script', 'leadx' ); ?></option>
			<option value="Coda" <?php if( "Coda" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Coda', 'leadx' ); ?></option>
			<option value="Coda Caption" <?php if( "Coda Caption" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Coda Caption', 'leadx' ); ?></option>
			<option value="Codystar" <?php if( "Codystar" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Codystar', 'leadx' ); ?></option>
			<option value="Combo" <?php if( "Combo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Combo', 'leadx' ); ?></option>
			<option value="Comfortaa" <?php if( "Comfortaa" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Comfortaa', 'leadx' ); ?></option>
			<option value="Coming Soon" <?php if( "Coming Soon" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Coming Soon', 'leadx' ); ?></option>
			<option value="Concert One" <?php if( "Concert One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Concert One', 'leadx' ); ?></option>
			<option value="Condiment" <?php if( "Condiment" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Condiment', 'leadx' ); ?></option>
			<option value="Content" <?php if( "Content" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Content', 'leadx' ); ?></option>
			<option value="Contrail One" <?php if( "Contrail One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Contrail One', 'leadx' ); ?></option>
			<option value="Convergence" <?php if( "Convergence" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Convergence', 'leadx' ); ?></option>
			<option value="Cookie" <?php if( "Cookie" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cookie', 'leadx' ); ?></option>
			<option value="Copse" <?php if( "Copse" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Copse', 'leadx' ); ?></option>
			<option value="Corben" <?php if( "Corben" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Corben', 'leadx' ); ?></option>
			<option value="Courgette" <?php if( "Courgette" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Courgette', 'leadx' ); ?></option>
			<option value="Cousine" <?php if( "Cousine" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cousine', 'leadx' ); ?></option>
			<option value="Coustard" <?php if( "Coustard" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Coustard', 'leadx' ); ?></option>
			<option value="Covered By Your Grace" <?php if( "Covered By Your Grace" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Covered By Your Grace', 'leadx' ); ?></option>
			<option value="Crafty Girls" <?php if( "Crafty Girls" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Crafty Girls', 'leadx' ); ?></option>
			<option value="Creepster" <?php if( "Creepster" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Creepster', 'leadx' ); ?></option>
			<option value="Crete Round" <?php if( "Crete Round" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Crete Round', 'leadx' ); ?></option>
			<option value="Crimson Text" <?php if( "Crimson Text" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Crimson Text', 'leadx' ); ?></option>
			<option value="Croissant One" <?php if( "Croissant One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Croissant One', 'leadx' ); ?></option>
			<option value="Crushed" <?php if( "Crushed" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Crushed', 'leadx' ); ?></option>
			<option value="Cuprum" <?php if( "Cuprum" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cuprum', 'leadx' ); ?></option>
			<option value="Cutive" <?php if( "Cutive" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cutive', 'leadx' ); ?></option>
			<option value="Cutive Mono" <?php if( "Cutive Mono" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Cutive Mono', 'leadx' ); ?></option>
			<option value="Damion" <?php if( "Damion" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Damion', 'leadx' ); ?></option>
			<option value="Dancing Script" <?php if( "Dancing Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Dancing Script', 'leadx' ); ?></option>
			<option value="Dangrek" <?php if( "Dangrek" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Dangrek', 'leadx' ); ?></option>
			<option value="Dawning of a New Day" <?php if( "Dawning of a New Day" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Dawning of a New Day', 'leadx' ); ?></option>
			<option value="Days One" <?php if( "Days One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Days One', 'leadx' ); ?></option>
			<option value="Delius" <?php if( "Delius" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Delius', 'leadx' ); ?></option>
			<option value="Delius Swash Caps" <?php if( "Delius Swash Caps" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Delius Swash Caps', 'leadx' ); ?></option>
			<option value="Delius Unicase" <?php if( "Delius Unicase" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Delius Unicase', 'leadx' ); ?></option>
			<option value="Della Respira" <?php if( "Della Respira" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Della Respira', 'leadx' ); ?></option>
			<option value="Denk One" <?php if( "Denk One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Denk One', 'leadx' ); ?></option>
			<option value="Devonshire" <?php if( "Devonshire" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Devonshire', 'leadx' ); ?></option>
			<option value="Didact Gothic" <?php if( "Didact Gothic" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Didact Gothic', 'leadx' ); ?></option>
			<option value="Diplomata" <?php if( "Diplomata" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Diplomata', 'leadx' ); ?></option>
			<option value="Diplomata SC" <?php if( "Diplomata SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Diplomata SC', 'leadx' ); ?></option>
			<option value="Domine" <?php if( "Domine" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Domine', 'leadx' ); ?></option>
			<option value="Donegal One" <?php if( "Donegal One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Donegal One', 'leadx' ); ?></option>
			<option value="Doppio One" <?php if( "Doppio One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Doppio One', 'leadx' ); ?></option>
			<option value="Dorsa" <?php if( "Dorsa" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Dorsa', 'leadx' ); ?></option>
			<option value="Dosis" <?php if( "Dosis" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Dosis', 'leadx' ); ?></option>
			<option value="Dr Sugiyama" <?php if( "Dr Sugiyama" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Dr Sugiyama', 'leadx' ); ?></option>
			<option value="Droid Sans" <?php if( "Droid Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Droid Sans', 'leadx' ); ?></option>
			<option value="Droid Sans Mono" <?php if( "Droid Sans Mono" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Droid Sans Mono', 'leadx' ); ?></option>
			<option value="Droid Serif" <?php if( "Droid Serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Droid Serif', 'leadx' ); ?></option>
			<option value="Duru Sans" <?php if( "Duru Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Duru Sans', 'leadx' ); ?></option>
			<option value="Dynalight" <?php if( "Dynalight" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Dynalight', 'leadx' ); ?></option>
			<option value="EB Garamond" <?php if( "EB Garamond" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'EB Garamond', 'leadx' ); ?></option>
			<option value="Eagle Lake" <?php if( "Eagle Lake" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Eagle Lake', 'leadx' ); ?></option>
			<option value="Eater" <?php if( "Eater" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Eater', 'leadx' ); ?></option>
			<option value="Economica" <?php if( "Economica" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Economica', 'leadx' ); ?></option>
			<option value="Ek Mukta" <?php if( "Ek Mukta" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ek Mukta', 'leadx' ); ?></option>
			<option value="Electrolize" <?php if( "Electrolize" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Electrolize', 'leadx' ); ?></option>
			<option value="Elsie" <?php if( "Elsie" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Elsie', 'leadx' ); ?></option>
			<option value="Elsie Swash Caps" <?php if( "Elsie Swash Caps" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Elsie Swash Caps', 'leadx' ); ?></option>
			<option value="Emblema One" <?php if( "Emblema One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Emblema One', 'leadx' ); ?></option>
			<option value="Emilys Candy" <?php if( "Emilys Candy" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Emilys Candy', 'leadx' ); ?></option>
			<option value="Engagement" <?php if( "Engagement" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Engagement', 'leadx' ); ?></option>
			<option value="Englebert" <?php if( "Englebert" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Englebert', 'leadx' ); ?></option>
			<option value="Enriqueta" <?php if( "Enriqueta" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Enriqueta', 'leadx' ); ?></option>
			<option value="Erica One" <?php if( "Erica One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Erica One', 'leadx' ); ?></option>
			<option value="Esteban" <?php if( "Esteban" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Esteban', 'leadx' ); ?></option>
			<option value="Euphoria Script" <?php if( "Euphoria Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Euphoria Script', 'leadx' ); ?></option>
			<option value="Ewert" <?php if( "Ewert" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ewert', 'leadx' ); ?></option>
			<option value="Exo" <?php if( "Exo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Exo', 'leadx' ); ?></option>
			<option value="Exo 2" <?php if( "Exo 2" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Exo 2', 'leadx' ); ?></option>
			<option value="Expletus Sans" <?php if( "Expletus Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Expletus Sans', 'leadx' ); ?></option>
			<option value="Fanwood Text" <?php if( "Fanwood Text" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fanwood Text', 'leadx' ); ?></option>
			<option value="Fascinate" <?php if( "Fascinate" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fascinate', 'leadx' ); ?></option>
			<option value="Fascinate Inline" <?php if( "Fascinate Inline" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fascinate Inline', 'leadx' ); ?></option>
			<option value="Faster One" <?php if( "Faster One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Faster One', 'leadx' ); ?></option>
			<option value="Fasthand" <?php if( "Fasthand" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fasthand', 'leadx' ); ?></option>
			<option value="Fauna One" <?php if( "Fauna One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fauna One', 'leadx' ); ?></option>
			<option value="Federant" <?php if( "Federant" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Federant', 'leadx' ); ?></option>
			<option value="Federo" <?php if( "Federo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Federo', 'leadx' ); ?></option>
			<option value="Felipa" <?php if( "Felipa" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Felipa', 'leadx' ); ?></option>
			<option value="Fenix" <?php if( "Fenix" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fenix', 'leadx' ); ?></option>
			<option value="Finger Paint" <?php if( "Finger Paint" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Finger Paint', 'leadx' ); ?></option>
			<option value="Fira Mono" <?php if( "Fira Mono" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fira Mono', 'leadx' ); ?></option>
			<option value="Fira Sans" <?php if( "Fira Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fira Sans', 'leadx' ); ?></option>
			<option value="Fjalla One" <?php if( "Fjalla One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fjalla One', 'leadx' ); ?></option>
			<option value="Fjord One" <?php if( "Fjord One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fjord One', 'leadx' ); ?></option>
			<option value="Flamenco" <?php if( "Flamenco" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Flamenco', 'leadx' ); ?></option>
			<option value="Flavors" <?php if( "Flavors" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Flavors', 'leadx' ); ?></option>
			<option value="Fondamento" <?php if( "Fondamento" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fondamento', 'leadx' ); ?></option>
			<option value="Fontdiner Swanky" <?php if( "Fontdiner Swanky" == $this_val ) echo 'selected="selected"'; ?>>Fontdiner Swanky</option>
			<option value="Forum" <?php if( "Forum" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Forum', 'leadx' ); ?></option>
			<option value="Francois One" <?php if( "Francois One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Francois One', 'leadx' ); ?></option>
			<option value="Freckle Face" <?php if( "Freckle Face" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Freckle Face', 'leadx' ); ?></option>
			<option value="Fredericka the Great" <?php if( "Fredericka the Great" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fredericka the Great', 'leadx' ); ?></option>
			<option value="Fredoka One" <?php if( "Fredoka One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fredoka One', 'leadx' ); ?></option>
			<option value="Freehand" <?php if( "Freehand" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Freehand', 'leadx' ); ?></option>
			<option value="Fresca" <?php if( "Fresca" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fresca', 'leadx' ); ?></option>
			<option value="Frijole" <?php if( "Frijole" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Frijole', 'leadx' ); ?></option>
			<option value="Fruktur" <?php if( "Fruktur" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fruktur', 'leadx' ); ?></option>
			<option value="Fugaz One" <?php if( "Fugaz One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Fugaz One', 'leadx' ); ?></option>
			<option value="GFS Didot" <?php if( "GFS Didot" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'GFS Didot', 'leadx' ); ?></option>
			<option value="GFS Neohellenic" <?php if( "GFS Neohellenic" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'GFS Neohellenic', 'leadx' ); ?></option>
			<option value="Gabriela" <?php if( "Gabriela" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Gabriela', 'leadx' ); ?></option>
			<option value="Gafata" <?php if( "Gafata" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Gafata', 'leadx' ); ?></option>
			<option value="Galdeano" <?php if( "Galdeano" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Galdeano', 'leadx' ); ?></option>
			<option value="Galindo" <?php if( "Galindo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Galindo', 'leadx' ); ?></option>
			<option value="Gentium Basic" <?php if( "Gentium Basic" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Gentium Basic', 'leadx' ); ?></option>
			<option value="Gentium Book Basic" <?php if( "Gentium Book Basic" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Gentium Book Basic', 'leadx' ); ?></option>
			<option value="Geo" <?php if( "Geo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Geo', 'leadx' ); ?></option>
			<option value="Geostar" <?php if( "Geostar" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Geostar', 'leadx' ); ?></option>
			<option value="Geostar Fill" <?php if( "Geostar Fill" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Geostar Fill', 'leadx' ); ?></option>
			<option value="Germania One" <?php if( "Germania One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Germania One', 'leadx' ); ?></option>
			<option value="Gilda Display" <?php if( "Gilda Display" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Gilda Display', 'leadx' ); ?></option>
			<option value="Give You Glory" <?php if( "Give You Glory" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Give You Glory', 'leadx' ); ?></option>
			<option value="Glass Antiqua" <?php if( "Glass Antiqua" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Glass Antiqua', 'leadx' ); ?></option>
			<option value="Glegoo" <?php if( "Glegoo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Glegoo', 'leadx' ); ?></option>
			<option value="Gloria Hallelujah" <?php if( "Gloria Hallelujah" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Gloria Hallelujah', 'leadx' ); ?></option>
			<option value="Goblin One" <?php if( "Goblin One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Goblin One', 'leadx' ); ?></option>
			<option value="Gochi Hand" <?php if( "Gochi Hand" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Gochi Hand', 'leadx' ); ?></option>
			<option value="Gorditas" <?php if( "Gorditas" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Gorditas', 'leadx' ); ?></option>
			<option value="Goudy Bookletter 1911" <?php if( "Goudy Bookletter 1911" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Goudy Bookletter 1911', 'leadx' ); ?></option>
			<option value="Graduate" <?php if( "Graduate" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Graduate', 'leadx' ); ?></option>
			<option value="Grand Hotel" <?php if( "Grand Hotel" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Grand Hotel', 'leadx' ); ?></option>
			<option value="Gravitas One" <?php if( "Gravitas One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Gravitas One', 'leadx' ); ?></option>
			<option value="Great Vibes" <?php if( "Great Vibes" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Great Vibes', 'leadx' ); ?></option>
			<option value="Griffy" <?php if( "Griffy" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Griffy', 'leadx' ); ?></option>
			<option value="Gruppo" <?php if( "Gruppo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Gruppo', 'leadx' ); ?></option>
			<option value="Gudea" <?php if( "Gudea" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Gudea', 'leadx' ); ?></option>
			<option value="Habibi" <?php if( "Habibi" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Habibi', 'leadx' ); ?></option>
			<option value="Halant" <?php if( "Halant" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Halant', 'leadx' ); ?></option>
			<option value="Hammersmith One" <?php if( "Hammersmith One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Hammersmith One', 'leadx' ); ?></option>
			<option value="Hanalei" <?php if( "Hanalei" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Hanalei', 'leadx' ); ?></option>
			<option value="Hanalei Fill" <?php if( "Hanalei Fill" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Hanalei Fill', 'leadx' ); ?></option>
			<option value="Handlee" <?php if( "Handlee" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Handlee', 'leadx' ); ?></option>
			<option value="Hanuman" <?php if( "Hanuman" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Hanuman', 'leadx' ); ?></option>
			<option value="Happy Monkey" <?php if( "Happy Monkey" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Happy Monkey', 'leadx' ); ?></option>
			<option value="Headland One" <?php if( "Headland One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Headland One', 'leadx' ); ?></option>
			<option value="Henny Penny" <?php if( "Henny Penny" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Henny Penny', 'leadx' ); ?></option>
			<option value="Herr Von Muellerhoff" <?php if( "Herr Von Muellerhoff" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Herr Von Muellerhoff', 'leadx' ); ?></option>
			<option value="Hind" <?php if( "Hind" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Hind', 'leadx' ); ?></option>
			<option value="Holtwood One SC" <?php if( "Holtwood One SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Holtwood One SC', 'leadx' ); ?></option>
			<option value="Homemade Apple" <?php if( "Homemade Apple" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Homemade Apple', 'leadx' ); ?></option>
			<option value="Homenaje" <?php if( "Homenaje" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Homenaje', 'leadx' ); ?></option>
			<option value="IM Fell DW Pica" <?php if( "IM Fell DW Pica" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'IM Fell DW Pica', 'leadx' ); ?></option>
			<option value="IM Fell DW Pica SC" <?php if( "IM Fell DW Pica SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'IM Fell DW Pica SC', 'leadx' ); ?></option>
			<option value="IM Fell Double Pica" <?php if( "IM Fell Double Pica" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'IM Fell Double Pica', 'leadx' ); ?></option>
			<option value="IM Fell Double Pica SC" <?php if( "IM Fell Double Pica SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'IM Fell Double Pica SC', 'leadx' ); ?></option>
			<option value="IM Fell English" <?php if( "IM Fell English" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'IM Fell English', 'leadx' ); ?></option>
			<option value="IM Fell English SC" <?php if( "IM Fell English SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'IM Fell English SC', 'leadx' ); ?></option>
			<option value="IM Fell French Canon" <?php if( "IM Fell French Canon" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'IM Fell French Canon', 'leadx' ); ?></option>
			<option value="IM Fell French Canon SC" <?php if( "IM Fell French Canon SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'IM Fell French Canon SC', 'leadx' ); ?></option>
			<option value="IM Fell Great Primer" <?php if( "IM Fell Great Primer" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'IM Fell Great Primer', 'leadx' ); ?></option>
			<option value="IM Fell Great Primer SC" <?php if( "IM Fell Great Primer SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'IM Fell Great Primer SC', 'leadx' ); ?></option>
			<option value="Iceberg" <?php if( "Iceberg" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Iceberg', 'leadx' ); ?></option>
			<option value="Iceland" <?php if( "Iceland" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Iceland', 'leadx' ); ?></option>
			<option value="Imprima" <?php if( "Imprima" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Imprima', 'leadx' ); ?></option>
			<option value="Inconsolata" <?php if( "Inconsolata" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Inconsolata', 'leadx' ); ?></option>
			<option value="Inder" <?php if( "Inder" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Inder', 'leadx' ); ?></option>
			<option value="Indie Flower" <?php if( "Indie Flower" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Indie Flower', 'leadx' ); ?></option>
			<option value="Inika" <?php if( "Inika" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Inika', 'leadx' ); ?></option>
			<option value="Irish Grover" <?php if( "Irish Grover" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Irish Grover', 'leadx' ); ?></option>
			<option value="Istok Web" <?php if( "Istok Web" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Istok Web', 'leadx' ); ?></option>
			<option value="Italiana" <?php if( "Italiana" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Italiana', 'leadx' ); ?></option>
			<option value="Italianno" <?php if( "Italianno" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Italianno', 'leadx' ); ?></option>
			<option value="Jacques Francois" <?php if( "Jacques Francois" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Jacques Francois', 'leadx' ); ?></option>
			<option value="Jacques Francois Shadow" <?php if( "Jacques Francois Shadow" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Jacques Francois Shadow', 'leadx' ); ?></option>
			<option value="Jim Nightshade" <?php if( "Jim Nightshade" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Jim Nightshade', 'leadx' ); ?></option>
			<option value="Jockey One" <?php if( "Jockey One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( '>Jockey One', 'leadx' ); ?></option>
			<option value="Jolly Lodger" <?php if( "Jolly Lodger" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( '>Jolly Lodger', 'leadx' ); ?></option>
			<option value="Josefin Sans" <?php if( "Josefin Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( '>Josefin Sans', 'leadx' ); ?></option>
			<option value="Josefin Slab" <?php if( "Josefin Slab" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Josefin Slab', 'leadx' ); ?></option>
			<option value="Joti One" <?php if( "Joti One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Joti One', 'leadx' ); ?></option>
			<option value="Judson" <?php if( "Judson" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Judson', 'leadx' ); ?></option>
			<option value="Julee" <?php if( "Julee" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Julee', 'leadx' ); ?></option>
			<option value="Julius Sans One" <?php if( "Julius Sans One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Julius Sans One', 'leadx' ); ?></option>
			<option value="Junge" <?php if( "Junge" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Junge', 'leadx' ); ?></option>
			<option value="Jura" <?php if( "Jura" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Jura', 'leadx' ); ?></option>
			<option value="Just Another Hand" <?php if( "Just Another Hand" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Just Another Hand', 'leadx' ); ?></option>
			<option value="Just Me Again Down Here" <?php if( "Just Me Again Down Here" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Just Me Again Down Here', 'leadx' ); ?></option>
			<option value="Kalam" <?php if( "Kalam" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kalam', 'leadx' ); ?></option>
			<option value="Kameron" <?php if( "Kameron" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kameron', 'leadx' ); ?></option>
			<option value="Kantumruy" <?php if( "Kantumruy" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kantumruy', 'leadx' ); ?></option>
			<option value="Karla" <?php if( "Karla" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Karla', 'leadx' ); ?></option>
			<option value="Karma" <?php if( "Karma" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Karma', 'leadx' ); ?></option>
			<option value="Kaushan Script" <?php if( "Kaushan Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kaushan Script', 'leadx' ); ?></option>
			<option value="Kavoon" <?php if( "Kavoon" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kavoon', 'leadx' ); ?></option>
			<option value="Kdam Thmor" <?php if( "Kdam Thmor" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kdam Thmor', 'leadx' ); ?></option>
			<option value="Keania One" <?php if( "Keania One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Keania One', 'leadx' ); ?></option>
			<option value="Kelly Slab" <?php if( "Kelly Slab" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kelly Slab', 'leadx' ); ?></option>
			<option value="Kenia" <?php if( "Kenia" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kenia', 'leadx' ); ?></option>
			<option value="Khand" <?php if( "Khand" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Khand', 'leadx' ); ?></option>
			<option value="Khmer" <?php if( "Khmer" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Khmer', 'leadx' ); ?></option>
			<option value="Kite One" <?php if( "Kite One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kite One', 'leadx' ); ?></option>
			<option value="Knewave" <?php if( "Knewave" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Knewave', 'leadx' ); ?></option>
			<option value="Kotta One" <?php if( "Kotta One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kotta One', 'leadx' ); ?></option>
			<option value="Koulen" <?php if( "Koulen" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Koulen', 'leadx' ); ?></option>
			<option value="Kranky" <?php if( "Kranky" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kranky', 'leadx' ); ?></option>
			<option value="Kreon" <?php if( "Kreon" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kreon', 'leadx' ); ?></option>
			<option value="Kristi" <?php if( "Kristi" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Kristi', 'leadx' ); ?></option>
			<option value="Krona One" <?php if( "Krona One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Krona One', 'leadx' ); ?></option>
			<option value="La Belle Aurore" <?php if( "La Belle Aurore" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'La Belle Aurore', 'leadx' ); ?></option>
			<option value="Laila" <?php if( "Laila" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Laila', 'leadx' ); ?></option>
			<option value="Lancelot" <?php if( "Lancelot" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lancelot', 'leadx' ); ?></option>
			<option value="Lato" <?php if( "Lato" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lato', 'leadx' ); ?></option>
			<option value="League Script" <?php if( "League Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'League Script', 'leadx' ); ?></option>
			<option value="Leckerli One" <?php if( "Leckerli One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Leckerli One', 'leadx' ); ?></option>
			<option value="Ledger" <?php if( "Ledger" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ledger', 'leadx' ); ?></option>
			<option value="Lekton" <?php if( "Lekton" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lekton', 'leadx' ); ?></option>
			<option value="Lemon" <?php if( "Lemon" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lemon', 'leadx' ); ?></option>
			<option value="Libre Baskerville" <?php if( "Libre Baskerville" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Libre Baskerville', 'leadx' ); ?></option>
			<option value="Life Savers" <?php if( "Life Savers" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Life Savers', 'leadx' ); ?></option>
			<option value="Lilita One" <?php if( "Lilita One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lilita One', 'leadx' ); ?></option>
			<option value="Lily Script One" <?php if( "Lily Script One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lily Script One', 'leadx' ); ?></option>
			<option value="Limelight" <?php if( "Limelight" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Limelight', 'leadx' ); ?></option>
			<option value="Linden Hill" <?php if( "Linden Hill" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Linden Hill', 'leadx' ); ?></option>
			<option value="Lobster" <?php if( "Lobster" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lobster', 'leadx' ); ?></option>
			<option value="Lobster Two" <?php if( "Lobster Two" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lobster Two', 'leadx' ); ?></option>
			<option value="Londrina Outline" <?php if( "Londrina Outline" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Londrina Outline', 'leadx' ); ?></option>
			<option value="Londrina Shadow" <?php if( "Londrina Shadow" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Londrina Shadow', 'leadx' ); ?></option>
			<option value="Londrina Sketch" <?php if( "Londrina Sketch" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Londrina Sketch', 'leadx' ); ?></option>
			<option value="Londrina Solid" <?php if( "Londrina Solid" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Londrina Solid', 'leadx' ); ?></option>
			<option value="Lora" <?php if( "Lora" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lora', 'leadx' ); ?></option>
			<option value="Love Ya Like A Sister" <?php if( "Love Ya Like A Sister" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Love Ya Like A Sister', 'leadx' ); ?></option>
			<option value="Loved by the King" <?php if( "Loved by the King" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Loved by the King', 'leadx' ); ?></option>
			<option value="Lovers Quarrel" <?php if( "Lovers Quarrel" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lovers Quarrel', 'leadx' ); ?></option>
			<option value="Luckiest Guy" <?php if( "Luckiest Guy" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Luckiest Guy', 'leadx' ); ?></option>
			<option value="Lusitana" <?php if( "Lusitana" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lusitana', 'leadx' ); ?></option>
			<option value="Lustria" <?php if( "Lustria" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Lustria', 'leadx' ); ?></option>
			<option value="Macondo" <?php if( "Macondo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Macondo', 'leadx' ); ?></option>
			<option value="Macondo Swash Caps" <?php if( "Macondo Swash Caps" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Macondo Swash Caps', 'leadx' ); ?></option>
			<option value="Magra" <?php if( "Magra" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Magra', 'leadx' ); ?></option>
			<option value="Maiden Orange" <?php if( "Maiden Orange" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Maiden Orange', 'leadx' ); ?></option>
			<option value="Mako" <?php if( "Mako" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Mako', 'leadx' ); ?></option>
			<option value="Marcellus" <?php if( "Marcellus" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Marcellus', 'leadx' ); ?></option>
			<option value="Marcellus SC" <?php if( "Marcellus SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Marcellus SC', 'leadx' ); ?></option>
			<option value="Marck Script" <?php if( "Marck Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Marck Script', 'leadx' ); ?></option>
			<option value="Margarine" <?php if( "Margarine" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Margarine', 'leadx' ); ?></option>
			<option value="Marko One" <?php if( "Marko One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Marko One', 'leadx' ); ?></option>
			<option value="Marmelad" <?php if( "Marmelad" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Marmelad', 'leadx' ); ?></option>
			<option value="Marvel" <?php if( "Marvel" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Marvel', 'leadx' ); ?></option>
			<option value="Mate" <?php if( "Mate" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Mate', 'leadx' ); ?></option>
			<option value="Mate SC" <?php if( "Mate SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Mate SC', 'leadx' ); ?></option>
			<option value="Maven Pro" <?php if( "Maven Pro" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Maven Pro', 'leadx' ); ?></option>
			<option value="McLaren" <?php if( "McLaren" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'McLaren', 'leadx' ); ?></option>
			<option value="Meddon" <?php if( "Meddon" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Meddon', 'leadx' ); ?></option>
			<option value="MedievalSharp" <?php if( "MedievalSharp" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'MedievalSharp', 'leadx' ); ?></option>
			<option value="Medula One" <?php if( "Medula One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Medula One', 'leadx' ); ?></option>
			<option value="Megrim" <?php if( "Megrim" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Megrim', 'leadx' ); ?></option>
			<option value="Meie Script" <?php if( "Meie Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Meie Script', 'leadx' ); ?></option>
			<option value="Merienda" <?php if( "Merienda" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Merienda', 'leadx' ); ?></option>
			<option value="Merienda One" <?php if( "Merienda One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Merienda One', 'leadx' ); ?></option>
			<option value="Merriweather" <?php if( "Merriweather" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Merriweather', 'leadx' ); ?></option>
			<option value="Merriweather Sans" <?php if( "Merriweather Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Merriweather Sans', 'leadx' ); ?></option>
			<option value="Metal" <?php if( "Metal" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Metal', 'leadx' ); ?></option>
			<option value="Metal Mania" <?php if( "Metal Mania" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Metal Mania', 'leadx' ); ?></option>
			<option value="Metamorphous" <?php if( "Metamorphous" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Metamorphous', 'leadx' ); ?></option>
			<option value="Metrophobic" <?php if( "Metrophobic" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Metrophobic', 'leadx' ); ?></option>
			<option value="Michroma" <?php if( "Michroma" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Michroma', 'leadx' ); ?></option>
			<option value="Milonga" <?php if( "Milonga" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Milonga', 'leadx' ); ?></option>
			<option value="Miltonian" <?php if( "Miltonian" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Miltonian', 'leadx' ); ?></option>
			<option value="Miltonian Tattoo" <?php if( "Miltonian Tattoo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Miltonian Tattoo', 'leadx' ); ?></option>
			<option value="Miniver" <?php if( "Miniver" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Miniver', 'leadx' ); ?></option>
			<option value="Miss Fajardose" <?php if( "Miss Fajardose" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Miss Fajardose', 'leadx' ); ?></option>
			<option value="Modern Antiqua" <?php if( "Modern Antiqua" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Modern Antiqua', 'leadx' ); ?></option>
			<option value="Molengo" <?php if( "Molengo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Molengo', 'leadx' ); ?></option>
			<option value="Molle" <?php if( "Molle" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Molle', 'leadx' ); ?></option>
			<option value="Monda" <?php if( "Monda" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Monda', 'leadx' ); ?></option>
			<option value="Monofett" <?php if( "Monofett" == $this_val ) echo 'selected="selected"'; ?>>Monofett</option>
			<option value="Monoton" <?php if( "Monoton" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Monoton', 'leadx' ); ?></option>
			<option value="Monsieur La Doulaise" <?php if( "Monsieur La Doulaise" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Monsieur La Doulaise', 'leadx' ); ?></option>
			<option value="Montaga" <?php if( "Montaga" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Montaga', 'leadx' ); ?></option>
			<option value="Montez" <?php if( "Montez" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Montez', 'leadx' ); ?></option>
			<option value="Montserrat" <?php if( "Montserrat" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Montserrat', 'leadx' ); ?></option>
			<option value="Montserrat Alternates" <?php if( "Montserrat Alternates" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Montserrat Alternates', 'leadx' ); ?></option>
			<option value="Montserrat Subrayada" <?php if( "Montserrat Subrayada" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Montserrat Subrayada', 'leadx' ); ?></option>
			<option value="Moul" <?php if( "Moul" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Moul', 'leadx' ); ?></option>
			<option value="Moulpali" <?php if( "Moulpali" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Moulpali', 'leadx' ); ?></option>
			<option value="Mountains of Christmas" <?php if( "Mountains of Christmas" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Mountains of Christmas', 'leadx' ); ?></option>
			<option value="Mouse Memoirs" <?php if( "Mouse Memoirs" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Mouse Memoirs', 'leadx' ); ?></option>
			<option value="Mr Bedfort" <?php if( "Mr Bedfort" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Mr Bedfort', 'leadx' ); ?></option>
			<option value="Mr Dafoe" <?php if( "Mr Dafoe" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Mr Dafoe', 'leadx' ); ?></option>
			<option value="Mr De Haviland" <?php if( "Mr De Haviland" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Mr De Haviland', 'leadx' ); ?></option>
			<option value="Mrs Saint Delafield" <?php if( "Mrs Saint Delafield" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Mrs Saint Delafield', 'leadx' ); ?></option>
			<option value="Mrs Sheppards" <?php if( "Mrs Sheppards" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Mrs Sheppards', 'leadx' ); ?></option>
			<option value="Muli" <?php if( "Muli" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Muli', 'leadx' ); ?></option>
			<option value="Mystery Quest" <?php if( "Mystery Quest" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Mystery Quest', 'leadx' ); ?></option>
			<option value="Neucha" <?php if( "Neucha" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Neucha', 'leadx' ); ?></option>
			<option value="Neuton" <?php if( "Neuton" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Neuton', 'leadx' ); ?></option>
			<option value="New Rocker" <?php if( "New Rocker" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'New Rocker', 'leadx' ); ?></option>
			<option value="News Cycle" <?php if( "News Cycle" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'News Cycle', 'leadx' ); ?></option>
			<option value="Niconne" <?php if( "Niconne" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Niconne', 'leadx' ); ?></option>
			<option value="Nixie One" <?php if( "Nixie One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nixie One', 'leadx' ); ?></option>
			<option value="Nobile" <?php if( "Nobile" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nobile', 'leadx' ); ?></option>
			<option value="Nokora" <?php if( "Nokora" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nokora', 'leadx' ); ?></option>
			<option value="Norican" <?php if( "Norican" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Norican', 'leadx' ); ?></option>
			<option value="Nosifer" <?php if( "Nosifer" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nosifer', 'leadx' ); ?></option>
			<option value="Nothing You Could Do" <?php if( "Nothing You Could Do" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nothing You Could Do', 'leadx' ); ?></option>
			<option value="Noticia Text" <?php if( "Noticia Text" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Noticia Text', 'leadx' ); ?></option>
			<option value="Noto Sans" <?php if( "Noto Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Noto Sans', 'leadx' ); ?></option>
			<option value="Noto Serif" <?php if( "Noto Serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( '>Noto Serif', 'leadx' ); ?></option>
			<option value="Nova Cut" <?php if( "Nova Cut" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nova Cut', 'leadx' ); ?></option>
			<option value="Nova Flat" <?php if( "Nova Flat" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nova Flat', 'leadx' ); ?></option>
			<option value="Nova Mono" <?php if( "Nova Mono" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nova Mono', 'leadx' ); ?></option>
			<option value="Nova Oval" <?php if( "Nova Oval" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nova Oval', 'leadx' ); ?></option>
			<option value="Nova Round" <?php if( "Nova Round" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nova Round', 'leadx' ); ?></option>
			<option value="Nova Script" <?php if( "Nova Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nova Script', 'leadx' ); ?></option>
			<option value="Nova Slim" <?php if( "Nova Slim" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nova Slim', 'leadx' ); ?></option>
			<option value="Nova Square" <?php if( "Nova Square" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nova Square', 'leadx' ); ?></option>
			<option value="Numans" <?php if( "Numans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Numans', 'leadx' ); ?></option>
			<option value="Nunito" <?php if( "Nunito" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Nunito', 'leadx' ); ?></option>
			<option value="Odor Mean Chey" <?php if( "Odor Mean Chey" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Odor Mean Chey', 'leadx' ); ?></option>
			<option value="Offside" <?php if( "Offside" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Offside', 'leadx' ); ?></option>
			<option value="Old Standard TT" <?php if( "Old Standard TT" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Old Standard TT', 'leadx' ); ?></option>
			<option value="Oldenburg" <?php if( "Oldenburg" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Oldenburg', 'leadx' ); ?></option>
			<option value="Oleo Script" <?php if( "Oleo Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Oleo Script', 'leadx' ); ?></option>
			<option value="Oleo Script Swash Caps" <?php if( "Oleo Script Swash Caps" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Oleo Script Swash Caps', 'leadx' ); ?></option>
			<option value="Open Sans" <?php if( "Open Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Open Sans', 'leadx' ); ?></option>
			<option value="Open Sans Condensed" <?php if( "Open Sans Condensed" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Open Sans Condensed', 'leadx' ); ?></option>
			<option value="Oranienbaum" <?php if( "Oranienbaum" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Oranienbaum', 'leadx' ); ?></option>
			<option value="Orbitron" <?php if( "Orbitron" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Orbitron', 'leadx' ); ?></option>
			<option value="Oregano" <?php if( "Oregano" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Oregano', 'leadx' ); ?></option>
			<option value="Orienta" <?php if( "Orienta" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Orienta', 'leadx' ); ?></option>
			<option value="Original Surfer" <?php if( "Original Surfer" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Original Surfer', 'leadx' ); ?></option>
			<option value="Oswald" <?php if( "Oswald" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Oswald', 'leadx' ); ?></option>
			<option value="Over the Rainbow" <?php if( "Over the Rainbow" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Over the Rainbow', 'leadx' ); ?></option>
			<option value="Overlock" <?php if( "Overlock" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Overlock', 'leadx' ); ?></option>
			<option value="Overlock SC" <?php if( "Overlock SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Overlock SC', 'leadx' ); ?></option>
			<option value="Ovo" <?php if( "Ovo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ovo', 'leadx' ); ?></option>
			<option value="Oxygen" <?php if( "Oxygen" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Oxygen', 'leadx' ); ?></option>
			<option value="Oxygen Mono" <?php if( "Oxygen Mono" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Oxygen Mono', 'leadx' ); ?></option>
			<option value="PT Mono" <?php if( "PT Mono" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'PT Mono', 'leadx' ); ?></option>
			<option value="PT Sans" <?php if( "PT Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'PT Sans', 'leadx' ); ?></option>
			<option value="PT Sans Caption" <?php if( "PT Sans Caption" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'PT Sans Caption', 'leadx' ); ?></option>
			<option value="PT Sans Narrow" <?php if( "PT Sans Narrow" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'PT Sans Narrow', 'leadx' ); ?></option>
			<option value="PT Serif" <?php if( "PT Serif" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'PT Serif', 'leadx' ); ?></option>
			<option value="PT Serif Caption" <?php if( "PT Serif Caption" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'PT Serif Caption', 'leadx' ); ?></option>
			<option value="Pacifico" <?php if( "Pacifico" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Pacifico', 'leadx' ); ?></option>
			<option value="Paprika" <?php if( "Paprika" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Paprika', 'leadx' ); ?></option>
			<option value="Parisienne" <?php if( "Parisienne" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Parisienne', 'leadx' ); ?></option>
			<option value="Passero One" <?php if( "Passero One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Passero One', 'leadx' ); ?></option>
			<option value="Passion One" <?php if( "Passion One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Passion One', 'leadx' ); ?></option>
			<option value="Pathway Gothic One" <?php if( "Pathway Gothic One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Pathway Gothic One', 'leadx' ); ?></option>
			<option value="Patrick Hand" <?php if( "Patrick Hand" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Patrick Hand', 'leadx' ); ?></option>
			<option value="Patrick Hand SC" <?php if( "Patrick Hand SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Patrick Hand SC', 'leadx' ); ?></option>
			<option value="Patua One" <?php if( "Patua One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Patua One', 'leadx' ); ?></option>
			<option value="Paytone One" <?php if( "Paytone One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Paytone One', 'leadx' ); ?></option>
			<option value="Peralta" <?php if( "Peralta" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Peralta', 'leadx' ); ?></option>
			<option value="Permanent Marker" <?php if( "Permanent Marker" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Permanent Marker', 'leadx' ); ?></option>
			<option value="Petit Formal Script" <?php if( "Petit Formal Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Petit Formal Script', 'leadx' ); ?></option>
			<option value="Petrona" <?php if( "Petrona" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Petrona', 'leadx' ); ?></option>
			<option value="Philosopher" <?php if( "Philosopher" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Philosopher', 'leadx' ); ?></option>
			<option value="Piedra" <?php if( "Piedra" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Piedra', 'leadx' ); ?></option>
			<option value="Pinyon Script" <?php if( "Pinyon Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Pinyon Script', 'leadx' ); ?></option>
			<option value="Pirata One" <?php if( "Pirata One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Pirata One', 'leadx' ); ?></option>
			<option value="Plaster" <?php if( "Plaster" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Plaster', 'leadx' ); ?></option>
			<option value="Play" <?php if( "Play" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Play', 'leadx' ); ?></option>
			<option value="Playball" <?php if( "Playball" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Playball', 'leadx' ); ?></option>
			<option value="Playfair Display" <?php if( "Playfair Display" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Playfair Display', 'leadx' ); ?></option>
			<option value="Playfair Display SC" <?php if( "Playfair Display SC" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Playfair Display SC', 'leadx' ); ?></option>
			<option value="Podkova" <?php if( "Podkova" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Podkova', 'leadx' ); ?></option>
			<option value="Poiret One" <?php if( "Poiret One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Poiret One', 'leadx' ); ?></option>
			<option value="Poller One" <?php if( "Poller One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Poller One', 'leadx' ); ?></option>
			<option value="Poly" <?php if( "Poly" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Poly', 'leadx' ); ?></option>
			<option value="Pompiere" <?php if( "Pompiere" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Pompiere', 'leadx' ); ?></option>
			<option value="Pontano Sans" <?php if( "Pontano Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Pontano Sans', 'leadx' ); ?></option>
			<option value="Port Lligat Sans" <?php if( "Port Lligat Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Port Lligat Sans', 'leadx' ); ?></option>
			<option value="Port Lligat Slab" <?php if( "Port Lligat Slab" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Port Lligat Slab', 'leadx' ); ?></option>
			<option value="Prata" <?php if( "Prata" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Prata', 'leadx' ); ?></option>
			<option value="Preahvihear" <?php if( "Preahvihear" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Preahvihear', 'leadx' ); ?></option>
			<option value="Press Start 2P" <?php if( "Press Start 2P" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Press Start 2P', 'leadx' ); ?></option>
			<option value="Princess Sofia" <?php if( "Princess Sofia" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Princess Sofia', 'leadx' ); ?></option>
			<option value="Prociono" <?php if( "Prociono" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Prociono', 'leadx' ); ?></option>
			<option value="Prosto One" <?php if( "Prosto One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Prosto One', 'leadx' ); ?></option>
			<option value="Puritan" <?php if( "Puritan" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Puritan', 'leadx' ); ?></option>
			<option value="Purple Purse" <?php if( "Purple Purse" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Purple Purse', 'leadx' ); ?></option>
			<option value="Quando" <?php if( "Quando" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Quando', 'leadx' ); ?></option>
			<option value="Quantico" <?php if( "Quantico" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Quantico', 'leadx' ); ?></option>
			<option value="Quattrocento" <?php if( "Quattrocento" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Quattrocento', 'leadx' ); ?></option>
			<option value="Quattrocento Sans" <?php if( "Quattrocento Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Quattrocento Sans', 'leadx' ); ?></option>
			<option value="Questrial" <?php if( "Questrial" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Questrial', 'leadx' ); ?></option>
			<option value="Quicksand" <?php if( "Quicksand" == $this_val ) echo 'selected="selected"'; ?>>Quicksand</option>
			<option value="Quintessential" <?php if( "Quintessential" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Quintessential', 'leadx' ); ?></option>
			<option value="Qwigley" <?php if( "Qwigley" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Qwigley', 'leadx' ); ?></option>
			<option value="Racing Sans One" <?php if( "Racing Sans One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Racing Sans One', 'leadx' ); ?></option>
			<option value="Radley" <?php if( "Radley" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Radley', 'leadx' ); ?></option>
			<option value="Rajdhani" <?php if( "Rajdhani" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rajdhani', 'leadx' ); ?></option>
			<option value="Raleway" <?php if( "Raleway" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Raleway', 'leadx' ); ?></option>
			<option value="Raleway Dots" <?php if( "Raleway Dots" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Raleway Dots', 'leadx' ); ?></option>
			<option value="Rambla" <?php if( "Rambla" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rambla', 'leadx' ); ?></option>
			<option value="Rammetto One" <?php if( "Rammetto One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rammetto One', 'leadx' ); ?></option>
			<option value="Ranchers" <?php if( "Ranchers" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ranchers', 'leadx' ); ?></option>
			<option value="Rancho" <?php if( "Rancho" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rancho', 'leadx' ); ?></option>
			<option value="Rationale" <?php if( "Rationale" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rationale', 'leadx' ); ?></option>
			<option value="Redressed" <?php if( "Redressed" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Redressed', 'leadx' ); ?></option>
			<option value="Reenie Beanie" <?php if( "Reenie Beanie" == $this_val ) echo 'selected="selected"'; ?>>Reenie Beanie</option>
			<option value="Revalia" <?php if( "Revalia" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Revalia', 'leadx' ); ?></option>
			<option value="Ribeye" <?php if( "Ribeye" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ribeye', 'leadx' ); ?></option>
			<option value="Ribeye Marrow" <?php if( "Ribeye Marrow" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ribeye Marrow', 'leadx' ); ?></option>
			<option value="Righteous" <?php if( "Righteous" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Righteous', 'leadx' ); ?></option>
			<option value="Risque" <?php if( "Risque" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Risque', 'leadx' ); ?></option>
			<option value="Roboto" <?php if( "Roboto" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Roboto', 'leadx' ); ?></option>
			<option value="Roboto Condensed" <?php if( "Roboto Condensed" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Roboto Condensed', 'leadx' ); ?></option>
			<option value="Roboto Slab" <?php if( "Roboto Slab" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Roboto Slab', 'leadx' ); ?></option>
			<option value="Rochester" <?php if( "Rochester" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rochester', 'leadx' ); ?></option>
			<option value="Rock Salt" <?php if( "Rock Salt" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rock Salt', 'leadx' ); ?></option>
			<option value="Rokkitt" <?php if( "Rokkitt" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rokkitt', 'leadx' ); ?></option>
			<option value="Romanesco" <?php if( "Romanesco" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Romanesco', 'leadx' ); ?></option>
			<option value="Ropa Sans" <?php if( "Ropa Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ropa Sans', 'leadx' ); ?></option>
			<option value="Rosario" <?php if( "Rosario" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rosario', 'leadx' ); ?></option>
			<option value="Rosarivo" <?php if( "Rosarivo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rosarivo', 'leadx' ); ?></option>
			<option value="Rouge Script" <?php if( "Rouge Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rouge Script', 'leadx' ); ?></option>
			<option value="Rozha One" <?php if( "Rozha One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rozha One', 'leadx' ); ?></option>
			<option value="Rubik Mono One" <?php if( "Rubik Mono One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rubik Mono One', 'leadx' ); ?></option>
			<option value="Rubik One" <?php if( "Rubik One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rubik One', 'leadx' ); ?></option>
			<option value="Ruda" <?php if( "Ruda" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ruda', 'leadx' ); ?></option>
			<option value="Rufina" <?php if( "Rufina" == $this_val ) echo 'selected="selected"'; ?>>Rufina</option>
			<option value="Ruge Boogie" <?php if( "Ruge Boogie" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ruge Boogie', 'leadx' ); ?></option>
			<option value="Ruluko" <?php if( "Ruluko" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ruluko', 'leadx' ); ?></option>
			<option value="Rum Raisin" <?php if( "Rum Raisin" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rum Raisin', 'leadx' ); ?></option>
			<option value="Ruslan Display" <?php if( "Ruslan Display" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ruslan Display', 'leadx' ); ?></option>
			<option value="Russo One" <?php if( "Russo One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Russo One', 'leadx' ); ?></option>
			<option value="Ruthie" <?php if( "Ruthie" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ruthie', 'leadx' ); ?></option>
			<option value="Rye" <?php if( "Rye" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Rye', 'leadx' ); ?></option>
			<option value="Sacramento" <?php if( "Sacramento" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sacramento', 'leadx' ); ?></option>
			<option value="Sail" <?php if( "Sail" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sail', 'leadx' ); ?></option>
			<option value="Salsa" <?php if( "Salsa" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Salsa', 'leadx' ); ?></option>
			<option value="Sanchez" <?php if( "Sanchez" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sanchez', 'leadx' ); ?></option>
			<option value="Sancreek" <?php if( "Sancreek" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sancreek', 'leadx' ); ?></option>
			<option value="Sansita One" <?php if( "Sansita One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sansita One', 'leadx' ); ?></option>
			<option value="Sarina" <?php if( "Sarina" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sarina', 'leadx' ); ?></option>
			<option value="Sarpanch" <?php if( "Sarpanch" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sarpanch', 'leadx' ); ?></option>
			<option value="Satisfy" <?php if( "Satisfy" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Satisfy', 'leadx' ); ?></option>
			<option value="Scada" <?php if( "Scada" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Scada', 'leadx' ); ?></option>
			<option value="Schoolbell" <?php if( "Schoolbell" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Schoolbell', 'leadx' ); ?></option>
			<option value="Seaweed Script" <?php if( "Seaweed Script" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Seaweed Script', 'leadx' ); ?></option>
			<option value="Sevillana" <?php if( "Sevillana" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sevillana', 'leadx' ); ?></option>
			<option value="Seymour One" <?php if( "Seymour One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Seymour One', 'leadx' ); ?></option>
			<option value="Shadows Into Light" <?php if( "Shadows Into Light" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Shadows Into Light', 'leadx' ); ?></option>
			<option value="Shadows Into Light Two" <?php if( "Shadows Into Light Two" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Shadows Into Light Two', 'leadx' ); ?></option>
			<option value="Shanti" <?php if( "Shanti" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Shanti', 'leadx' ); ?></option>
			<option value="Share" <?php if( "Share" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Share', 'leadx' ); ?></option>
			<option value="Share Tech" <?php if( "Share Tech" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Share Tech', 'leadx' ); ?></option>
			<option value="Share Tech Mono" <?php if( "Share Tech Mono" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Share Tech Mono', 'leadx' ); ?></option>
			<option value="Shojumaru" <?php if( "Shojumaru" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Shojumaru', 'leadx' ); ?></option>
			<option value="Short Stack" <?php if( "Short Stack" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Short Stack', 'leadx' ); ?></option>
			<option value="Siemreap" <?php if( "Siemreap" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Siemreap', 'leadx' ); ?></option>
			<option value="Sigmar One" <?php if( "Sigmar One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sigmar One', 'leadx' ); ?></option>
			<option value="Signika" <?php if( "Signika" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Signika', 'leadx' ); ?></option>
			<option value="Signika Negative" <?php if( "Signika Negative" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Signika Negative', 'leadx' ); ?></option>
			<option value="Simonetta" <?php if( "Simonetta" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Simonetta', 'leadx' ); ?></option>
			<option value="Sintony" <?php if( "Sintony" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sintony', 'leadx' ); ?></option>
			<option value="Sirin Stencil" <?php if( "Sirin Stencil" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sirin Stencil', 'leadx' ); ?></option>
			<option value="Six Caps" <?php if( "Six Caps" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Six Caps', 'leadx' ); ?></option>
			<option value="Skranji" <?php if( "Skranji" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Skranji', 'leadx' ); ?></option>
			<option value="Slabo 13px" <?php if( "Slabo 13px" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Slabo 13px', 'leadx' ); ?></option>
			<option value="Slabo 27px" <?php if( "Slabo 27px" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Slabo 27px', 'leadx' ); ?></option>
			<option value="Slackey" <?php if( "Slackey" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Slackey', 'leadx' ); ?></option>
			<option value="Smokum" <?php if( "Smokum" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Smokum', 'leadx' ); ?></option>
			<option value="Smythe" <?php if( "Smythe" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Smythe', 'leadx' ); ?></option>
			<option value="Sniglet" <?php if( "Sniglet" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sniglet', 'leadx' ); ?></option>
			<option value="Snippet" <?php if( "Snippet" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Snippet', 'leadx' ); ?></option>
			<option value="Snowburst One" <?php if( "Snowburst One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Snowburst One', 'leadx' ); ?></option>
			<option value="Sofadi One" <?php if( "Sofadi One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sofadi One', 'leadx' ); ?></option>
			<option value="Sofia" <?php if( "Sofia" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sofia', 'leadx' ); ?></option>
			<option value="Sonsie One" <?php if( "Sonsie One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sonsie One', 'leadx' ); ?></option>
			<option value="Sorts Mill Goudy" <?php if( "Sorts Mill Goudy" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sorts Mill Goudy', 'leadx' ); ?></option>
			<option value="Source Code Pro" <?php if( "Source Code Pro" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Source Code Pro', 'leadx' ); ?></option>
			<option value="Source Sans Pro" <?php if( "Source Sans Pro" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Source Sans Pro', 'leadx' ); ?></option>
			<option value="Source Serif Pro" <?php if( "Source Serif Pro" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Source Serif Pro', 'leadx' ); ?></option>
			<option value="Special Elite" <?php if( "Special Elite" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Special Elite', 'leadx' ); ?></option>
			<option value="Spicy Rice" <?php if( "Spicy Rice" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Spicy Rice', 'leadx' ); ?></option>
			<option value="Spinnaker" <?php if( "Spinnaker" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Spinnaker', 'leadx' ); ?></option>
			<option value="Spirax" <?php if( "Spirax" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Spirax', 'leadx' ); ?></option>
			<option value="Squada One" <?php if( "Squada One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Squada One', 'leadx' ); ?></option>
			<option value="Stalemate" <?php if( "Stalemate" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Stalemate', 'leadx' ); ?></option>
			<option value="Stalinist One" <?php if( "Stalinist One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Stalinist One', 'leadx' ); ?></option>
			<option value="Stardos Stencil" <?php if( "Stardos Stencil" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Stardos Stencil', 'leadx' ); ?></option>
			<option value="Stint Ultra Condensed" <?php if( "Stint Ultra Condensed" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Stint Ultra Condensed', 'leadx' ); ?></option>
			<option value="Stint Ultra Expanded" <?php if( "Stint Ultra Expanded" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Stint Ultra Expanded', 'leadx' ); ?></option>
			<option value="Stoke" <?php if( "Stoke" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Stoke', 'leadx' ); ?></option>
			<option value="Strait" <?php if( "Strait" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Strait', 'leadx' ); ?></option>
			<option value="Sue Ellen Francisco" <?php if( "Sue Ellen Francisco" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sue Ellen Francisco', 'leadx' ); ?></option>
			<option value="Sunshiney" <?php if( "Sunshiney" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Sunshiney', 'leadx' ); ?></option>
			<option value="Supermercado One" <?php if( "Supermercado One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Supermercado One', 'leadx' ); ?></option>
			<option value="Suwannaphum" <?php if( "Suwannaphum" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Suwannaphum', 'leadx' ); ?></option>
			<option value="Swanky and Moo Moo" <?php if( "Swanky and Moo Moo" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Swanky and Moo Moo', 'leadx' ); ?></option>
			<option value="Syncopate" <?php if( "Syncopate" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Syncopate', 'leadx' ); ?></option>
			<option value="Tangerine" <?php if( "Tangerine" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Tangerine', 'leadx' ); ?></option>
			<option value="Taprom" <?php if( "Taprom" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Taprom', 'leadx' ); ?></option>
			<option value="Tauri" <?php if( "Tauri" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Tauri', 'leadx' ); ?></option>
			<option value="Teko" <?php if( "Teko" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Teko', 'leadx' ); ?></option>
			<option value="Telex" <?php if( "Telex" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Telex', 'leadx' ); ?></option>
			<option value="Tenor Sans" <?php if( "Tenor Sans" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Tenor Sans', 'leadx' ); ?></option>
			<option value="Text Me One" <?php if( "Text Me One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Text Me One', 'leadx' ); ?></option>
			<option value="The Girl Next Door" <?php if( "The Girl Next Door" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'The Girl Next Door', 'leadx' ); ?></option>
			<option value="Tienne" <?php if( "Tienne" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Tienne', 'leadx' ); ?></option>
			<option value="Tinos" <?php if( "Tinos" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Tinos', 'leadx' ); ?></option>
			<option value="Titan One" <?php if( "Titan One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Titan One', 'leadx' ); ?></option>
			<option value="Titillium Web" <?php if( "Titillium Web" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Titillium Web', 'leadx' ); ?></option>
			<option value="Trade Winds" <?php if( "Trade Winds" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Trade Winds', 'leadx' ); ?></option>
			<option value="Trocchi" <?php if( "Trocchi" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Trocchi', 'leadx' ); ?></option>
			<option value="Trochut" <?php if( "Trochut" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Trochut', 'leadx' ); ?></option>
			<option value="Trykker" <?php if( "Trykker" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Trykker', 'leadx' ); ?></option>
			<option value="Tulpen One" <?php if( "Tulpen One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Tulpen One', 'leadx' ); ?></option>
			<option value="Ubuntu" <?php if( "Ubuntu" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ubuntu', 'leadx' ); ?></option>
			<option value="Ubuntu Condensed" <?php if( "Ubuntu Condensed" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ubuntu Condensed', 'leadx' ); ?></option>
			<option value="Ubuntu Mono" <?php if( "Ubuntu Mono" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ubuntu Mono', 'leadx' ); ?></option>
			<option value="Ultra" <?php if( "Ultra" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Ultra', 'leadx' ); ?></option>
			<option value="Uncial Antiqua" <?php if( "Uncial Antiqua" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Uncial Antiqua', 'leadx' ); ?></option>
			<option value="Underdog" <?php if( "Underdog" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Underdog', 'leadx' ); ?></option>
			<option value="Unica One" <?php if( "Unica One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Unica One', 'leadx' ); ?></option>
			<option value="UnifrakturCook" <?php if( "UnifrakturCook" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'UnifrakturCook', 'leadx' ); ?></option>
			<option value="UnifrakturMaguntia" <?php if( "UnifrakturMaguntia" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'UnifrakturMaguntia', 'leadx' ); ?></option>
			<option value="Unkempt" <?php if( "Unkempt" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Unkempt', 'leadx' ); ?></option>
			<option value="Unlock" <?php if( "Unlock" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Unlock', 'leadx' ); ?></option>
			<option value="Unna" <?php if( "Unna" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Unna', 'leadx' ); ?></option>
			<option value="VT323" <?php if( "VT323" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'VT323', 'leadx' ); ?></option>
			<option value="Vampiro One" <?php if( "Vampiro One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Vampiro One', 'leadx' ); ?></option>
			<option value="Varela" <?php if( "Varela" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Varela', 'leadx' ); ?></option>
			<option value="Varela Round" <?php if( "Varela Round" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Varela Round', 'leadx' ); ?></option>
			<option value="Vast Shadow" <?php if( "Vast Shadow" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Vast Shadow', 'leadx' ); ?></option>
			<option value="Vesper Libre" <?php if( "Vesper Libre" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Vesper Libre', 'leadx' ); ?></option>
			<option value="Vibur" <?php if( "Vibur" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Vibur', 'leadx' ); ?></option>
			<option value="Vidaloka" <?php if( "Vidaloka" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Vidaloka', 'leadx' ); ?></option>
			<option value="Viga" <?php if( "Viga" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Viga', 'leadx' ); ?></option>
			<option value="Voces" <?php if( "Voces" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Voces', 'leadx' ); ?></option>
			<option value="Volkhov" <?php if( "Volkhov" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Volkhov', 'leadx' ); ?></option>
			<option value="Vollkorn" <?php if( "Vollkorn" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Vollkorn', 'leadx' ); ?></option>
			<option value="Voltaire" <?php if( "Voltaire" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Voltaire', 'leadx' ); ?></option>
			<option value="Waiting for the Sunrise" <?php if( "Waiting for the Sunrise" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Waiting for the Sunrise', 'leadx' ); ?></option>
			<option value="Wallpoet" <?php if( "Wallpoet" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Wallpoet', 'leadx' ); ?></option>
			<option value="Walter Turncoat" <?php if( "Walter Turncoat" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Walter Turncoat', 'leadx' ); ?></option>
			<option value="Warnes" <?php if( "Warnes" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Warnes', 'leadx' ); ?></option>
			<option value="Wellfleet" <?php if( "Wellfleet" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Wellfleet', 'leadx' ); ?></option>
			<option value="Wendy One" <?php if( "Wendy One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Wendy One', 'leadx' ); ?></option>
			<option value="Wire One" <?php if( "Wire One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Wire One', 'leadx' ); ?></option>
			<option value="Yanone Kaffeesatz" <?php if( "Yanone Kaffeesatz" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yanone Kaffeesatz', 'leadx' ); ?></option>
			<option value="Yellowtail" <?php if( "Yellowtail" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yellowtail', 'leadx' ); ?></option>
			<option value="Yeseva One" <?php if( "Yeseva One" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yeseva One', 'leadx' ); ?></option>
			<option value="Yesteryear" <?php if( "Yesteryear" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Yesteryear', 'leadx' ); ?></option>
			<option value="Zeyada" <?php if( "Zeyada" == $this_val ) echo 'selected="selected"'; ?>><?php esc_html_e( 'Zeyada', 'leadx' ); ?></option>
		</select>
	</label>
	<?php }
}