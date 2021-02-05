<?php

namespace App\Service\OmdbApi;

use Unirest\Request;
use Unirest\Response;

/**
 * Movie manager handles connection and sending request to omdbApi.
 *
 * Class MovieManager
 * @package App\Service\OmdbApi
 */
class MovieManager extends ApiManager
{
    /**
     * Search for movies by title, year, type and page.
     *
     * @param string $title
     * @param int|null $year
     * @param string|null $type
     * @param int|null $page
     * @return Response
     */
    public function search(string $title, ?int $year, ?string $type, ?int $page): Response
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

    /**
     * Search for movie by imdbID.
     *
     * @param string $id
     * @return Response
     */
    public function getById(string $id): Response
    {
        $query = ['i' => $id];

        return Request::get($this->getFullUrl(), [], $query);
    }
}
