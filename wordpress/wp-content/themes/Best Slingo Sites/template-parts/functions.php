<?php
require_once __DIR__ . '/classes/Container.php';
require_once __DIR__ . '/classes/UI.php';
require_once __DIR__ . '/classes/DB.php';
require_once __DIR__ . '/classes/Helper.php';
require_once __DIR__ . '/classes/FaqBuilder.php';
require_once __DIR__ . '/classes/Config.php';

Container::Add( 'ui', new UI() );
Container::Add( 'db', new DB() );
Container::Add( 'helper', new Helper() );
Container::Add( 'faqBuilder', new FaqBuilder() );
Container::Add( 'config', new Config() );

function dg_theme_support() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'post-thumbnails' );
}

function menus() {
	$locations = array(
		'header'       => 'Header Menu',
		'footer-menu1' => 'Footer Menu 1',
		'footer-menu2' => 'Footer Menu 2',
		'footer-menu3' => 'Footer Menu 3'
	);

	register_nav_menus( $locations );
}

function dg_registerStyles() {

	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'dg-css', get_template_directory_uri() . "/style.css", array(), $version, 'all' );
	wp_enqueue_style( 'dg-googleFont-primary', "https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap", array(), "1.0", 'all' );
	wp_enqueue_style( 'dg-googleFont-secondary', "https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap", array(), "1.0", 'all' );
}


function load_admin_style() {
	wp_enqueue_style( 'admin-reorder_style', get_template_directory_uri() . "/reorder/admin-casino_reorder.css", array(), '1.0', 'all' );
	wp_enqueue_style( 'admin-style', get_template_directory_uri() . "/materialize-custom.css", array(), '1.0', 'all' );

	wp_enqueue_script( 'admin-casino_reorder', get_template_directory_uri() . "/reorder/admin-casino_reorder.js", array(), '1.0' );
	wp_enqueue_script( 'admin-casino_reorder_api', get_template_directory_uri() . "/reorder/admin-casino_reorder_api.js", array(), '1.0' );
	wp_enqueue_script( 'admin-casino_reorder_jquery', "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js", array(), '1.0' );
	wp_enqueue_script( 'materialize_js', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js', array() );


}

add_action( 'admin_enqueue_scripts', 'load_admin_style' );

add_action( 'after_setup_theme', 'dg_theme_support', 30 );
add_action( 'wp_enqueue_scripts', 'dg_registerStyles', 30 );
add_action( 'init', 'menus' );

require_once __DIR__ . "/classes/Casino.php";
require_once __DIR__ . "/classes/GeneralShortcodes.php";


add_filter( 'rwmb_meta_boxes', function ( $meta_boxes ) {
	$prefix = '';

	$meta_boxes[] = [
		'title'   => esc_html__( 'Game Fields', 'online-generator' ),
		'id'      => 'untitled',
		'context' => 'normal',
		'pages'   => 'game',
		'fields'  => [
			[
				'type' => 'textarea',
				'name' => esc_html__( 'Why Play', 'online-generator' ),
				'id'   => $prefix . 'why_play',
			],
			[
				'type' => 'textarea',
				'name' => esc_html__( 'Game Demo', 'online-generator' ),
				'id'   => $prefix . 'game_demo',
			],
			[
				'type' => 'text',
				'name' => esc_html__( 'Title Before the Filters', 'online-generator' ),
				'id'   => $prefix . 'title_bf_filters',
			],
			[
				'type' => 'wysiwyg',
				'name' => esc_html__( 'Textarea Before the Casino List', 'online-generator' ),
				'id'   => $prefix . 'textarea_bf_filters',
			],
			[
				'type' => 'url',
				'name' => esc_html__( 'Demo', 'online-generator' ),
				'id'   => $prefix . 'demo',
			],
			[
				'type' => 'url',
				'name' => esc_html__( 'Affiliate Link', 'online-generator' ),
				'id'   => $prefix . 'affiliate_link',
			],
			[
				'type' => 'text',
				'name' => esc_html__( 'Theme', 'online-generator' ),
				'id'   => $prefix . 'theme',
			],
			[
				'type' => 'text',
				'name' => esc_html__( 'Max Bet', 'online-generator' ),
				'id'   => $prefix . 'max_bet',
			],
			[
				'type' => 'text',
				'name' => esc_html__( 'Free Spins', 'online-generator' ),
				'id'   => $prefix . 'free_spins',
			],
			[
				'type' => 'text',
				'name' => esc_html__( 'Volatility', 'online-generator' ),
				'id'   => $prefix . 'volatility',
			],
			[
				'type' => 'text',
				'name' => esc_html__( 'Bonus Feature', 'online-generator' ),
				'id'   => $prefix . 'bonus_feature',
			],
			[
				'type' => 'text',
				'name' => esc_html__( 'RTP', 'online-generator' ),
				'id'   => $prefix . 'other',
			],
		],
	];

	return $meta_boxes;

}, 30 );
add_filter( 'rwmb_meta_boxes', function ( $meta_boxes ) {
	$prefix       = "";
	$meta_boxes[] = [
		'title'   => esc_html__( 'Filter title', 'online-generator' ),
		'id'      => 'untitled',
		'context' => 'normal',
		'pages'   => array( 'bonus', 'payment' ),
		'fields'  => [

			[
				'type' => 'text',
				'name' => esc_html__( 'Filter Title', 'online-generator' ),
				'id'   => $prefix . 'filter_title',
			],
		]
	];

	return $meta_boxes;
} );
add_filter( 'rwmb_meta_boxes', function ( $meta_boxes ) {
	$prefix = '';

	$meta_boxes[] = [
		'title'   => esc_html__( 'Seo Fields', 'online-generator' ),
		'id'      => 'untitled',
		'context' => 'normal',
		'pages'   => array( 'post', 'page' ),
		'fields'  => [
			[
				'type'       => 'post',
				'name'       => esc_html__( 'Casino Finder Games', 'online-generator' ),
				'id'         => $prefix . 'cf_games',
				'post_type'  => 'game',
				'field_type' => 'select_advanced',
				'multiple'   => true
			],
			[
				'type'       => 'post',
				'name'       => esc_html__( 'Casino Finder Bonuses', 'online-generator' ),
				'id'         => $prefix . 'cf_bonus',
				'post_type'  => 'bonus',
				'field_type' => 'select_advanced',
				'multiple'   => true
			],
			[
				'type'       => 'post',
				'name'       => esc_html__( 'Casino Finder Payment Methods', 'online-generator' ),
				'id'         => $prefix . 'cf_payment',
				'post_type'  => 'payment',
				'field_type' => 'select_advanced',
				'multiple'   => true
			],
		],
	];

	return $meta_boxes;

}, 30 );
//remove_filter ('the_content', 'wpautop');
function na_remove_slug( $post_link, $post, $leavename ) {

	if ( 'game' != $post->post_type || 'publish' != $post->post_status ) {
		return $post_link;
	}

	$post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

	return $post_link;
}

add_filter( 'post_type_link', 'na_remove_slug', 10, 3 );

function change_slug_struct( $query ) {

	if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
		return;
	}

	if ( ! empty( $query->query['name'] ) ) {
		$query->set( 'post_type', array( 'post', 'game', 'page' ) );
	} elseif ( ! empty( $query->query['pagename'] ) && false === strpos( $query->query['pagename'], '/' ) ) {
		$query->set( 'post_type', array( 'post', 'game', 'page' ) );

		// We also need to set the name query var since redirect_guess_404_permalink() relies on it.
		$query->set( 'name', $query->query['pagename'] );
	}
}

add_action( 'pre_get_posts', 'change_slug_struct' );


add_image_size( 'casino-list-item', 200, 111, true );
add_image_size( 'card-thumbnail', 373, 210, true );
add_image_size( 'archive-thumbnail', 336, 198, true );
add_image_size( 'review-large', 604, 339, true );
add_image_size( 'review-medium', 514, 289, true );
add_image_size( 'review-small', 424, 238, true );

