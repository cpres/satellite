<?php


class SatellitePremium extends SatellitePlugin 
{
    
    function __construct() 
    {

        add_action('admin_init', array( $this, 'prem_upgrade_plugin' ));        

    }

    // Initial install of plugin move pro plugin to uploads
    function prem_activate_plugin() 
    {
        $currDate	= date('F j, Y, g:i a', time());
        //echo("<script type='text/javascript'>alert('include!".$currDate."');</script>");
        if(SATL_PRO){
            if(!file_exists(SATL_UPLOADPRO_DIR)){
                if(!$this->get_option('premium_orig')){
                        $this->add_option('premium_orig', $styles);
                }else{
                        $this->update_option('premium_orig', $styles);
                }
                $this->copy_directory(SATL_PLUGINPRO_DIR, SATL_UPLOADPRO_DIR);
            }
        }

    }    
    
    // On Automatic update, pro directory gets removed and needs to be replaced
    function prem_upgrade_plugin(){
        if(!SATL_PRO){
            if(file_exists(SATL_UPLOADPRO_DIR)){
                $this->copy_directory(SATL_UPLOADPRO_DIR, SATL_PLUGINPRO_DIR);
            }
        }
    }

    function copy_directory( $source, $destination ) {
        if ( is_dir( $source ) ) {
            @mkdir( $destination );
            $directory = dir( $source );
            while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
                    if ( $readdirectory == '.' || $readdirectory == '..' ) {
                            continue;
                    }
                    $PathDir = $source . '/' . $readdirectory; 
                    if ( is_dir( $PathDir ) ) {
                            $this->copy_directory( $PathDir, $destination . '/' . $readdirectory );
                            continue;
                    }
                    copy( $PathDir, $destination . '/' . $readdirectory );
            }

            $directory->close();
        }else {
            copy( $source, $destination );
        }
    }

    function check_pro_dirs() {
        if ($this->folderSize(SATL_UPLOADPRO_DIR) != $this->folderSize(SATL_PLUGINPRO_DIR) ) {
            $this->remove_dir(SATL_UPLOADPRO_DIR);
            $this->copy_directory(SATL_PLUGINPRO_DIR, SATL_UPLOADPRO_DIR);
            $this->render_msg("You've just updated your premium plugin! Congratulations and enjoy the new features.");
        }
    }

    function remove_dir($dir) {
        if (is_dir($dir)) {
         $objects = scandir($dir);
         foreach ($objects as $object) {
           if ($object != "." && $object != "..") {
                 if (filetype($dir."/".$object) == "dir") $this->remove_dir($dir."/".$object); else unlink($dir."/".$object);
           }
         }
         try {
             reset($objects);
             rmdir($dir);
         } catch (Exception $e) {
             echo "Removing the directory did not work:". $e->getMessage();
         }
       }
       return true;
     }

    function folderSize($path) {
        $total_size = 0;
        $files = scandir($path);
        $cleanPath = rtrim($path, '/'). '/';

        foreach( $files as $t ) {
            if ( $t<>"." && $t<>".." ) {
                $currentFile = $cleanPath . $t;
                if (is_dir($currentFile)) {
                    $size = $this->folderSize($currentFile);
                    $total_size += $size;
                } else {
                    $size = filesize($currentFile);
                    $total_size += $size;
                }
            }
        }

        return $total_size;
    }
    
}
$SatellitePremium = new SatellitePremium();
?>
