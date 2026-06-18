<?php

class isAdmin
{
    public static function check() : bool {
        if(isset($_SESSION['user']) && $_SESSION['user']['is_admin'] == 1) {
            return true;
        }
        return false;
    }
}