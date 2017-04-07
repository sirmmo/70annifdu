<?php
  define('ABSPATH', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))).'/');
  include_once(ABSPATH.'wp-config.php');
  include_once(ABSPATH.'wp-load.php');
  include_once(ABSPATH.'wp-includes/wp-db.php');
  global $wpdb;
	
	if (!current_user_can('edit_pages') && !current_user_can('edit_posts'))
	{
		wp_die('Permission Denied.');
	}
	
	$upload_dir = wp_upload_dir();
?>
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_directory'); ?>/admin/admin.css" />
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{
    
    jQuery('[id^=edit_button_]').on('click', function(){
                
            var currentId = jQuery(this).attr('id');
	
    });
    
    jQuery('[id^=the_content_]').val(document.post.content.value);
        
});
</script>
<?php
    $gallery = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_ids WHERE post_id = " . ((int)$_POST['post_id']) . " ORDER BY gallery_id ASC");
    if($wpdb->num_rows != 0) {
	foreach ($gallery as $gallery) {
	    echo '<div style="width:100%;height:95px;padding:10px;clear:both;margin-bottom:15px;">';
		if($gallery->gal_type == '6' || $gallery->gal_type == '7') {
			
			if($gallery->gal_type == '6') {
				
				echo '<div style="float:left;width:140px;"><img src="' . photocrati_gallery_file_uri('image/album_list.jpg') . '"></div>';
				
			} else {
				
				echo '<div style="float:left;width:140px;"><img src="' . photocrati_gallery_file_uri('image/album_grid.jpg') . '"></div>';
				
			}
			
		} else {
			
	    $image = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '" . $wpdb->escape($gallery->gallery_id) . "' ORDER BY image_order,image_name LIMIT 1");
	    foreach ($image as $image) {
			if(!function_exists('gd_info')) {
				if (file_exists($upload_dir['basedir'].'/galleries/post-'.$gallery->post_id.'/'.str_replace("&amp;","&",$image->image_name))) {
					echo '<div style="float:left;width:140px;"><img src="'.$upload_dir['baseurl'].'/galleries/post-'.$gallery->post_id.'/'.$image->image_name.'" style="max-width:125px;max-height:100px;"></div>';
				} else {
					echo '<div style="float:left;width:140px;"><img src="'.get_bloginfo('template_url').'/galleries/post-'.$gallery->post_id.'/'.$image->image_name.'" style="max-width:125px;max-height:100px;"></div>';	
				}
			} else {
				if (file_exists($upload_dir['basedir'].'/galleries/post-'.$gallery->post_id.'/'.str_replace("&amp;","&",$image->image_name))) {
					echo '<div style="float:left;width:140px;"><img src="'.$upload_dir['baseurl'].'/galleries/post-'.$gallery->post_id.'/thumbnails/'.$image->image_name.'" style="max-width:125px;max-height:100px;"></div>';
				} else {
					echo '<div style="float:left;width:140px;"><img src="'.get_bloginfo('template_url').'/galleries/post-'.$gallery->post_id.'/thumbnails/'.$image->image_name.'" style="max-width:125px;max-height:100px;"></div>';	
				}
			}
		}
		
		}
        $galnumber = explode("_", $gallery->gallery_id);
?>
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{
    
    jQuery('#delete_button_<?php echo $gallery->gallery_id; ?>').on('click', function(){
        
        function replacePost(gallery) {
				
            document.post.content.value = gallery;
			
		}
        
	    var answer = confirm("Are you sure you want to delete this gallery? This can't be undone.")
	    if (answer){
        
        jQuery("#msggallery_<?php echo $gallery->gallery_id; ?>").html("Deleting Gallery - Please Wait");
        jQuery("#msggallery_<?php echo $gallery->gallery_id; ?>")
			.fadeIn('slow')
			.animate({opacity: 1.0}, 2000);
        
        //switchEditors.go('content', 'html');
        var str = jQuery('#the_content_<?php echo $gallery->gallery_id; ?>').val();
        var pattern = /(<img([^<]*)id="phgallery-<?php echo $gallery->gallery_id; ?> ([^<]*)"[^<]*>)/;
        var repstr = str.replace(pattern, '');
        jQuery('#the_content_<?php echo $gallery->gallery_id; ?>').val(repstr);    
            
		jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/delete-gallery.php'); ?>", data: 'gallery_id=<?php echo $gallery->gallery_id; ?>&post_id=<?php echo $gallery->post_id; ?>', success: function(data)
			{
				jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/get_galleries.php'); ?>", data: 'post_id=<?php echo $_POST['post_id']; ?>', success: function(data)
				{
                    if (tinyMCE.activeEditor != null && tinyMCE.activeEditor.isHidden() == false) {
                        var html = tinyMCE.activeEditor.getContent();
                        var string_replace = html.replace(pattern,'');
                        tinyMCE.execCommand('mceSetContent',false,string_replace); 
                    } else {
                        replacePost(jQuery('#the_content_<?php echo $gallery->gallery_id; ?>').val());
                    }
                    //switchEditors.go('content', 'tinymce');
                    jQuery('#ph_gallery_button').show();
                    jQuery('#display_galleries').html(data);
				}
				});
			}
                });
		
            }
	
    });
    
	
    jQuery('#delete_abutton_<?php echo $gallery->gallery_id; ?>').on('click', function(){
        
        function replacePost(gallery) {
				
            document.post.content.value = gallery;
			
		}
        
	    var answer = confirm("Are you sure you want to remove this album? This can't be undone.")
	    if (answer){
        
        jQuery("#msggallery_<?php echo $gallery->gallery_id; ?>").html("Deleting Album - Please Wait");
        jQuery("#msggallery_<?php echo $gallery->gallery_id; ?>")
			.fadeIn('slow')
			.animate({opacity: 1.0}, 2000);
        
        //switchEditors.go('content', 'html');
        var str = jQuery('#the_content_<?php echo $gallery->gallery_id; ?>').val();
        var pattern = /(<img([^<]*)id="phgallery-<?php echo $gallery->gallery_id; ?> ([^<]*)"[^<]*>)/;
        var repstr = str.replace(pattern, '');
        jQuery('#the_content_<?php echo $gallery->gallery_id; ?>').val(repstr);    
            
		jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/delete-album.php'); ?>", data: 'gallery_id=<?php echo $gallery->gallery_id; ?>&post_id=<?php echo $gallery->post_id; ?>', success: function(data)
			{
				jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/get_galleries.php'); ?>", data: 'post_id=<?php echo $_POST['post_id']; ?>', success: function(data)
				{
                    if (tinyMCE.activeEditor != null && tinyMCE.activeEditor.isHidden() == false) {
                        var html = tinyMCE.activeEditor.getContent();
                        var string_replace = html.replace(pattern,'');
                        tinyMCE.execCommand('mceSetContent',false,string_replace); 
                    } else {
                        replacePost(jQuery('#the_content_<?php echo $gallery->gallery_id; ?>').val());
                    }
                    //switchEditors.go('content', 'tinymce');
                    jQuery('#ph_gallery_button').show();
                    jQuery('#display_galleries').html(data);
				}
				});
			}
            });
		
            }
	
    });
    
	
	jQuery('#reinsert_button_<?php echo $gallery->gallery_id; ?>').on('click', function(){
	
		function insertInPost(gallery) {
            
			// IE support
			if (document.post.content.selection) {
				document.post.content.focus();
				sel = document.post.content.selection.createRange();
				sel.text = gallery;
			}
			// MOZILLA/NETSCAPE support
			else if (document.post.content.selectionStart || document.post.content.selectionStart == 0) {
				var startPos = document.post.content.selectionStart;
				var endPos = document.post.content.selectionEnd;
				document.post.content.value = document.post.content.value.substring(0, startPos) + gallery + document.post.content.value.substring(endPos,document.post.content.value.length);
            } else {
				document.post.content.value += gallery;
			}	
			
		}
		
        //switchEditors.go('content', 'html');
        if (tinyMCE.activeEditor != null && tinyMCE.activeEditor.isHidden() == false) {

			<?php if($gallery->gal_type == '6' || $gallery->gal_type == '7') { ?>
				
				tinyMCE.execCommand('mceInsertContent',false,'<img id="phgallery-<?php echo $gallery->gallery_id.' '.$gallery->gal_type; ?>" src="<?php echo photocrati_gallery_file_uri('image/album-placeholder-' . $galnumber[1] . '.gif'); ?>" alt="photocrati gallery" />');
				
			<?php } else { ?>
				
				tinyMCE.execCommand('mceInsertContent',false,'<img id="phgallery-<?php echo $gallery->gallery_id.' '.$gallery->gal_type; ?>" src="<?php echo photocrati_gallery_file_uri('image/gallery-placeholder-' . $galnumber[1] . '.gif'); ?>" alt="photocrati gallery" />');
				
			<?php } ?>
		
        } else {
            
			<?php if($gallery->gal_type == '6' || $gallery->gal_type == '7') { ?>
				
				insertInPost('<img id="phgallery-<?php echo $gallery->gallery_id.' '.$gallery->gal_type; ?>" src="<?php echo photocrati_gallery_file_uri('image/album-placeholder-' . $galnumber[1] . '.gif'); ?>" alt="photocrati gallery" />');
				
			<?php } else { ?>
				
				insertInPost('<img id="phgallery-<?php echo $gallery->gallery_id.' '.$gallery->gal_type; ?>" src="<?php echo photocrati_gallery_file_uri('image/gallery-placeholder-' . $galnumber[1] . '.gif'); ?>" alt="photocrati gallery" />');
				
			<?php } ?>
        
        }
        //switchEditors.go('content', 'tinymce');
        jQuery('#reinsert_button_<?php echo $gallery->gallery_id; ?>').hide();
    
	});
    
    var currContent<?php echo $gallery->gallery_id; ?> = jQuery('[id^=the_content_<?php echo $gallery->gallery_id; ?>]').val();
    var galID<?php echo $gallery->gallery_id; ?> = 'id="phgallery-<?php echo $gallery->gallery_id; ?>';
    var isGallery = currContent<?php echo $gallery->gallery_id; ?>.indexOf(galID<?php echo $gallery->gallery_id; ?>);
    
    if(isGallery == -1) {
        jQuery('#reinsert_button_<?php echo $gallery->gallery_id; ?>').show();
    }
});
</script>
<?php
        echo '<div style="float:left;width:75%;">';
		if($gallery->gal_type == '6' || $gallery->gal_type == '7') {
			
			if($gallery->gal_type == '6') {
				
				echo '<p><b>Title:</b> '.stripslashes($gallery->gal_title).' &nbsp;&nbsp;|&nbsp;&nbsp; <b>Album - List Style (ID #'.$galnumber[1].')</b></p>';
				
			} else {
				
				echo '<p><b>Title:</b> '.stripslashes($gallery->gal_title).' &nbsp;&nbsp;|&nbsp;&nbsp; <b>Album - Grid Style (ID #'.$galnumber[1].')</b></p>';
				
			}
			
			echo '<p><div style="float:left;"><a href="' . photocrati_gallery_file_uri('admin/upload_edit.php') . '?post='.$gallery->post_id.'&gallery_id='.$gallery->gallery_id.'&TB_iframe=true" class="thickbox"><img src="' . photocrati_gallery_file_uri('image/edit_album.jpg') . '" id="edit_button_'.$gallery->gallery_id.'" /></a></div>';
			echo '<div style="float:left;"><img src="' . photocrati_gallery_file_uri('image/delete_album.jpg') . '" id="delete_abutton_'.$gallery->gallery_id.'" style="cursor:pointer;margin:0 4px;" /></div>';
			echo '<div style="float:left;"><img src="' . photocrati_gallery_file_uri('image/reinsert_album.jpg') . '" id="reinsert_button_'.$gallery->gallery_id.'" style="cursor:pointer;margin:0 4px;display:none;" /></div>';
			echo '<div class="msggallery1" id="msggallery_'.$gallery->gallery_id.'"></div></p>';
			
		} else {
			
			echo '<p><b>Title:</b> '.stripslashes($gallery->gal_title).' &nbsp;&nbsp;|&nbsp;&nbsp; <b>Gallery (ID #'.$galnumber[1].')</b></p>';
			echo '<p><div style="float:left;"><a href="' . photocrati_gallery_file_uri('admin/upload_edit.php') . '?post='.$gallery->post_id.'&gallery_id='.$gallery->gallery_id.'&TB_iframe=true" class="thickbox"><img src="' . photocrati_gallery_file_uri('image/edit_gallery.jpg') . '" id="edit_button_'.$gallery->gallery_id.'" /></a></div>';
			echo '<div style="float:left;"><img src="' . photocrati_gallery_file_uri('image/delete_gallery.jpg') . '" id="delete_button_'.$gallery->gallery_id.'" style="cursor:pointer;margin:0 4px;" /></div>';
			echo '<div style="float:left;"><img src="' . photocrati_gallery_file_uri('image/reinsert_gallery.jpg') . '" id="reinsert_button_'.$gallery->gallery_id.'" style="cursor:pointer;margin:0 4px;display:none;" /></div>';
			echo '<div class="msggallery1" id="msggallery_'.$gallery->gallery_id.'"></div></p>';
		
		}
        echo '<input type="hidden" id="the_content_'.$gallery->gallery_id.'">';
        echo '</div>';
        echo '</div>';
	}
    } else {
		
	    echo '<p><b>There are no galleries associated with this page</b></p>';
		
    }
?>
