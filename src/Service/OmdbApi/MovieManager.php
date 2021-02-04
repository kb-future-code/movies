<?php

namespace App\Service\OmdbApi;

use Unirest\Request;
use Unirest\Response;

class MovieManager extends ApiManager
{
    public function search(?string $title, ?int $year, ?string $type, ?int $page): Response
    {
        $query = [];
        if ($title) {
            $query['s'] = $title;
        }
        if ($year) {
            $query['y'] = $year;
        }
        if ($page) {
            $query['page'] = $page;
        }
        if ($type) {
            $query['type'] = $type;
        }

        return Request::get($this->getFullUrl(), [], $query);
    }

    public function getById(string $id): Response
    {
        $query = ['i' => $id];

        return Request::get($this->getFullUrl(), [], $query);
    }
}
