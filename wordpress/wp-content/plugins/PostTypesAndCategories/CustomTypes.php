<?php
/*
Plugin Name: Custom Types
Description: Plugin to register post type
Version: 1.0
Author: Diamadis Giorgos
*/

class CustomTypes {
	public function __construct() {
		add_action( 'init', array( $this, 'BonusType' ), 0 );
		add_action( 'init', array( $this, 'PaymentType' ), 0 );
		add_action( 'init', array( $this, 'GameType' ), 0 );
		add_action( 'init', array( $this, 'CasinoType' ), 0 );
		add_action( 'init', array( $this, 'WageringTaxonomy' ), 0 );
	}

	function GameType() {
		$this->registerType( 'Game', '' );
	}

	function BonusType() {
		$this->registerType( 'Bonus', 'slingo-bonus' );
	}

	function PaymentType() {
		$this->registerType( 'Payment', 'payment' );
	}

	function CasinoType() {
		$this->registerType( 'Casino', 'slingo-reviews', array( 'category', 'bonus', 'payment_method', 'wagering' ) );
	}


	function registerType( $typeName, $slug, $categories = array( 'category' ) ) {
		$labels = array(
			'name'               => __( $typeName . "s" ),
			'singular_name'      => __( $typeName ),
			'add_new'            => __( 'New ' . $typeName ),
			'add_new_item'       => __( 'Add New ' . $typeName ),
			'edit_item'          => __( 'Edit ' . $typeName ),
			'new_item'           => __( 'New ' . $typeName ),
			'view_item'          => __( 'View ' . $typeName . "s" ),
			'search_items'       => __( 'Search ' . $typeName . "s" ),
			'not_found'          => __( 'No ' . $typeName . "s Found" ),
			'not_found_in_trash' => __( 'No ' . $typeName . 's found in Trash' ),
		);

		$args = array(
			'labels'       => $labels,
			'has_archive'  => false,
			'public'       => true,
			'hierarchical' => false,
			'supports'     => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'custom-fields'
			),
			'rewrite'      => array( 'slug' => $slug ),
			'show_in_rest' => true,
			'taxonomies'   => $categories,
		);

		register_post_type( $typeName, $args );
	}


	function WageringTaxonomy() {
		$labels = array(
			'name'              => _x( 'Wagering', 'Wagering' ),
			'singular_name'     => _x( 'Wagerings', 'Wagering' ),
			'search_items'      => __( 'Search Wagering' ),
			'all_items'         => __( 'All Wagerings' ),
			'parent_item'       => __( 'Parent Wagering' ),
			'parent_item_colon' => __( 'Parent Wagering' ),
			'edit_item'         => __( 'Edit Wagering' ),
			'update_item'       => __( 'Update Wagering' ),
			'add_new_item'      => __( 'Add New Wagering' ),
			'new_item_name'     => __( 'New Wagering' ),
			'menu_name'         => __( 'Wagering' ),
		);

		register_taxonomy( 'wagering', array( 'post', 'page', 'casino' ), array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'query_var'         => true,
		) );
	}
}


$customTypes = new CustomTypes();