<?php

namespace FastFramework\Http;

class Utils
{
    //TODO: Check that codes and reason phrases match RFC 7231 and IANA recommendations (https://datatracker.ietf.org/doc/html/rfc7231#section-6.2, https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml)

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/100
     */
    public const CONTINUE = 100;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/101
     */
    public const SWITCHING_PROTOCOLS = 101;
    /**
     * @link https://httpstatus.in/102/
     */
    public const PROCESSING = 102;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/103
     */
    public const EARLY_HINT = 103;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/200
     */
    public const OK = 200;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/201
     */
    public const CREATED = 201;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/202
     */
    public const ACCEPTED = 202;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/203
     */
    public const NON_AUTHORITATIVE_INFORMATION = 203;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/204
     */
    public const NO_CONTENT = 204;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/205
     */
    public const RESET_CONTENT = 205;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/206
     */
    public const PARTIAL_CONTENT = 206;
    /**
     * @link https://httpstatus.in/207/
     */
    public const MULTI_STATUS = 207;
    /**
     * @link https://httpstatus.in/208/
     */
    public const ALREADY_REPORTED = 208;
    public const CONTENT_DIFFERENT = 210;
    /**
     * @link https://httpstatus.in/226/
     */
    public const IM_USED = 226;

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/300
     */
    public const MULTIPLE_CHOICES = 300;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/301
     */
    public const MOVED_PERMANENTLY = 301;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/302
     */
    public const FOUND = 302;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/303
     */
    public const SEE_OTHER = 303;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/304
     */
    public const NOT_MODIFIED = 304;
    public const USE_PROXY = 305;
    public const SWITCH_PROXY = 306;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/307
     */
    public const TEMPORARY_REDIRECT = 307;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/308
     */
    public const PERMANENT_REDIRECT = 308;
    public const TOO_MANY_REDIRECTS = 310;

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/400
     */
    public const BAD_REQUEST = 400;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/401
     */
    public const UNAUTHORIZED = 401;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/402
     */
    public const PAYMENT_REQUIRED = 402;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/403
     */
    public const FORBIDDEN = 403;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/404
     */
    public const NOT_FOUND = 404;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/405
     */
    public const METHOD_NOT_ALLOWED = 405;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/406
     */
    public const NOT_ACCEPTABLE = 406;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/407
     */
    public const PROXY_AUTH_REQUIRED = 407;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/408
     */
    public const REQUEST_TIME_OUT = 408;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/409
     */
    public const CONFLICT = 409;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/410
     */
    public const GONE = 410;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/411
     */
    public const LENGTH_REQUIRED = 411;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/412
     */
    public const PRECONDITION_FAILED = 412;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/413
     */
    public const REQUEST_ENTITY_TOO_LARGE = 413;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/414
     */
    public const REQUEST_URI_TOO_LONG = 414;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/415
     */
    public const UNSUPPORTED_MEDIA_TYPE = 415;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/416
     */
    public const REQUESTED_RANGE_UNSATISFIABLE = 416;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/417
     */
    public const EXPECTATION_FAILED = 417;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/418
     */
    public const IM_A_TEAPOT = 418;
    public const PAGE_EXPIRED = 419;
    public const BAD_MAPPING = 421;
    public const MISDIRECTED_REQUEST = 421;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/422
     */
    public const UNPROCESSABLE_ENTITY = 422;
    public const LOCKED = 423;
    public const FAILED_DEPENDENCY = 424;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/425
     */
    public const TOO_EARLY = 425;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/426
     */
    public const UPGRADE_REQUIRED = 426;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/428
     */
    public const PRECONDITION_REQUIRED = 428;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/429
     */
    public const TOO_MANY_REQUESTS = 429;
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/431
     */
    public const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    /**
     * @link https://learn.microsoft.com/en-us/openspecs/windows_protocols/ms-wdv/83ecf19f-e0f8-4706-aae5-ba618f52f100
     */
    public const RETRY_WITH = 449;

    public const INTERNAL_SERVER_ERROR = 500;
    public const NOT_IMPLEMENTED = 501;
    public const BAD_GATEWAY = 502;
    public const SERVICE_UNAVAILABLE = 503;
    public const GATEWAY_TIMEOUT = 504;
    public const HTTP_VERSION_NOT_SUPPORTED = 505;

    public const REASON_PHRASE = [
        100 => "Continue",
        101 => "Switching Protocols",
        102 => "Processing",
        103 => "Early Hint",
        200 => "Ok",
        201 => "Created",
        203 => "Non Authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        207 => "Multi Status",
        208 => "Already Reported",
        210 => "Content Different",
        226 => "I'm Used",
        300 => "Multiple Choices",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        306 => "Switch Proxy",
        307 => "Temporary Redirect",
        308 => "Permanent Redirect",
        310 => "Too Many Redirects",
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        407 => "Proxy Authentication Required",
        408 => "Request Time-Out",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Request Entity Too Large",
        414 => "Request-URI Too Long",
        415 => "Unsupported Media Type",
        416 => "Requested Range Unsatisfiable",
        417 => "Expectation Failed",
        418 => "I’m a Teapot",
        419 => "Page Expired",
        421 => "Bad Mapping / Misdirected Request",
        422 => "Unprocessable Entity",
        423 => "Locked",
        424 => "Method Failure",
        425 => "Too Early",
        426 => "Upgrade Required",
        428 => "Precondition Required",
        429 => "Too Many Requests",
        431 => "Request Header Fields Too Large",
        449 => "Retry With",
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway",
        503 => "Service Unavailable",
        504 => "Gateway Time-out",
        505 => "HTTP Version Not Supported",
    ];

    public static function getReasonPhraseFromCode(int $code): string { return static::REASON_PHRASE[$code] ?? ""; }
}