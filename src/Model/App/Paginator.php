<?php


namespace App\Model\App;

/**
 * Class Paginator helps paginate elemeents on pages.
 *
 * @package App\Model\App
 */
class Paginator
{
    private ?int $start = null;
    private ?int $end = null;
    private bool $showPrevious = false;
    private bool $showNext = false;
    private bool $showFirst = false;

    public function getStart(): ?int
    {
        return $this->start;
    }

    public function setStart(?int $start): Paginator
    {
        $this->start = $start;
        return $this;
    }

    public function getEnd(): ?int
    {
        return $this->end;
    }

    public function setEnd(?int $end): Paginator
    {
        $this->end = $end;
        return $this;
    }

    public function isShowPrevious(): bool
    {
        return $this->showPrevious;
    }

    public function setShowPrevious(bool $showPrevious): Paginator
    {
        $this->showPrevious = $showPrevious;
        return $this;
    }

    public function isShowNext(): bool
    {
        return $this->showNext;
    }

    public function setShowNext(bool $showNext): Paginator
    {
        $this->showNext = $showNext;
        return $this;
    }

    public function isShowFirst(): bool
    {
        return $this->showFirst;
    }

    public function setShowFirst(bool $showFirst): Paginator
    {
        $this->showFirst = $showFirst;
        return $this;
    }
}