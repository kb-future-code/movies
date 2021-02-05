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

        $this->assertFalse($paginator->isShowFirst());
        $this->assertTrue($paginator->isShowNext());
        $this->assertFalse($paginator->isShowPrevious());
        $this->assertEquals(1, $paginator->getStart());
        $this->assertEquals(6, $paginator->getEnd());

        $customPaginatorManager->setPage(4);
        $paginator = $customPaginatorManager->getPaginator();

        $this->assertTrue($paginator->isShowFirst());
        $this->assertTrue($paginator->isShowNext());
        $this->assertTrue($paginator->isShowPrevious());
        $this->assertEquals(2, $paginator->getStart());
        $this->assertEquals(6, $paginator->getEnd());

        $customPaginatorManager->setPage(6);
        $paginator = $customPaginatorManager->getPaginator();

        $this->assertTrue($paginator->isShowFirst());
        $this->assertFalse($paginator->isShowNext());
        $this->assertTrue($paginator->isShowPrevious());
        $this->assertEquals(4, $paginator->getStart());
        $this->assertEquals(6, $paginator->getEnd());
    }
}
