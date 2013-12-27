<?php
/**
 * @version    $Id$
 * @package    IG Pagebuilder
 * @author     InnoThemes Team <support@innothemes.com>
 * @copyright  Copyright (C) 2012 innothemes.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innothemes.com
 * Technical Support:  Feedback - http://www.innothemes.com
 */
if ( ! class_exists( 'IG_Carousel' ) ) {

	class IG_Carousel extends IG_Pb_Parent {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode']        = strtolower( __CLASS__ );
			$this->config['name']             = __( 'Carousel', IGPBL );
			$this->config['cat']              = __( 'Typography', IGPBL );
			$this->config['icon']             = 'icon-paragraph-text';
			$this->config['has_subshortcode'] = 'IG_Item_' . str_replace( 'IG_', '', __CLASS__ );
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'action' => array(
					array(
						'id'      => 'btn_convert',
						'type'    => 'button_group',
						'bound'   => 0,
						'actions' => array(
							array(
								'std'         => __( 'Tab', IGPBL ),
								'action_type' => 'convert',
								'action'      => 'carousel_to_tab',
							),
							array(
								'std'         => __( 'Accordion', IGPBL ),
								'action_type' => 'convert',
								'action'      => 'carousel_to_accordion',
							),
							array(
								'std'         => __( 'List', IGPBL ),
								'action_type' => 'convert',
								'action'      => 'carousel_to_list',
							),
						)
					),
				),
				'content' => array(
					array(
						'name'    => __( 'Element Title', IGPBL ),
						'id'      => 'el_title',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => __( '', IGPBL ),
						'role'    => 'title',
						'tooltip' => __( 'Set title for current element for identifying easily', IGPBL )
					),
					array(
						'id'            => 'carousel_items',
						'type'          => 'group',
						'shortcode'     => ucfirst( __CLASS__ ),
						'sub_item_type' => $this->config['has_subshortcode'],
						'sub_items'     => array(
							array('std' => ''),
							array('std' => ''),
						),
					),
				),
				'styling' => array(
					array(
						'type' => 'preview',
					),
					array(
						'name'    => __( 'Alignment', IGPBL ),
						'id'      => 'align',
						'type'    => 'select',
						'std'     => 'center',
						'options' => IG_Pb_Helper_Type::get_text_align(),
						'tooltip' => __( 'Alignment Description', IGPBL )
					),
					array(
						'name'                 => __( 'Dimension', IGPBL ),
						'container_class'      => 'combo-group',
						'id'                   => 'dimension',
						'type'                 => 'dimension',
						'extended_ids'         => array( 'dimension_width', 'dimension_height', 'dimension_width_unit' ),
						'dimension_width'      => array( 'std' => '500' ),
						'dimension_height'     => array( 'std' => '300' ),
						'dimension_width_unit' => array(
							'options' => array( 'px' => 'px', '%' => '%' ),
							'std'     => 'px',
						)
					),
					array(
						'name'    => __( 'Show Indicator', IGPBL ),
						'id'      => 'show_indicator',
						'type'    => 'radio',
						'std'     => 'yes',
						'options' => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
					),
					array(
						'name'    => __( 'Show Arrows', IGPBL ),
						'id'      => 'show_arrows',
						'type'    => 'radio',
						'std'     => 'yes',
						'options' => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
					),
					array(
						'name'       => __( 'Automatic Cycling', IGPBL ),
						'id'         => 'automatic_cycling',
						'type'       => 'radio',
						'std'        => 'no',
						'options'    => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
						'has_depend' => '1',
					),
					array(
						'name' => __( 'Cycling Interval', IGPBL ),
						'type' => array(
							array(
								'id'         => 'cycling_interval',
								'type'       => 'text_append',
								'type_input' => 'number',
								'class'      => 'input-mini',
								'std'        => '5',
								'append'     => 'second(s)',
								'validate'   => 'number',
							),
						),
						'dependency' => array('automatic_cycling', '=', 'yes'),
					),
					array(
						'name'       => __( 'Pause on mouse over', IGPBL ),
						'id'         => 'pause_mouseover',
						'type'       => 'radio',
						'std'        => 'yes',
						'options'    => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
						'dependency' => array( 'automatic_cycling', '=', 'yes' ),
					),
				)
			);
		}

		/**
		 * DEFINE shortcode content
		 *
		 * @param type $atts
		 * @param type $content
		 */
		public function element_shortcode( $atts = null, $content = null ) {
			$arr_params    = shortcode_atts( $this->config['params'], $atts );
			extract( $arr_params );
			$random_id     = ig_pb_generate_random_string();
			$carousel_id   = "carousel_$random_id";

			$interval_time = ! empty( $cycling_interval ) ? intval( $cycling_interval ) * 1000 : 5000;
			$interval      = ( $automatic_cycling == 'yes' ) ? $interval_time : 'false';
			$pause         = ( $pause_mouseover == 'yes' ) ? 'pause : "hover"' : '';
			$script        = IG_Pb_Helper_Functions::carousel_js( $carousel_id, $interval, $pause );

			$styles        = array();
			if ( ! empty( $dimension_width ) )
				$styles[] = "width : {$dimension_width}{$dimension_width_unit};";
			if ( ! empty( $dimension_height ) )
				$styles[] = "height : {$dimension_height}px;";

			if ( in_array( $align, array( 'left', 'right', 'inherit') ) ) {
				$styles[] = "float : $align;";
			} else if ( $align == 'center' )
				$styles[] = 'margin : 0 auto;';

			$styles = trim( implode( ' ', $styles ) );
			$styles = ! empty( $styles ) ? "style='$styles'" : '';


			$carousel_indicators   = array();
			$carousel_indicators[] = '<ol class="carousel-indicators">';

			$sub_shortcode         = IG_Pb_Helper_Shortcode::remove_autop( $content );
			$items                 = explode( '<!--seperate-->', $sub_shortcode );
			$items                 = array_filter( $items );
			$initial_open          = isset( $initial_open ) ? ( ( $initial_open > count( $items ) ) ? 1 : $initial_open ) : 1;
			foreach ( $items as $idx => $item ) {
				$active                = ($idx + 1 == $initial_open) ? 'active' : '';
				$item                  = str_replace( '{active}', $active, $item );
				$item                  = str_replace( '{WIDTH}', 'width : '. $dimension_width . $dimension_width_unit .';', $item );
				$item                  = str_replace( '{HEIGHT}', 'height : '. $dimension_height .'px;', $item );
				$items[$idx]           = $item;
				$active_li             = ($idx + 1 == $initial_open) ? "class='active'" : '';
				$carousel_indicators[] = "<li data-target='#$carousel_id' data-slide-to='$idx' $active_li></li>";
			}
			$carousel_content      = "<div class='carousel-inner'>" . implode( '', $items ) . '</div>';

			$carousel_indicators[] = '</ol>';
			$carousel_indicators   = implode( '', $carousel_indicators );

			if ( $show_indicator == 'no' )
				$carousel_indicators = '';

			$carousel_navigator = '';
			if ($show_arrows == 'yes')
				$carousel_navigator = "<a class='left carousel-control' href='#$carousel_id' data-slide='prev'><span class='glyphicon glyphicon-chevron-left'></span></a><a class='right carousel-control' href='#$carousel_id' data-slide='next'><span class='glyphicon glyphicon-chevron-right'></span></a>";

			$html = "<div class='carousel slide' $styles id='$carousel_id'>$carousel_indicators $carousel_content $carousel_navigator</div>";

			return $this->element_wrapper( $html . $script, $arr_params );
		}

	}

}
