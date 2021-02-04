<?php

namespace App\Service\OmdbApi;

abstract class ApiManager
{
    private string $url;
    private string $apiKey;

    public function __construct(string $url, string $apiKey)
    {
        $this->url = $url;
        $this->apiKey = $apiKey;
    }

    protected function getFullUrl(): string
    {
        return $this->url.$this->apiKey;
    }
}
