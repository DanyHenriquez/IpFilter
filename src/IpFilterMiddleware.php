<?php

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Prezto\IpFilter\Mode as Mode;

namespace Prezto\IpFilter;

class IpFilterMiddleware
{

    protected $addresses = [];
    protected $mode = null;
    protected $allowed = null;

    public function __construct($addresses = [], $mode = Mode::ALLOW)
    {
        $this->patterns = $addresses;
        $this->mode = $mode;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if ($this->mode == Mode::ALLOW)
            $this->allowed = $this->allow($request);

        if ($this->mode == Mode::DENY)
            $this->allowed = $this->deny($request);

        if (!$this->allowed)
            $response = $response->withStatus(401);

        $response = $next($request, $response);

        return $response;
    }

    public function allow(Request $request)
    {
        $clientAddress = ip2long($_SERVER["REMOTE_ADDR"]);

        if (in_array($clientAddress, $this->addresses))
            return false;

        return true;
    }

    public function deny(Request $request)
    {
        $clientAddress = ip2long($_SERVER["REMOTE_ADDR"]);

        if (in_array($clientAddress, $this->addresses))
            return true;

        return false;
    }

    public function addIpRange($start, $end)
    {
        foreach (range(ip2long($start), ip2long($end)) as $address)
            $this->addresses[] = $address;

        return $this;
    }

    public function addIp($ip)
    {
        $this->addresses[] = $ip;
        return $this;
    }
}