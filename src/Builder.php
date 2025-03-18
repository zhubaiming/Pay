<?php

namespace Zhubaiming\Pay;

class Builder
{
    private array $params = [];

    /**
     * 荷载
     * @var array
     */
    private $payload = [];

    private $radar; // 雷达

    private $direction = false; // 方向、指导、指示、方位、走向、取向、针对性

    private $destination = null; // 目的地、重点、去处、终点
    private $destinationOrigin = null;


    public function setPayload(array $payload)
    {
        $this->payload = $payload;

        return $this;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function mergePayload(array $payload)
    {
        $this->payload = array_merge($this->payload, $payload);

        return $this;
    }

    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setRadar($radar)
    {
        $this->radar = $radar;

        return $this;
    }

    public function getRadar()
    {
        return $this->radar;
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function setDestinationOrigin($destinationOrigin)
    {
        $this->destinationOrigin = $destinationOrigin;

        return $this;
    }

    public function getDestinationOrigin()
    {
        return $this->destinationOrigin;
    }
}