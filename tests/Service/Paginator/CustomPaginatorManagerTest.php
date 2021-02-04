<?php


namespace App\Tests\Service\Paginator;

use App\Service\Paginator\CustomPaginatorManager;
use App\Test\BaseWebTestCase;

class CustomPaginatorManagerTest extends BaseWebTestCase
{
    public function testPaginator()
    {
        $customPaginatorManager = new CustomPaginatorManager();

        $customPaginatorManager->setTotalResults(59);
        $customPaginatorManager->setPage(1);
        $customPaginatorManager->setElementsPerPage(10);

        $paginator = $customPaginatorManager->getPaginator();

        $this->assertEquals(false, $paginator->isShowFirst());
        $this->assertEquals(true, $paginator->isShowNext());
        $this->assertEquals(false, $paginator->isShowPrevious());
        $this->assertEquals(1, $paginator->getStart());
        $this->assertEquals(6, $paginator->getEnd());

        $customPaginatorManager->setPage(4);
        $paginator = $customPaginatorManager->getPaginator();

        $this->assertEquals(true, $paginator->isShowFirst());
        $this->assertEquals(true, $paginator->isShowNext());
        $this->assertEquals(true, $paginator->isShowPrevious());
        $this->assertEquals(2, $paginator->getStart());
        $this->assertEquals(6, $paginator->getEnd());

        $customPaginatorManager->setPage(6);
        $paginator = $customPaginatorManager->getPaginator();

        $this->assertEquals(true, $paginator->isShowFirst());
        $this->assertEquals(false, $paginator->isShowNext());
        $this->assertEquals(true, $paginator->isShowPrevious());
        $this->assertEquals(4, $paginator->getStart());
        $this->assertEquals(6, $paginator->getEnd());
    }
}
