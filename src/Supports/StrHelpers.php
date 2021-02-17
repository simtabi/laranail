<?php

namespace Simtabi\Laranail\Supports;

class StrHelpers
{

    /**
     * Generate initials from a name
     *
     * @param string $name
     * @return string
     */
    public static function generate(string $name) : string
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }
        return self::makeInitialsFromSingleWord($name);
    }

    /**
     * Make initials from a word with no spaces
     *
     * @param string $name
     * @return string
     */
    protected static function makeInitialsFromSingleWord(string $name) : string
    {
        preg_match_all('#([A-Z]+)#', $name, $capitals);
        if (count($capitals[1]) >= 2) {
            return substr(implode('', $capitals[1]), 0, 2);
        }
        return strtoupper(substr($name, 0, 2));
    }

    public static function generateRandomUsername(string $firstName = "John", string $lastName = "Doe", int $randNo = 1000){
        $buildFullName = $firstName . " " . $lastName;
        $usernameParts = array_filter(explode(" ", strtolower($buildFullName))); //explode and lowercase name
        $usernameParts = array_slice($usernameParts, 0, 2); //return only first two array part

        $part1 = (!empty($usernameParts[0]))?substr($usernameParts[0], 0,8):""; //cut first name to 8 letters
        $part2 = (!empty($usernameParts[1]))?substr($usernameParts[1], 0,5):""; //cut second name to 5 letters
        $part3 = ($randNo) ? rand(0, $randNo) : "";

        $out   = $part1. str_shuffle($part2). $part3; //str_shuffle to randomly shuffle all characters

        return strtolower(trim($out));
    }

    public static function generateUsernameFromEmail(string $email): string
    {
        // Split the username and domain from the email
        $parts = explode('@',$email);
        return strtolower(trim($parts[0]));
    }

    public static function generateDomainFromEmail(string $email): string
    {
        // Split the username and domain from the email
        $parts = explode('@',$email);
        return strtolower(trim($parts[1]));
    }

}
