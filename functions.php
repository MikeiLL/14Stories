<?php
/* ----Override Some Gallery Styles -------*/

function enqueue_gallery_style() {
	wp_enqueue_style($handle = 'storybook-gallery-styling', $src = get_stylesheet_directory_uri() . '/album-gallery.css', $deps = array(), $ver = null, $media = null );
}
add_filter( 'post_gallery', 'enqueue_gallery_style', 1001, 2 );

/*Remove Archive Title*/
add_filter('tc_category_archive_title',function(){ return ''; });



//Adding and Encuing styles and scripts for Magnific Popup
//add_action('wp_enqueue_scripts', 'enqueue_magnific_popup_styles');
function enqueue_magnific_popup_styles() {
wp_register_style( 'magnific_popup_style', get_stylesheet_directory_uri() . '/magnific-popup/magnific-popup.css' );
wp_enqueue_style( 'magnific_popup_style' );
}
 
//add_action('wp_enqueue_scripts', 'enqueue_magnific_popup_scripts');
function enqueue_magnific_popup_scripts() {
wp_register_script( 'magnific_popup_script', get_stylesheet_directory_uri() . '/magnific-popup/jquery.magnific-popup.min.js', array( 'jquery' ),'1.0.0', true );
wp_enqueue_script( 'magnific_popup_script' );
wp_register_script( 'magnific_init_script', get_stylesheet_directory_uri() . '/magnific-popup/jquery.magnific-init.js','','1.0.0', true );
wp_enqueue_script( 'magnific_init_script' );
}

/**
* Add title back to images
*/
function pexeto_add_title_to_attachment( $markup, $id ){
$att = get_post( $id );
return str_replace('<a ', '<a title="'.$att->post_content.'" ', $markup);
}
add_filter('wp_get_attachment_link', 'pexeto_add_title_to_attachment', 10, 5);

/*Add custom link to front page Home Page featured pages*/
add_filter('tc_fp_link_url' , 'my_custom_fp_links', 10 ,2);
function my_custom_fp_links( $original_link , $fp_id ) {
    
    //assigns a custom link by page id
    $custom_link = array(
        //page id => 'Custom link' - 1436, n this case
        1436 => 'http://www.storybooksound.com/category/news/'//,
    );
    
    foreach ($custom_link as $page_id => $link) {
        if ( get_permalink($page_id) == $original_link )
            return $link;
    }

    //if no custom title is defined for the current page id, return original
    return $original_link;
}




/* ----Enable Bootstrap Taps Support in Theme-------*/
add_theme_support( 'tabs', 'twitter-bootstrap' );



/* ----Google Font -Cinzel-  -------*/



add_action ( 'wp_head' , 'my_google_font');

function my_google_font() {

	?>

	<link href='http://fonts.googleapis.com/css?family=Cinzel:400,700' rel='stylesheet' type='text/css'>

	<?php

}

add_filter( 'tc_credits_display', 'my_credits_display' );

function my_credits_display($html) {

    $logo_src                = esc_url ( tc__f( '__get_option' , 'tc_logo_upload') ) ;

    if ( empty($logo_src) )

        return $html;

    ?>

    <div class="span4 credits">

        <?php

            $credits =  sprintf( '<br/><p> © <a href="%2$s">%3$s</a> %1$s <a href="mailto:%4$s" title="%2$s" >%4$s</a>   </p> ',

                    esc_attr( date( 'Y' ) ),

                    esc_url( home_url() ),

                    esc_attr(get_bloginfo()),

                    'info@storybooksound.com'


            );

            echo $credits."</div>";

	}

//Adding the Open Graph in the Language Attributes
function add_opengraph_doctype( $output ) {
		return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
	}
add_filter('language_attributes', 'add_opengraph_doctype');

//Lets add Open Graph Meta Info

function insert_fb_in_head() {
	global $post;
        setup_postdata($post);
        $default_image=get_stylesheet_directory_uri() . "/images/Storybook-logo-fb-sq-sm.png"; 
	if ( !is_singular()) //if it is not a post or a page
		{
		if (is_category())
			{
			echo '<meta property="og:description" content="Storybook Sound Mastering and Recording Studio Latest News"/>';
        	echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        	echo '<meta property="og:site_name" content="Storybook Sound Mastering &amp; Recording Studio"/>';
        	echo '<meta property="og:image" content="' . $default_image . '"/>';
			}
		return;
		}
		else
		{
        echo '<meta property="fb:admins" content="1090178760"/>';
        echo '<meta property="og:title" content="' . get_the_title() . '"/>';
        echo '<meta property="og:type" content="article"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        echo '<meta property="og:site_name" content="Storybook Sound Mastering &amp; Recording Studio"/>';
        $an_excerpt =  strip_tags(get_the_excerpt($post->ID));
        $og_excerpt = isset($an_excerpt) ? $an_excerpt : bloginfo('description');
        echo '<meta property="og:description" content="'.$og_excerpt.'"/>';
			if(!has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
				echo '<meta property="og:image" content="' . $default_image . '"/>';
			}
			else{
				$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
				echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
			}
	echo "
";	}
}
add_action( 'wp_head', 'insert_fb_in_head', 5 );
?>