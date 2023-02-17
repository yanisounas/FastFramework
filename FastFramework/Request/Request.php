<?php
declare(strict_types=1);

namespace FastFramework\Request;

class Request
{
    /**
     * @param array $subject
     * @param string|null $key
     * @return mixed
     */
    private static function _process(array $subject, ?string $key = null): mixed
    {
        if ($key === null) return (empty($subject)) ? null : $subject;
        return (isset($subject[$key])) ? $subject[$key] : null;
    }

    public static function METHOD() { return $_SERVER["REQUEST_METHOD"]; }

    /**
     * @param string|null $key
     * @return mixed
     */
    public static function get(?string $key = null): mixed { return self::_process($_GET, $key); }

    /**
     * @param string|null $key
     * @return mixed
     */
    public static function post(?string $key = null): mixed { return self::_process($_POST, $key); }

    /**
     * @param string|null $key
     * @return mixed
     */
    public static function file(?string $key = null): mixed { return self::_process($_FILES, $key); }

    /**
     * @param string|null $key
     * @return mixed
     */
    public static function input(?string $key = null): mixed { return self::_process(json_decode(file_get_contents("php://input"), true), $key); }

    /**
     * @param string|null $key
     * @return mixed
     */
    public static function session(?string $key = null): mixed
    {
        self::useSession();
        return self::_process($_SESSION, $key);
    }

    /**
     * @return void
     */
    public static function useSession(): void { if (session_status() !== PHP_SESSION_ACTIVE) session_start(); }

    /**
     * @return void
     */
    public static function clearSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) return;
        session_unset();
        session_destroy();
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function setSession(string $key, mixed $value): void
    {
        self::useSession();
        $_SESSION[$key] = $value;
    }

    /**
     * @param array $keyValues
     * @return void
     */
    public static function setSessions(array $keyValues): void { foreach ($keyValues as $key => $value) self::setSession($key, $value); }
}