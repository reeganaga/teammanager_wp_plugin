<?php
/*
Plugin Name: Team Manager
Plugin URI: http://wp.tutsplus.com/
Description: make your team member showing on frontpage as thumbnail view.
Version: 1.0
Author: Rega Cahya Gumilang
Author URI: http://wp.tutsplus.com/
License: GPLv2
*/
?>

<?php
add_action('init','create_team_member');
add_action('admin_init','my_admin');
add_action( 'save_post', 'add_movie_review_fields', 10, 2 );
// add_filter( 'template_include', 'include_template_function', 1 );

function create_team_member() {
    register_post_type( 'movie_reviews',
        array(
            'labels' => array(
                'name' => 'Team Members',
                'singular_name' => 'Team Member',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Team Member',
                'edit' => 'Edit',
                'edit_item' => 'Edit Team Member',
                'new_item' => 'New Team Member',
                'view' => 'View',
                'view_item' => 'View Team Member',
                'search_items' => 'Search Team Members',
                'not_found' => 'No Team Members found',
                'not_found_in_trash' => 'No Team Members found in Trash',
                'parent' => 'Parent Team Member'
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail',  ),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
            'has_archive' => true
        )
    );
}


	add_filter( 'rwmb_meta_boxes', 'your_prefix_meta_boxes' );
	function your_prefix_meta_boxes( $meta_boxes ) {
		// Retrieve current name of the Director and Movie Rating based on review ID
   		
	    $meta_boxes[] = array(
	        'title'      => __( 'Other Information', 'textdomain' ),
	        'post_types' => 'movie_reviews',
	        'fields'     => array(
	            array(
	                'id'   => 'teamManagerPosition',
	                'name' => __( 'Position', 'textdomain' ),
	                'type' => 'text',
	            ),
	            array(
	                'id'      => 'teamManagerEmail',
	                'name'    => __( 'Email', 'textdomain' ),
	                'type'    => 'email',
	            ),
	            array(
	                'id'   => 'teamManagerWebsite',
	                'name' => __( 'Website', 'textdomain' ),
	                'type' => 'text',
	            ),
	            array(
	                'id'   => 'teamManagerImage',
	                'name' => __( 'Image', 'textdomain' ),
	                'type' => 'image',
	            ),
	        ),
	    );
	    return $meta_boxes;

	}



function add_movie_review_fields( $movie_review_id, $movie_review ) {
    // Check post type for movie reviews
    if ( $movie_review->post_type == 'movie_reviews' ) {
        // Store data in post meta table if present in post data

		if ( isset( $_POST['teamManagerPosition'] ) && $_POST['teamManagerPosition'] !='' ){
			update_post_meta($movie_review_id, 'teamManagerPosition', $_POST['teamManagerPosition']);
		}
		if ( isset( $_POST['teamManagerEmail'] ) && $_POST['teamManagerEmail'] !='' ){
			update_post_meta($movie_review_id, 'teamManagerEmail', $_POST['teamManagerEmail']);
		}
		if ( isset( $_POST['teamManagerWebsite'] ) && $_POST['teamManagerWebsite'] !='' ){
			update_post_meta($movie_review_id, 'teamManagerWebsite', $_POST['teamManagerWebsite']);
		}
		if ( isset( $_POST['teamManagerImage'] ) && $_POST['teamManagerImage'] !='' ){
			update_post_meta($movie_review_id, 'teamManagerImage', $_POST['teamManagerImage']);
		}        

    }
}

function front_team_member(){
    ob_start();
    show_team_member();
    return ob_get_clean();
}
add_shortcode( 'call_team_member', 'front_team_member');

function show_team_member($movie_review){
	$queryTeam = new WP_Query('post_type=movie_reviews'); 
	$jhf_values = array('');
	// The Loop
	if ( $queryTeam->have_posts() ) { ?>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<div class="row text-center" style="background: #2a9ede;color: #fff;padding-top: 30px;">

	<?php while ( $queryTeam->have_posts() ) { ?>
	<?php $queryTeam->the_post();
		// print_r($queryTeam->the_post());
		$position = get_post_meta(get_the_ID(),'teamManagerPosition',true);
		$phone = get_post_meta(get_the_ID(),'teamManagerPhone',true);
		$website = get_post_meta(get_the_ID(),'teamManagerWebsite',true);
		$img_id = get_post_meta(get_the_ID(),'teamManagerImage',true);

		$image =  wp_get_attachment_image_src($img_id);
		 ?>
		<div class="col-md-4">
			<img src="<?php echo $image[0]; ?>" class="img-responsive img-circle center-block">
			<h4><?php echo get_the_title(); ?></h4>
			<h5><?php echo $position; ?></h5>
			<h5><i class=""></i>Phone :<?php echo $phone; ?></h5>
			<p><a href="<?php echo $website; ?>" target="_blank"><?php echo $website; ?></a></p>
		</div>
	<?php } ?>

	</div>

	<?php
		/* Restore original Post Data */
		wp_reset_postdata();
	} else {
		// no posts found
	}
}
?>
