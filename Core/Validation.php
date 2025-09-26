<?php
namespace Deadt\RatchetChatPractice\Core;

class Validation {
    public static function username($input) {
        return preg_match("/^[a-zA-Z0-9_]{3,20}$/", $input);
    }

    public static function message($input) {
        return preg_match("/^.{1,200}$/", $input);
    }
}
