<?php

//
//  Custom Child Theme Functions
//

// I've included a "commented out" sample function below that'll add a home link to your menu
// More ideas can be found on "A Guide To Customizing The Thematic Theme Framework" 
// http://themeshaper.com/thematic-for-wordpress/guide-customizing-thematic-theme-framework/

// Adds a home link to your menu
// http://codex.wordpress.org/Template_Tags/wp_page_menu
//function childtheme_menu_args($args) {
//    $args = array(
//        'show_home' => 'Home',
//        'sort_column' => 'menu_order',
//        'menu_class' => 'menu',
//        'echo' => true
//    );
//	return $args;
//}
//add_filter('wp_page_menu_args','childtheme_menu_args'); 


// ----------------------------------------------------------------------------------------- Main nav

// Creating the content for the Right Nav
function mushon_rightnav() {
  ?>
  <div id="right-top">
  
	  <div id="main-nav">
	    
	    <ul>
	    	<?php
	    	//List pages (about...)
	    	wp_list_pages('title_li='); ?>
	    </ul>
	    
	    <ul>
				<?php
	    	//List extra categories (publications...)
			  $args = array(
			    'hide_empty'         => 0,
			    'include'            => 29,37,
			    'title_li'           => '');
			  wp_list_categories( $args );
			  ?>
	    </ul>
	    
			<ul class="categories last">
				<?php
	    	//List languages (Hebrew / English...)
			  wp_list_categories('include=4,21&title_li=' );
			  ?>
	    </ul>
	    
	   </div>
  <?php
}
add_action('thematic_aboveheader', 'mushon_rightnav');
// END of Right Nav

// ----------------------------------------------------------------------------------------- Page Header

// Remove the default way the header is set up
function remove_thematic_branding() {
	remove_action('thematic_header','thematic_blogtitle',3);
	remove_action('thematic_header','thematic_blogdescription',5);
	remove_action('thematic_header','thematic_brandingclose',7);
	remove_action('thematic_header','thematic_access',9);
}
add_action('init','remove_thematic_branding');

// setup new custom description:
function mushon_desc() {
	echo " | " ;
}
add_filter('thematic_blogdescription', 'mushon_desc');

// setup new custom header:
function mushon_header() {
	$new_header .= thematic_blogtitle();
	$new_header .= thematic_blogdescription();
	$new_header .= thematic_brandingclose();
	echo $new_header;
}
add_filter('thematic_header', 'mushon_header');

// ----------------------------------------------------------------------------------------- Left Side Nav


// creates the previous_post_link_args
function mushon_previous_post_link_args() {
	$args = array ('format'              => '%link',
								 'link'                => '<span><strong>Prev</strong></span><span class="meta-nav"><strong>ious Post:</strong><br> %title</span>',
								 'in_same_cat'         => FALSE,
								 'excluded_categories' => '');
	return $args;
}
add_filter('thematic_previous_post_link_args', 'mushon_previous_post_link_args' );
 // end mushon_previous_post_link_args

// creates the next_post_link_args
function mushon_next_post_link_args() {
	$args = array ('format'              => '%link',
								 'link'                => '<span><strong>Next</strong></span><span class="meta-nav"><strong>&nbsp;Post:</strong><br> %title</span>',
								 'in_same_cat'         => FALSE,
								 'excluded_categories' => '');
	return $args;
} 
add_filter('thematic_next_post_link_args', 'mushon_next_post_link_args' );
// end mushon_next_post_link_args

// Action to create the above navigation
function mushon_nav_side() {
		
		?></div><!-- closing #right-top -->
		
		<div id="navi">
  
  	<?php //add search:
  	echo thematic_search_form() ?>
	
  	<?php //add navigation:
		if (is_single()) {?>
		
				<div id="nav-above" class="navigation">
					<div class="nav-previous"><?php thematic_previous_post_link() ?></div>
					<div class="nav-next"><?php thematic_next_post_link() ?></div>
				</div>
			
    <?php
		} else { ?>

			<div id="nav-above" class="navigation">
        <?php if(function_exists('wp_pagenavi')) { ?>
        <?php wp_pagenavi(); ?>
        <?php } else { ?>  
				<div class="nav-previous"><?php next_posts_link(__('<span>Prev</span><span class="meta-nav">ious Posts</span>', 'thematic')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('<span>Next</span><span class="meta-nav">&nbsp;Posts</span>', 'thematic')) ?></div>
				<?php } ?>
			</div>	
		<?php }
		
		//add home link:
		?>	
		<div id="home-link">
			<a title="Go back home" href="<?php bloginfo('url'); ?>">Home</a>
		</div>
		
		<?php
		//add home RSS:
		?>				
		<div id="feed">
			<a type="application/rss+xml" rel="alternate nofollow" title="Mushon.com Posts RSS feed" href="<?php bloginfo('rss2_url'); ?>">Feed</a>
		</div>
		
	</div>
	
<?php
}
add_filter('thematic_belowheader', 'mushon_nav_side' );

// ----------------------------------------------------------------------------------------- Posts

//Twit This button on post footer:
function tweet_this() {
  global $post;
  $tweet = sprintf( __('Reading: %1$s %2$s'), $post->post_title, wp_get_shortlink() );
  //echo '<a class="tweethis" target="blank" href="http://twitter.com/home?status=' . urlencode( $tweet ) . '">Tweet this</a>';
  echo '<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="mushon">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
}

//add_filter('thematic_postfooter', 'tweet_this');

//add class to post:

function add_class($classes) {
	array_push($classes, 'hello');
	return $classes;
}

//add_filter('post_class', 'add_class');


// ----------------------------------------------------------------------------------------- Post Header


function mushon_postheader(){
    global $id, $post, $authordata;
    
		// Post Date:
		$postdate = '<div class="entry-date">';
    $postdate .= '<abbr class="published" title="';
    $postdate .= get_the_time(thematic_time_title()) . '">';
    $postdate .= get_the_time(thematic_time_display());
    $postdate .= '</abbr></div>';
    
    // Create $posteditlink    
    $posteditlink .= '<a href="' . get_bloginfo('wpurl') . '/wp-admin/post.php?action=edit&amp;post=' . $id;
    $posteditlink .= '" title="' . __('Edit post', 'thematic') .'">';
    $posteditlink .= __('Edit', 'thematic') . '</a>';
    $posteditlink = apply_filters('thematic_postheader_posteditlink',$posteditlink); 

    
    if (is_single() || is_page()) {
        $posttitle = '<h1 class="entry-title">' . get_the_title() . "</h1>\n";
    } elseif (is_404()) {    
        $posttitle = '<h1 class="entry-title">' . __('Not Found', 'thematic') . "</h1>\n";
    } else {
        $posttitle = '<h2 class="entry-title"><a href="';
        $posttitle .= get_permalink();
        $posttitle .= '" title="';
        $posttitle .= __('Permalink to ', 'thematic') . the_title_attribute('echo=0');
        $posttitle .= '" rel="bookmark">';
        $posttitle .= get_the_title();   
        $posttitle .= "</a></h2>\n";
    }
    //$posttitle = apply_filters('thematic_postheader_posttitle',$posttitle); 
    
    $postmeta = '<div class="entry-meta">';
    
    //author:
    $postmeta .= '<span class="meta-prep meta-prep-author">' . __('By ', 'thematic') . '</span>';
    $postmeta .= '<span class="author vcard">'. '<a class="url fn n" href="';
    $postmeta .= get_author_link(false, $authordata->ID, $authordata->user_nicename);
    $postmeta .= '" title="' . __('View all posts by ', 'thematic') . get_the_author() . '">';
    $postmeta .= get_the_author();
    $postmeta .= '</a></span>';
    
    //comments:
    if (comments_open()) {
        $postcommentnumber = get_comments_number();
        if ($postcommentnumber > '1') {
            $postcomments = ' <span class="comments-link"><a href="' . get_permalink() . '#comments" title="' . __('Comment on ', 'thematic') . the_title_attribute('echo=0') . '">';
            $postcomments .= get_comments_number() . __(' Comments', 'thematic') . '</a></span>';
        } elseif ($postcommentnumber == '1') {
            $postcomments = ' <span class="comments-link"><a href="' . get_permalink() . '#comments" title="' . __('Comment on ', 'thematic') . the_title_attribute('echo=0') . '">';
            $postcomments .= get_comments_number() . __(' Comment', 'thematic') . '</a></span>';
        } elseif ($postcommentnumber == '0') {
            $postcomments = ' <span class="comments-link"><a href="' . get_permalink() . '#comments" title="' . __('Comment on ', 'thematic') . the_title_attribute('echo=0') . '">';
            $postcomments .= __('Leave a comment', 'thematic') . '</a></span>';
        }
    } else {
        $postcomments = ' <span class="comments-link comments-closed-link">' . __('Comments closed', 'thematic') .'</span>';
    }
    $postmeta .= $postcomments . " <span class=\"meta-sep meta-sep-comments-link\">|</span> ";
    
    //tags:
    if (is_single()) {
        $tagtext = __(' and tagged', 'thematic');
        $posttags = get_the_tag_list("<span class=\"tag-links\"> $tagtext ",', ','</span> <span class=\"meta-sep meta-sep-comments-link\">|</span> ');
    } elseif ( is_tag() && $tag_ur_it = thematic_tag_ur_it(', ') ) { // Returns tags other than the one queried 
        $posttags = '<span class="tag-links">' . __(' Also tagged ', 'thematic') . $tag_ur_it . '</span> <span class=\"meta-sep meta-sep-comments-link\">|</span> ';
    } else {
        $tagtext = __('Tagged', 'thematic');
        $posttags = get_the_tag_list(" <span class=\"tag-links\"> $tagtext ",', ','</span> <span class=\"meta-sep meta-sep-comments-link\">|</span> ');
    }
    $posttags = apply_filters('thematic_postfooter_posttags',$posttags);
    $postmeta .= $posttags;
    
    // Display the post categories  
    $postcategory .= '<span class="cat-links">';
    if (is_single()) {
        $postcategory .= __('This entry was posted in ', 'thematic') . get_the_category_list(', ');
        $postcategory .= '</span>';
    } elseif ( is_category() && $cats_meow = thematic_cats_meow(', ') ) { // Returns categories other than the one queried
        $postcategory .= __('Also posted in ', 'thematic') . $cats_meow;
    } else {
        $postcategory .= __('Posted in ', 'thematic') . get_the_category_list(', ');
    }
    $postcategory = apply_filters('thematic_postfooter_postcategory',$postcategory); 

    
    $postmeta .= $postcategory;
    
    // Display edit link
    if (current_user_can('edit_posts')) {
        $postmeta .= ' <span class="meta-sep meta-sep-edit">|</span> ' . '<span class="edit">' . $posteditlink . '</span>';
    }               
    $postmeta .= "</div><!-- .entry-meta -->\n";
    $postmeta = apply_filters('thematic_postheader_postmeta',$postmeta); 
	    
    if ($post->post_type == 'page' || is_404()) {
        $postheader = $posttitle;        
    } else {
        $postheader = $postdate . $posttitle . $postmeta;    
    }
	return $postheader;
} 
add_filter('thematic_postheader', 'mushon_postheader' );


// ----------------------------------------------------------------------------------------- Sidebar

//wrap the widgets in a "sidebar" div:
function start_side() {
	?>
	<div id="sidebar">
	<?php
}

add_filter('thematic_abovemainasides', 'start_side' );

//Create the custom links sidebar between the other two:
function mushon_sidebar($cat, $title) {
	?>
	<div class="aside mushon-bookmarks">
		<ul>
	<?php
	// --------------------------------------------start projects widget:
		$bm = get_bookmarks( array(
            'orderby'        => 'id',
            'category_name'  => $cat));
    if (isset($bm)){
    $links_widg = '
			<li id="'.$cat.'" class="mushon-links">
				<h3 class="box"><span>' .$title. ':</span></h3>
					<ul>';

		  foreach ($bm as $bookmark){ 
                $links_widg .= "
                <li>";
                if ($bookmark->link_image){
                	$links_widg .= "<div class='thumb' style='background-image:url({$bookmark->link_image})'></div>";
                }
                $links_widg .= "<div class='info'>
										<h4 class='link-title'>{$bookmark->link_name}</h4>
										<p>{$bookmark->link_description}</p>
									</div>
									<span>{$bookmark->link_notes}</span>
                	<a id='relatedlinks' href='{$bookmark->link_url}' target='_blank'>Visit site</a>
                </li><!-- End project item -->";
          }
      $links_widg .= "
	      </ul>
      </li><!-- End projects widget -->";
			echo $links_widg;
		}

			/* Static version:
			
			<li id="projects">
				<h3><span>Selected Projects:</span></h3>
					<ul>
            <li>
            	<img src='/mushon.com/wp-content/uploads/thumbs/shual.png' alt='shual.com'>
							<div class='info'>
								<h4 class='link-title'>shual.com</h4>
								<p>Full portfolio of our work at Shual design studio shared with Guy Saggee</p>
							</div>
							<span><a href='#'>More info @shual</a></span>
            	<a id='relatedlinks' href='#' target='_blank'>Visit site</a>
            </li><!-- End project item -->
          </ul>
       </li><!-- End projects widget -->
      
      */
			?>
		</ul><!-- End sidebar widgets -->
	</div>
	<?php
}

function mushon_2sidebars() {
	echo mushon_sidebar('projects', 'Selected Projects');
	echo mushon_sidebar('teaching', 'Classes I Teach');
}


add_filter('thematic_betweenmainasides', 'mushon_2sidebars' );

//close the "sidebar" div:
function end_side() {
	?>
	</div>
	<?php
}

add_filter('thematic_belowmainasides', 'end_side' );
?>