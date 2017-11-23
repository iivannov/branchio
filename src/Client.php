<?php

namespace Iivannov\Branchio;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Iivannov\Branchio\Exceptions\BranchioDuplicateLinkException;
use Iivannov\Branchio\Exceptions\BranchioException;
use Iivannov\Branchio\Exceptions\BranchioForbiddenException;
use Iivannov\Branchio\Exceptions\BranchioNotFoundException;

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
    public function __construct($key, $secret, ClientInterface $client = null)
    {
        $this->key = $key;
        $this->secret = $secret;

        $this->http = $client ?? new \GuzzleHttp\Client();
    }


    /**
     * Retrieve data from existing link
     *
     * @param $url
     * @return Link
     * @throws \Throwable
     */
    public function getLink($url)
    {
        try {
            $response = $this->http->get(self::API_URL . "url", ['query' => [
                'url' => $url,
                'branch_key' => $this->key
            ]]);
        } catch (ClientException $ex) {
            throw $this->translateClientException($ex);
        }

        $result = json_decode($response->getBody()->getContents());
        return new Link($result);
    }

    /**
     * Create a single link
     *
     * @param Link $link
     * @return mixed
     * @throws \Throwable
     */
    public function createLink(Link $link)
    {

        $payload = array_merge($link->toArray(), [
            'branch_key' => $this->key
        ]);

        try {
            $response = $this->http->post(self::API_URL . 'url', ['json' => $payload]);
        } catch (ClientException $ex) {
            throw $this->translateClientException($ex);
        }

        $result = json_decode($response->getBody()->getContents());

        if (!isset($result->url)) {
            throw new BranchioException('No URL returned');
        }

        return $result->url;
    }


    public function updateLink($url, Link $link)
    {
        // Not all parameters of a link can be updated
        // The type and the alias can't be updated and thus are ignored
        $payload = array_merge($link->toArray(['type', 'alias']), [
            'branch_key' => $this->key,
            'branch_secret' => $this->secret,
        ]);

        $url = urlencode($url);

        try {
            $this->http->put(self::API_URL . "url?url={$url}", ['json' => $payload]);
        } catch (ClientException $ex) {
            throw $this->translateClientException($ex);
        }

        return true;
    }

    private function translateClientException(\Throwable $ex): \Throwable
    {
        if ($ex->getCode() == 403) {
            return new BranchioForbiddenException();
        }
        if ($ex->getCode() == 404) {
            return new BranchioNotFoundException();
        }
        if ($ex->getCode() == 409) {
            return new BranchioDuplicateLinkException();
        }
        return BranchioException::makeFromResponse($ex);
    }
}