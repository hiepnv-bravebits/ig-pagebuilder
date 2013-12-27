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
if ( ! class_exists( 'IG_Item_List' ) ) {

	class IG_Item_List extends IG_Pb_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'List Item', IGPBL )
			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'    => __( 'Heading', IGPBL ),
						'id'      => 'heading',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'role'    => 'title',
						'std'     => __( ig_pb_add_placeholder( 'List Item %s', 'index' ), IGPBL ),
						'tooltip' => __( 'Heading Description', IGPBL )
					),
					array(
						'name'    => __( 'Body', IGPBL ),
						'id'      => 'body',
						'role'    => 'content',
						'type'    => 'tiny_mce',
						'std'     => IG_Pb_Helper_Type::lorem_text(),
						'tooltip' => __( 'Body Description', IGPBL )
					),
					array(
						'name'      => __( 'Icon', IGPBL ),
						'id'        => 'icon',
						'type'      => 'icons',
						'std'       => '',
						'role'      => 'title_prepend',
						'title_prepend_type' => 'icon',
						'tooltip'   => __( 'Icon Description', IGPBL )
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
			extract( shortcode_atts( $this->config['params'], $atts ) );
			return "
			<li>
				[icon]<div class='ig-sub-icons' style='ig-styles'>
					<i class='$icon'></i>
				</div>[/icon]
				<div class='ig-list-content-wrap'>
					[heading]<h4 style='ig-list-title'>$heading</h4>[/heading]
					<div class='ig-list-content'>
						$content
					</div>
				</div>
			</li><!--seperate-->";
		}

	}

}