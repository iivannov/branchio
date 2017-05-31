<?php

namespace Iivannov\Branchio;

use GuzzleHttp\Exception\ClientException;
use Iivannov\Branchio\Exceptions\BranchioDuplicateLinkException;
use Iivannov\Branchio\Exceptions\BranchioException;

class Client
{

    /**
     * The API key for Branch.io
     * @var string
     */
    protected $key;

    /**
     * The account secret
     * @var string
     */
    protected $secret;

    /**
     * The campaign name
     * @var string
     */
    protected $campaign;

    /**
     * The channel name
     * @var string
     */
    protected $channel;

    /**
     * The http client used for communication
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $http;


    const API_URL = 'https://api.branch.io/v1/';

    /**
     * Client constructor.
     * @param string $key
     */
    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;

        $this->http = new \GuzzleHttp\Client();
    }

    /**
     * @param string $campaign
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @param string $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }


    /**
     * Create a single link
     *
     * For detailed information about different options:
     * @see https://github.com/BranchMetrics/branch-deep-linking-public-api#creating-a-deep-linking-url
     * @see https://dev.branch.io/getting-started/configuring-links/guide/#redirect-customization
     *
     * @param null $data optional :
     *      The dictionary to embed with the link.
     *
     * @param null $alias optional (max 128 characters) :
     *      Instead of our standard encoded short url, you can specify the alias of the link bnc.lt/alexaustin.
     *      Aliases are enforced to be unique per domain (bnc.lt, yourapp.com, etc).
     *      Be careful, link aliases are unique, immutable objects that cannot be deleted.
     *
     * @param null $type optional
     *      - Set type to 1, to make the URL a one-time use URL. It won't deep link after 1 successful deep link.
     *      - Set type to 2 to make a Marketing URL. These are URLs that are displayed under the Marketing tab on the dashboard
     *      - Default is set to 0, which is the standard Branch links created via our SDK.git
     *
     *
     * @throws \Exception
     */
    public function createLink($data = null, $alias = null, $type = null)
    {
        $payload = [
            'branch_key' => $this->key,
        ];


        if ($this->campaign) {
            $payload['campaign'] = $this->campaign;
        }

        if ($this->channel) {
            $payload['channel'] = $this->channel;
        }

        if ($data) {
            $payload['data'] = $data;
        }

        if ($alias) {
            $payload['alias'] = $alias;
        }

        if ($type) {
            $payload['type'] = $type;
        }

        try {
            $response = $this->http->post(self::API_URL . 'url', ['json' => $payload]);
        } catch (ClientException $ex) {
            if ($ex->getCode() == 409) {
                throw new BranchioDuplicateLinkException();
            }

            throw new BranchioException('Unhandled Bad Response', 0, $ex);
        }

        $result = json_decode($response->getBody()->getContents());

        if (!isset($result->url)) {
            throw new BranchioException('No URL returned');
        }

        return $result->url;
    }


    public function updateLink($url, $data = null, $type = null)
    {
        $payload = [
            'branch_key' => $this->key,
            'branch_secret' => $this->secret,
        ];

        if ($this->campaign) {
            $payload['campaign'] = $this->campaign;
        }

        if ($this->channel) {
            $payload['channel'] = $this->channel;
        }

        if ($data) {
            $payload['data'] = $data;
        }

        if ($type) {
            $payload['type'] = $type;
        }

        $url = urlencode($url);

        try {
            $this->http->put(self::API_URL . "url?url={$url}", ['json' => $payload]);
        } catch (ClientException $ex) {
            throw new BranchioException('Unhandled Bad Response', 0, $ex);
        }

        return true;
    }


}