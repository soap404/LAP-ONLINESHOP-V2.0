<?php

class isUser
{
    public static function check() : bool {
        if(isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }
}