<?php 

if( !function_exists('module_path') ) {

    function module_path( $name, $path = null ) {
        
        $folder = 'Modules/';
        $path = $path ? '/' . ltrim($path, '/') : null;
        return base_path( "{$folder}{$name}{$path}" );

    }

}