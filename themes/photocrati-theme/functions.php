<?php

function photocrati_mkdir($dir, $perm=0755)
{
    $upload_dir = wp_upload_dir();
    if (is_writable($upload_dir['basedir'])) {
        $upload_dir_perm = @fileperms($upload_dir['basedir']);
        if ($upload_dir_perm) {
            $upload_dir_perm = intval(substr(sprintf("%o", $upload_dir_perm), -4));
            if ($upload_dir_perm > $perm) $perm = $upload_dir_perm;
        }
    }

    if (!file_exists($dir)) mkdir($dir, octdec($perm));
    @chmod($dir, octdec($perm));
}

// These are essential functions for the operation of this theme. 
// DO NOT REMOVE these included files!

include 'bootstrap.php';

// You can insert custom functions below this line
// -----------------------------------------------
?>
