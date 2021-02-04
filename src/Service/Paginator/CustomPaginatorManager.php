<?php


namespace App\Service\Paginator;

use App\Model\App\Paginator;

class CustomPaginatorManager
{
    private int $elementsPerPage = 10;
    private ?int $page = null;
    private ?int $totalResults = null;

    public function getPaginator(): ?Paginator
    {
        if (!$this->page or !$this->totalResults) {
            return null;
        }

        $paginator = new Paginator();

        switch ($this->page) {
            case 1:
                $paginator
                    ->setStart(1)
                    ->setShowPrevious(false)
                    ->setShowFirst(false)
                ;
                break;
            case 2:
            case 3:
                $paginator
                    ->setStart(1)
                    ->setShowPrevious(true)
                    ->setShowFirst(false)
                ;
                break;
            default:
                $paginator
                    ->setStart($this->page-2)
                    ->setShowPrevious(true)
                    ->setShowFirst(true)
                ;
                break;
        }

        if ($paginator->getStart()+$this->elementsPerPage <= ($this->totalResults/$this->elementsPerPage)+1) {
            $paginator->setEnd($paginator->getStart() + $this->elementsPerPage);
        } else {
            $paginator->setEnd(($this->totalResults/$this->elementsPerPage));
            if ($this->totalResults % $this->elementsPerPage > 0) {
                $paginator->setEnd($paginator->getEnd()+1);
            }
        }

        $paginator->setShowNext((bool) ($this->page < $paginator->getEnd()));

        return $paginator;
    }

    public function getElementsPerPage(): int
    {
        return $this->elementsPerPage;
    }

    public function setElementsPerPage(int $elementsPerPage): void
    {
        $this->elementsPerPage = $elementsPerPage;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getTotalResults(): ?int
    {
        return $this->totalResults;
    }

    public function setTotalResults(int $totatResults): void
    {
        $this->totalResults = $totatResults;
    }
}