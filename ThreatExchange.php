<?php

namespace Certly\ThreatExchange;

use GuzzleHttp\Client;

class ThreatExchange
{
    /**
     * The Application ID used to authenticate with ThreatExchange.
     *
     * @var string
     */
    protected $appId;

    /**
     * The Application Secret used to authenticate with ThreatExchange.
     *
     * @var string
     */
    protected $appSecret;

    /**
     * The Guzzle instance used to connect to ThreatExchange.
     *
     * @var string
     */
    protected $guzzle;

    /**
     * The Base URI of the Graph API.
     *
     * @var string
     */
    protected $baseUri = 'https://graph.facebook.com/v2.5/';

    /**
     * Create a new ThreatExchange client.
     *
     * @param string                 $appId
     * @param string                 $appSecret
     * @param null|GuzzleHttp\Client $guzzle
     *
     * @return void
     */
    public function __construct($appId, $appSecret, Client $guzzle = null)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->guzzle = ($guzzle ? $guzzle : new Client(['base_uri' => $this->baseUri]));
    }

    /**
     * Get all ThreatExchange members.
     *
     * @return stdClass
     */
    public function getThreatExchangeMembers()
    {
        return $this->call('threat_exchange_members', 'GET');
    }

    /**
     * Get all threat indicators matching the specified criteria.
     *
     * @param string $type
     * @param string $query
     * @param array  $options
     *
     * @return stdClass
     */
    public function getThreatIndicators($type, $query, $options = [])
    {
        return $this->call('threat_indicators', 'GET', array_replace_recursive([
            'type' => $type,
            'text' => $query,
        ], $options));
    }

    /**
     * Get all threat descriptors matching the specified criteria.
     *
     * @param string $type
     * @param string $query
     * @param array  $options
     *
     * @return stdClass
     */
    public function getThreatDescriptors($type, $query, $options = [])
    {
        return $this->call('threat_descriptors', 'GET', array_replace_recursive([
            'type' => $type,
            'text' => $query,
        ], $options));
    }

    /**
     * Get the next page of results from a pagination URL obtained from a previous request.
     *
     * @param string $url
     *
     * @return stdClass
     */
    public function next($url)
    {
        return $this->call($url, 'GET');
    }

    /**
     * Call a ThreatExchange or Graph API endpoint via GET.
     *
     * @param string $endpoint
     * @param array  $options
     *
     * @return stdClass
     */
    public function get($endpoint, $options = [])
    {
        return $this->call($endpoint, 'GET', $options);
    }

    /**
     * Call a ThreatExchange or Graph API endpoint via POST.
     *
     * @param string $endpoint
     * @param array  $options
     *
     * @return stdClass
     */
    public function post($endpoint, $options = [])
    {
        return $this->call($endpoint, 'POST', $options);
    }

    /**
     * Call a ThreatExchange or Graph API endpoint.
     *
     * @param string $endpoint
     * @param string $method
     * @param array  $options
     *
     * @return stdClass
     */
    public function call($endpoint, $method, $options = [])
    {
        return json_decode($this->guzzle->{$method}($endpoint, [
            (strtoupper($method) == 'GET' ? 'query' : 'form_params') => array_replace_recursive($this->authParams(), $options),
        ])->getBody());
    }

    /**
     * Generate the authentication parameters to merge with the rest of the request.
     *
     * @return array
     */
    protected function authParams()
    {
        return [
            'access_token' => $this->appId.'|'.$this->appSecret,
        ];
    }
}
