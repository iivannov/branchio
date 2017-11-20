<?php

namespace Iivannov\Branchio;


class Link
{

    /**
     * @var string
     */
    public $alias;

    /**
     * @var int
     */
    public $type = 0;

    /**
     * @var string
     */
    public $feature;

    /**
     * @var string
     */
    public $channel;

    /**
     * @var string
     */
    public $campaign;

    /**
     * @var \stdClass
     */
    public $data;


    public function __construct($json = null)
    {
        if ($json) {
            if (!$json instanceof \stdClass) {
                throw new \InvalidArgumentException();
            }
            $this->makeFromLinkObject($json);
        }
    }

    /**
     * @param string $alias
     * @return Link
     */
    public function setAlias(string $alias): Link
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @param int $type
     * @return Link
     */
    public function setType(int $type): Link
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $feature
     * @return Link
     */
    public function setFeature(string $feature): Link
    {
        $this->feature = $feature;
        return $this;
    }

    /**
     * @param string $channel
     * @return Link
     */
    public function setChannel(string $channel): Link
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @param string $campaign
     * @return Link
     */
    public function setCampaign(string $campaign): Link
    {
        $this->campaign = $campaign;
        return $this;
    }

    /**
     * @param array $data
     * @return Link
     */
    public function setData(array $data): Link
    {
        $this->data = (object)$data;
        return $this;
    }


    private function makeFromLinkObject(\stdClass $json)
    {
        if (isset($json->alias)) {
            $this->alias = $json->alias;
        }

        if (isset($json->type)) {
            $this->type = $json->type;
        }

        if (isset($json->feature)) {
            $this->feature = $json->feature;
        }

        if (isset($json->channel)) {
            $this->channel = $json->channel;
        }

        if (isset($json->campaign)) {
            $this->campaign = $json->campaign;
        }

        if (isset($json->data)) {
            $this->data = $json->data;
        }
    }

}