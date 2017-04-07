<?php

/* Disable the Admin Bar. */
//add_filter( 'show_admin_bar', '__return_false' );

/* Remove the Admin Bar preference in user profile */
remove_action( 'personal_options', '_admin_bar_preferences' );


// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'photocrati-framework', TEMPLATEPATH . '/languages' );

add_theme_support( 'post-thumbnails' ); 
add_theme_support( 'jetpack-responsive-videos' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable($locale_file) )
    require_once($locale_file);


// Get the page number
function get_page_number() {
    if (get_query_var('paged')) {
        print ' | ' . __( 'Page ' , 'photocrati-framework') . get_query_var('paged');
    }
} // end get_page_number


// For category lists on category archives: Returns other categories except the current one (redundant)
function cats_meow($glue) {
    $current_cat = single_cat_title( '', false );
    $separator = "\n";
    $cats = explode( $separator, get_the_category_list($separator) );
    foreach ( $cats as $i => $str ) {
        if ( strstr( $str, ">$current_cat<" ) ) {
            unset($cats[$i]);
            break;
        }
    }
    if ( empty($cats) )
        return false;

    return trim(join( $glue, $cats ));
} // end cats_meow


// For tag lists on tag archives: Returns other tags except the current one (redundant)
function tag_ur_it($glue) {
    $current_tag = single_tag_title( '', '',  false );
    $separator = "\n";
    $tags = explode( $separator, get_the_tag_list( "", "$separator", "" ) );
    foreach ( $tags as $i => $str ) {
        if ( strstr( $str, ">$current_tag<" ) ) {
            unset($tags[$i]);
            break;
        }
    }
    if ( empty($tags) )
        return false;

    return trim(join( $glue, $tags ));
} // end tag_ur_it


// Produces an avatar image with the hCard-compliant photo class
function commenter_link() {
    $commenter = get_comment_author_link();
    if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
        $commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
    } else {
        $commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
    }
    $avatar_email = get_comment_author_email();
    $avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 80 ) );
    echo ' <a href="'.get_comment_author_url().'" title="Comment Author" target="_blank">'.$avatar .'</a>';
} // end commenter_link


// Custom callback to list comments in the photocrati-framework style
function custom_comments($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    $GLOBALS['comment_depth'] = $depth;
    ?>
    <li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
    <div class="comment-wrapper">
        <div class="comment-author vcard"><?php commenter_link() ?></div>
        <div class="content-wrapper">
            <div class="comment-meta"><?php printf(__('<a class="commentauthor" href="%4$s" title="Comment Author" target="_blank">%3$s</a> <span class="commentdate">%1$s at %2$s</span> <a class="commentpermalink" href="%5$s" title="Permalink to this comment">#</a>', 'photocrati-framework'),
                    get_comment_date(),
                    get_comment_time(),
                    get_comment_author(),
                    get_comment_author_url(),
                    '#comment-' . get_comment_ID() );
                edit_comment_link(__('Edit', 'photocrati-framework'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
            <?php if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n", 'photocrati-framework') ?>
            <div class="comment-content">
                <?php comment_text() ?>
            </div>
        </div>
        <?php // echo the comment reply link
        if($args['type'] == 'all' || get_comment_type() == 'comment') :
            comment_reply_link(array_merge($args, array(
                'reply_text' => __('Reply','photocrati-framework'),
                'login_text' => __('Log in to reply.','photocrati-framework'),
                'depth' => $depth,
                'before' => '<div class="comment-reply-link">',
                'after' => '</div>'
            )));
        endif;
        ?>
    </div>
<?php } // end custom_comments


// Custom callback to list pings
function custom_pings($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    ?>
<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
    <div class="comment-author"><?php printf(__('By %1$s on %2$s at %3$s', 'photocrati-framework'),
            get_comment_author_link(),
            get_comment_date(),
            get_comment_time() );
        edit_comment_link(__('Edit', 'photocrati-framework'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
    <?php if ($comment->comment_approved == '0') _e('\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation.</span>\n', 'photocrati-framework') ?>
    <div class="comment-content">
        <?php comment_text() ?>
    </div>
<?php } // end custom_pings ?>
