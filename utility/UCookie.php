<?php
/**
 * class to access to $_COOKIE superglobal array, You must use this class and not directly the _COOKIE array
 */
class UCookie
{
    /**
     * check if is set the specific id in the COOKIE
     * @return bool
     */
    public static function isSet($id)
    {
        if (isset($_COOKIE[$id])) {
            return true;
        } else {
            return false;
        }
    }
    // Imposta un cookie
    public static function setCookie(string $name, string $value): void
    {
        setcookie($name, $value, time() + COOKIE_EXP_TIME, '/');
    }

    // Legge un cookie
    public static function getCookie(string $name): ?string
    {
        return $_COOKIE[$name] ?? null;
    }

    public static function destroyCookie(string $name): void
    {
        setcookie($name, '', time() - 3600, '/');
    }

}