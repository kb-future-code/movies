<?php


namespace App\Model\Movie;

class MovieSearch
{
    const TYPE_MOVIE = 'movie';
    const TYPE_SERIES = 'series';
    const TYPE_EPISODES = 'episode';

    private ?string $title = null;
    private ?int $year = null;
    private ?string $type = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): MovieSearch
    {
        $this->title = $title;
        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): MovieSearch
    {
        $this->year = $year;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): MovieSearch
    {
        $this->type = $type;
        return $this;
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_MOVIE => self::TYPE_MOVIE,
            self::TYPE_SERIES => self::TYPE_SERIES,
            self::TYPE_EPISODES => self::TYPE_EPISODES,
        ];
    }
}