<?php

    if (!function_exists('active_role')) {
        function active_role(){
            return session('user_active_role');
        }
    }
    if (!function_exists('tipe_menus')) {
        function tipe_menus(){
            return session('tipe_menus');
        }
    }
    if (!function_exists('user_menus')) {
        function user_menus(){
            return session('user_menus');
        }
    }
