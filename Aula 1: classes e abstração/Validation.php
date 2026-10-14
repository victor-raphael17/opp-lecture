<?php

class Validation {
    public static function validateEmail($email): bool {
        $isValid = filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
        return $isValid;
    }

    public static function validatePhoneNumber($phoneNumber): bool {
        $isValid = preg_match('/^\+?[0-9]{10,15}$/', $phoneNumber) ? true : false;
        return $isValid;
    }
    // '/^\+?[0-9]{10,15}$/' The regular expression `/^\+?[0-9]{10,15}$/` is used to validate phone numbers. Here's a breakdown of its components:
    // ^ asserts the start of the string
    // \+? matches an optional plus sign (+) at the beginning of the string
    // [0-9]{10,15} matches between 10 and 15 digits
}

print (Validation::validateEmail("victorraphaellima2005@gmail.com") ? "Valid email" : "Invalid email") . PHP_EOL;
print (Validation::validatePhoneNumber("+1234567890") ? "Valid phone number" : "Invalid phone number") . PHP_EOL;