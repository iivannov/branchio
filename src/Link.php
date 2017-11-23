<?php

namespace Iivannov\Branchio;


class Link
{

    /**
     * @var string
     */
    public $channel;


    /**
     * @var string
     */
    public $feature;

    /**
     * @var string
     */
    public $campaign;

    /**
     * @var string
     */
    public $stage;

    /**
     * @var array
     */
    public $tags;

    /**
     * @var string
     */
    public $alias;

    /**
     * @var int
     */
    public $type = 0;

    /**
     * @var array
     */
    public $data;


    private $properties = [
        'channel',
        'feature',
        'campaign',
        'stage',
        'tags',
        'alias',
        'type',
        'data'
    ];


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
     * @param string $channel
     * @return Link
     */
    public function setChannel(string $channel): Link
    {
        $this->channel = $channel;
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
     * @param string $campaign
     * @return Link
     */
    public function setCampaign(string $campaign): Link
    {
        $this->campaign = $campaign;
        return $this;
    }

    /**
     * @param string $stage
     * @return Link
     */
    public function setStage(string $stage): Link
    {
        $this->stage = $stage;
        return $this;
    }

    public function setTags(array $tags): Link
    {
        $this->tags = $tags;
        return $this;
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
     * @param array $data
     * @return Link
     */
    public function setData(array $data): Link
    {
        $this->data = (object)$data;
        return $this;
    }

    public function toArray($ignore = [])
    {
        $array = [];

        foreach ($this->properties as $property) {
            if(in_array($property, $ignore)) {
                continue;
            }
            $array[$property] = $this->{$property};
        }

        return $array;
    }

    private function makeFromLinkObject(\stdClass $json)
    {
        foreach ($this->properties as $property) {
            if (isset($json->{$property})) {
                $this->{$property} = $json->{$property};
            }
        }
    }

}