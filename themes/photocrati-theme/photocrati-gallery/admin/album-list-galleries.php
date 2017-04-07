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

$SQL = "SELECT * FROM ".$wpdb->prefix."photocrati_gallery_ids WHERE post_id = ".((int)$_POST['post']);

$gals = $wpdb->get_results($SQL);
$i = 0;
$galarray = array();
foreach ($gals as $gals) {

	if($i == 0) {
		$galid = '';
	}

	if(!in_array($gals->gallery_id, $galarray)) {
		$galarray[] = $gals->gallery_id;
		$galid = $gals->gallery_id;
	}

	$i = $i + 1;
}

$gallast = end($galarray);
$gallastid = explode("_", $gallast);
$galnextid = (int)$gallastid[1]+1;

?>
<link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('template_directory'); ?>/admin/admin.css" />
<script type="text/javascript" src="<?php echo includes_url('js/jquery/jquery.js')?>"></script>
<?php if (version_compare(get_bloginfo('version'), '4.1', '>=')): ?>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/core.min.js ')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/widget.min.js ')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/mouse.min.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/draggable.min.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/sortable.min.js')?>"></script>
<?php else: ?>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/jquery.ui.core.min.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/jquery.ui.widget.min.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/jquery.ui.mouse.min.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/jquery.ui.draggable.min.js')?>"></script>
<script type="text/javascript" src="<?php echo includes_url('js/jquery/ui/jquery.ui.sortable.min.js')?>"></script>
<?php endif ?>
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function()
{

	jQuery("#sortable_album").sortable({
		revert: true,
        opacity: 0.75,
        update: function(event, ui) {
            var info = jQuery(this).sortable("toArray");
            var sort = 1;
            jQuery.each(
                info,
                    function( intIndex, objValue ){
                        jQuery('#album_order_'+objValue.substr(15)).val(sort);
                        sort++;
                    }
            );
        }
	});

	jQuery('[id^=save_album_]').on("click", function(){

		function saveAlbum() {
			var loop = 0;
			var galsize = jQuery("input[name='album']:checked").size();
			jQuery("input[name='album']:checked").each(function(index) {
				jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/save-album-galleries.php'); ?>", data: 'album_id=<?php echo $_POST['post']; ?>_<?php echo $galnextid; ?>&gallery_id='+jQuery(this).val()+'&album_order='+jQuery('#album_order_'+jQuery(this).val()).val(), success: function(data)
					{
						if(loop == 0) {
						jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/save-gallery.php'); ?>", data: 'gallery_id=<?php echo $_POST['post']; ?>_<?php echo $galnextid; ?>&post_id=<?php echo $_POST['post']; ?>&gal_title='+jQuery("input[name='gal_title']").val().replace("&","%26")+'&gal_type='+jQuery("input[name='gal_type']:checked").val(), success: function(data)
						{}});
						}
						loop++;
						if(loop == galsize) {


                            if (parent.tinyMCE.activeEditor != null && parent.tinyMCE.activeEditor.isHidden() == false) {

                                parent.tinyMCE.execCommand('mceInsertContent',false,'<img id="phgallery-<?php echo $_POST['post']; ?>_<?php echo $galnextid; ?> '+jQuery("input[name='gal_type']:checked").val()+'" src="<?php echo photocrati_gallery_file_uri('image/album-placeholder-' . $galnextid . '.gif'); ?>" alt="photocrati gallery" />');
								parent.jQuery('[id^=the_content_]').val(parent.document.post.content.value);

                            } else {

                                insertInPost('<img id="phgallery-<?php echo $_POST['post']; ?>_<?php echo $galnextid; ?> '+jQuery("input[name='gal_type']:checked").val()+'" src="<?php echo photocrati_gallery_file_uri('image/album-placeholder-' . $galnextid . '.gif'); ?>" alt="photocrati gallery" />');
								parent.jQuery('[id^=the_content_]').val(parent.document.post.content.value);

                            }

							jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/get_galleries.php'); ?>", data: 'post_id=<?php echo $_POST['post']; ?>', success: function(data)
								{

                                    parent.jQuery('#display_galleries').html(data);
								    parent.tb_remove();
									alert("Remember, you must update or publish your page before gallery changes will take effect!");
									parent.jQuery('#reinsert_button_<?php echo $_POST['post'].'_'.$galnextid; ?>').hide();
								}
							});

						}
					}
				});
			});
		}

		function insertInPost(gallery) {
			// IE support
			if (parent.document.post.content.selection) {
				parent.document.post.content.focus();
				sel = parent.document.post.content.selection.createRange();
				sel.text = gallery;
			}
			// MOZILLA/NETSCAPE support
			else if (parent.document.post.content.selectionStart || parent.document.post.content.selectionStart == 0) {
				var startPos = parent.document.post.content.selectionStart;
				var endPos = parent.document.post.content.selectionEnd;
				parent.document.post.content.value = parent.document.post.content.value.substring(0, startPos) + gallery + parent.document.post.content.value.substring(endPos,parent.document.post.content.value.length);
			} else {
				parent.document.post.content.value += gallery;
			}

		}

        jQuery("#msggallery4").html("Saving Album - Please Wait");
		jQuery("#msggallery4")
			.fadeIn('slow')
			.animate({opacity: 1.0}, 2000);
		saveAlbum();

	});

	jQuery('[id^=update_album_]').on("click", function(){

	jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/delete-album.php'); ?>", data: 'gallery_id=<?php echo $_POST['gallery_id']; ?>', success: function(data)
	{

        function replacePost(gallery) {

            parent.document.post.content.value = gallery;

		}

        function insertInPost(gallery) {
			// IE support
			if (parent.document.post.content.selection) {
				parent.document.post.content.focus();
				sel = parent.document.post.content.selection.createRange();
				sel.text = gallery;
			}
			// MOZILLA/NETSCAPE support
			else if (parent.document.post.content.selectionStart || parent.document.post.content.selectionStart == 0) {
				var startPos = parent.document.post.content.selectionStart;
				var endPos = parent.document.post.content.selectionEnd;
				parent.document.post.content.value = parent.document.post.content.value.substring(0, startPos) + gallery + parent.document.post.content.value.substring(endPos,parent.document.post.content.value.length);
			} else {
				parent.document.post.content.value += gallery;
			}

		}

		function updateAlbum() {
			var loop = 0;
			var galsize = jQuery("input[name='album']:checked").size();
			jQuery("input[name='album']:checked").each(function(index) {
                var currentId = jQuery(this).attr('id');
				jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/save-album-galleries.php'); ?>", data: 'album_id=<?php echo $_POST['gallery_id']; ?>&gallery_id='+jQuery(this).val()+'&album_order='+jQuery('#album_order_'+jQuery(this).val()).val(), success: function(data)
					{
						if(loop == 0) {
						jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/save-gallery.php'); ?>", data: 'gallery_id=<?php echo $_POST['gallery_id']; ?>&post_id=<?php echo $_POST['post']; ?>&gal_type='+jQuery("input[name='gal_type']:checked").val()+'&gal_title='+jQuery("input[name='gal_title']").val().replace("&","%26"), success: function(data)
						{}});
						}
						loop++;
						if(loop == galsize) {

                            if (parent.tinyMCE.activeEditor != null && parent.tinyMCE.activeEditor.isHidden() == false) {

                                parent.tinyMCE.execCommand('mceInsertContent',false,'<img id="phgallery-<?php echo $_POST['gallery_id']; ?> '+jQuery("input[name='gal_type']:checked").val()+'" src="<?php echo photocrati_gallery_path_uri('image/album-placeholder-'); ?>'+jQuery("#gal_number").val()+'.gif" alt="photocrati gallery" />');

                            } else {

                                insertInPost('<img id="phgallery-<?php echo $_POST['gallery_id']; ?> '+jQuery("input[name='gal_type']:checked").val()+'" src="<?php echo photocrati_gallery_path_uri('image/album-placeholder-'); ?>'+jQuery("#gal_number").val()+'.gif" alt="photocrati gallery" />');

                            }

							jQuery.ajax({type: "POST", url: "<?php echo photocrati_gallery_file_uri('admin/get_galleries.php'); ?>", data: 'post_id=<?php echo $_POST['post']; ?>', success: function(data)
								{
								    parent.jQuery('#display_galleries').html(data)
								    parent.tb_remove();
									alert("Remember, you must update or publish your page before gallery changes will take effect!");
									parent.jQuery('#reinsert_button_<?php echo $_POST['gallery_id']; ?>').hide();
								}
							});

						}
					}
				});
			});
		}

		jQuery("#msggallery4").html("Updating Album - Please Wait");
		jQuery("#msggallery4")
			.fadeIn('slow')
			.animate({opacity: 1.0}, 2000);

        var str = parent.jQuery('#the_content_<?php echo $_POST['gallery_id']; ?>').val();
        var pattern = /(<img([^<]*)id="phgallery-<?php echo $_POST['gallery_id']; ?> ([^<]*)"[^<]*>)/;
        var repstr = str.replace(pattern, '');
        parent.jQuery('#the_content_<?php echo $_POST['gallery_id']; ?>').val(repstr);
        if (parent.tinyMCE.activeEditor != null && parent.tinyMCE.activeEditor.isHidden() == false) {
            var html = parent.tinyMCE.activeEditor.getContent();
            var string_replace = html.replace(pattern,'');
            //parent.tinyMCE.execCommand('mceSetContent',false,string_replace);
			parent.tinyMCE.activeEditor.setContent(string_replace);
			//parent.tinyMCE.execCommand('mceInsertContent',false,string_replace);
        } else {
            replacePost(parent.jQuery('#the_content_<?php echo $_POST['gallery_id']; ?>').val());
        }

        updateAlbum();

	}
    });

	});

});
</script>

<h2 style="margin:20px 0;">Select Galleries Using the Checkbox / Drag and Drop the Galleries to Re-Order</h2>

<div class="clear"></div>

<ul id="sortable_album">

<?php

	$albumid = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_albums WHERE album_id = '". $wpdb->escape($_POST['gallery_id'])."' ORDER BY album_order ASC");
	foreach ($albumid as $albumid) {

    $gallery = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_ids WHERE gallery_id = '".$albumid->gallery_id."'");
    if($wpdb->num_rows != 0) {

		foreach ($gallery as $gallery) {

			if($gallery->gal_type == '6' || $gallery->gal_type == '7') {

			} else {

				if($_POST['edit']) {

					$galleryid = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_albums WHERE gallery_id = '".$gallery->gallery_id."' and album_id = '" . $wpdb->escape($_POST['gallery_id']) . "' ORDER BY gallery_id ASC");

					foreach ($galleryid as $galleryid) {

						$id = $gallery->gallery_id;
						${"checked_$id"} = 'checked="checked"';
						${"order_$id"} = $galleryid->album_order;

					}

				}

				$id2 = $gallery->gallery_id;

				if(${"order_$id2"}) {

					echo '<li class="sortable_album" id="sortable_album_' . $id2 . '">';
					echo '<div id="album_wrapper">';
					$image = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '".$gallery->gallery_id."' ORDER BY image_order,image_name LIMIT 1");
					foreach ($image as $image) {
						if(!function_exists('gd_info')) {

							if (file_exists($upload_dir['basedir'].'/galleries/post-'.$gallery->post_id.'/'.$image->image_name)) {
								echo '<div class="image"><img src="'.$upload_dir['baseurl'].'/galleries/post-'.$gallery->post_id.'/'.$image->image_name.'"><div class="checkbox"><input type="checkbox" name="album" id="album_'.$gallery->gallery_id.'" value="'.$gallery->gallery_id.'" '.${"checked_$id2"}.'><input type="hidden" name="album_order" id="album_order_'.$gallery->gallery_id.'" value="';
							} else {
								echo '<div class="image"><img src="'.get_bloginfo('template_url').'/galleries/post-'.$gallery->post_id.'/'.$image->image_name.'"><div class="checkbox"><input type="checkbox" name="album" id="album_'.$gallery->gallery_id.'" value="'.$gallery->gallery_id.'" '.${"checked_$id2"}.'><input type="hidden" name="album_order" id="album_order_'.$gallery->gallery_id.'" value="';
							}

						if (${"order_$id2"} <> '') {
						echo ${"order_$id2"};
						} else {
						echo '0';
						}
						echo '"></div></div>';
						} else {

							if (file_exists($upload_dir['basedir'].'/galleries/post-'.$gallery->post_id.'/thumbnails/'.$image->image_name)) {
								echo '<div class="image"><img src="'.$upload_dir['baseurl'].'/galleries/post-'.$gallery->post_id.'/thumbnails/'.$image->image_name.'"><div class="checkbox"><input type="checkbox" name="album" id="album_'.$gallery->gallery_id.'" value="'.$gallery->gallery_id.'" '.${"checked_$id2"}.'><input type="hidden" name="album_order" id="album_order_'.$gallery->gallery_id.'" value="';
							} else {
								echo '<div class="image"><img src="'.get_bloginfo('template_url').'/galleries/post-'.$gallery->post_id.'/thumbnails/'.$image->image_name.'"><div class="checkbox"><input type="checkbox" name="album" id="album_'.$gallery->gallery_id.'" value="'.$gallery->gallery_id.'" '.${"checked_$id2"}.'><input type="hidden" name="album_order" id="album_order_'.$gallery->gallery_id.'" value="';
							}

						if (${"order_$id2"} <> '') {
						echo ${"order_$id2"};
						} else {
						echo '0';
						}
						echo '"></div></div>';
						}
					}

					echo '<div class="title">';
					echo '<b>Title:</b> '.stripslashes($gallery->gal_title).'';
					echo '</div>';
					echo '</div>';
					echo '</li>';

				}

			}

		}

    }

	}

?>

<?php
    $gallery = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_gallery_ids ORDER BY gallery_id ASC");
    if($wpdb->num_rows != 0) {

		foreach ($gallery as $gallery) {

			if($gallery->gal_type == '6' || $gallery->gal_type == '7') {

			} else {

				if($_POST['edit']) {

					$galleryid = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_albums WHERE gallery_id = '".$gallery->gallery_id."' and album_id = '" . $wpdb->escape($_POST['gallery_id']) . "' ORDER BY gallery_id ASC");

					foreach ($galleryid as $galleryid) {

						$id = $gallery->gallery_id;
						${"checked_$id"} = 'checked="checked"';
						${"order_$id"} = $galleryid->album_order;

					}

				}

				$id2 = $gallery->gallery_id;

				if(!${"order_$id2"}) {

					echo '<li class="sortable_album" id="sortable_album_' . $id2 . '">';
					echo '<div id="album_wrapper">';
					$image = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."photocrati_galleries WHERE gallery_id = '".$gallery->gallery_id."' ORDER BY image_order,image_name LIMIT 1");
					foreach ($image as $image) {
						if(!function_exists('gd_info')) {

							if (file_exists($upload_dir['basedir'].'/galleries/post-'.$gallery->post_id.'/'.$image->image_name)) {
								echo '<div class="image"><img src="'.$upload_dir['baseurl'].'/galleries/post-'.$gallery->post_id.'/'.$image->image_name.'"><div class="checkbox"><input type="checkbox" name="album" id="album_'.$gallery->gallery_id.'" value="'.$gallery->gallery_id.'" '.${"checked_$id2"}.'><input type="hidden" name="album_order" id="album_order_'.$gallery->gallery_id.'" value="';
							} else {
								echo '<div class="image"><img src="'.get_bloginfo('template_url').'/galleries/post-'.$gallery->post_id.'/'.$image->image_name.'"><div class="checkbox"><input type="checkbox" name="album" id="album_'.$gallery->gallery_id.'" value="'.$gallery->gallery_id.'" '.${"checked_$id2"}.'><input type="hidden" name="album_order" id="album_order_'.$gallery->gallery_id.'" value="';
							}

						if (${"order_$id2"} <> '') {
						echo ${"order_$id2"};
						} else {
						echo '0';
						}
						echo '"></div></div>';
						} else {

							if (file_exists($upload_dir['basedir'].'/galleries/post-'.$gallery->post_id.'/'.$image->image_name)) {
								echo '<div class="image"><img src="'.$upload_dir['baseurl'].'/galleries/post-'.$gallery->post_id.'/thumbnails/'.$image->image_name.'"><div class="checkbox"><input type="checkbox" name="album" id="album_'.$gallery->gallery_id.'" value="'.$gallery->gallery_id.'" '.${"checked_$id2"}.'><input type="hidden" name="album_order" id="album_order_'.$gallery->gallery_id.'" value="';
							} else {
								echo '<div class="image"><img src="'.get_bloginfo('template_url').'/galleries/post-'.$gallery->post_id.'/thumbnails/'.$image->image_name.'"><div class="checkbox"><input type="checkbox" name="album" id="album_'.$gallery->gallery_id.'" value="'.$gallery->gallery_id.'" '.${"checked_$id2"}.'><input type="hidden" name="album_order" id="album_order_'.$gallery->gallery_id.'" value="';
							}

						if (${"order_$id2"} <> '') {
						echo ${"order_$id2"};
						} else {
						echo '0';
						}
						echo '"></div></div>';
						}
					}

					echo '<div class="title">';
					echo '<b>Title:</b> '.stripslashes($gallery->gal_title).'';
					echo '</div>';
					echo '</div>';
					echo '</li>';

				}

			}

		}

    } else {
	    echo '<p><b>There are currently no saved galleries in the system</b></p>';
    }

?>

</ul>

<div class="clear"></div>
<div style="float:left;cursor:pointer;">
<?php if($_POST['edit']) { ?>
<input type="image" src="<?php echo photocrati_gallery_file_uri('image/update_album.jpg'); ?>" id="update_album_1" value="Update Album" onclick="return false;" />
<?php } else { ?>
<input type="image" src="<?php echo photocrati_gallery_file_uri('image/save_album.jpg'); ?>" id="save_album_1" value="Save Album" onclick="return false;" />
<?php } ?>
</div>
<div id="msggallery4"></div>
