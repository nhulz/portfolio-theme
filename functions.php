<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

Timber::$dirname = array('templates', 'views');

class PortfolioTheme extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}

	function register_post_types() {
		/* Project */
		register_post_type( 'project',
			array(
				'labels' => array(
					'name' => __( 'Projects' ),
					'singular_name' => __( 'Project' )
					),
					'public' => true,
					'has_archive' => true
			)
		);
	}

	function register_taxonomies() {
		// This is the Project Type
		register_taxonomy('project-type', 'project', array(
			'hierarchical' => true,
			'show_ui' => true,
			// This array of options controls the labels displayed in the WordPress Admin UI
			'labels' => array(
				'name' => _x( 'Project Type', 'taxonomy general name' ),
				'singular_name' => _x( 'Project Type', 'taxonomy singular name' ),
				'search_items' =>  __( 'Search Project Types' ),
				'all_items' => __( 'All Project Types' ),
				'parent_item' => __( 'Parent Project Type' ),
				'parent_item_colon' => __( 'Parent Project Type:' ),
				'edit_item' => __( 'Edit Project Type' ),
				'update_item' => __( 'Update Project Type' ),
				'add_new_item' => __( 'Add New Project Type' ),
				'new_item_name' => __( 'New Project Type Name' ),
				'menu_name' => __( 'Project Types' ),
			),
			// Control the slugs used for this taxonomy
			'rewrite' => array(
				'slug' => 'project-types', // This controls the base slug that will display before each term
				'with_front' => false, // Don't display the category base before "/locations/"
				'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
			)
		));
	}

	function add_to_context( $context ) {
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}

}

new PortfolioTheme();
