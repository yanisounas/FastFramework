<?php

namespace FastFramework\Response;

class Response
{
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/100
     */
    public const CONTINUE = 100;

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/101
     */
    public const SWITCHING_PROTOCOLS = 101;
    public const PROCESSING = 102;
    public const EARLY_HINT = 103;

    public const OK = 200;
    public const CREATED = 201;
    public const ACCEPTED = 202;
    public const NON_AUTHORITATIVE_INFORMATION = 203;
    public const NO_CONTENT = 204;
    public const RESET_CONTENT = 205;
    public const PARTIAL_CONTENT = 206;
    public const MULTI_STATUS = 207;
    public const ALREADY_REPORTED = 208;
    public const CONTENT_DIFFERENT = 210;
    public const IM_USED = 226;

    public const MULTIPLE_CHOICES = 300;
    public const MOVED_PERMANENTLY = 301;
    public const FOUND = 302;
    public const SEE_OTHER = 303;
    public const NOT_MODIFIED = 304;
    public const USE_PROXY = 305;
    public const SWITCH_PROXY = 306;
    public const TEMPORARY_REDIRECT = 307;
    public const PERMANENT_REDIRECT = 308;
    public const TOO_MANY_REDIRECTS = 310;

    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const PAYMENT_REQUIRED = 402;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const NOT_ACCEPTABLE = 406;
    public const PROXY_AUTH_REQUIRED = 407;
    public const REQUEST_TIME_OUT = 408;
    public const CONFLICT = 409;
    public const GONE = 410;
    public const LENGTH_REQUIRED = 411;
    public const PRECONDITION_FAILED = 412;
    public const REQUEST_ENTITY_TOO_LARGE = 413;
    public const REQUEST_URI_TOO_LONG = 414;
    public const UNSUPPORTED_MEDIA_TYPE = 415;
    public const REQUESTED_RANGE_UNSATISFIABLE = 416;
    public const EXPECTATION_FAILED = 417;
    public const BAD_MAPPING = 421;
    public const MISDIRECTED_REQUEST = 421;
    public const UNPROCESSABLE_ENTITY = 422;
    public const LOCKED = 423;
    public const METHOD_FAILURE = 424;
    public const TOO_EARLY = 425;
    public const UPGRADE_REQUIRED = 426;
    public const PRECONDITION_REQUIRED = 428;
    public const TOO_MANY_REQUESTS = 429;
    public const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    public const RETRY_WITH = 449;
    public const UNRECOVERABLE_ERROR = 456;

    public const INTERNAL_SERVER_ERROR = 500;
    public const NOT_IMPLEMENTED = 501;
    public const BAD_GATEWAY = 502;
    public const SERVICE_UNAVAILABLE = 503;
    public const GATEWAY_TIMEOUT = 504;
    public const HTTP_VERSION_NOT_SUPPORTED = 505;

    public function __construct(
        public ?string $content = null,
        private readonly ?int    $statusCode = null,
        private readonly ?string $contentType = null)
    {
        if ($this->statusCode)
            http_response_code($this->statusCode);

        if($this->contentType)
            header("Content-Type: $this->contentType;");

        if (is_string($this->content))
            echo $this->content;
    }
}