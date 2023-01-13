<?php

declare(strict_types=1);

namespace Zimk\stylingcockpit\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Test case
 *
 * @author Daniel Kuhn
 */
class AjaxControllerTest extends UnitTestCase
{
    /**
     * @var \Zimk\stylingcockpit\Controller\AjaxController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\Zimk\stylingcockpit\Controller\AjaxController::class))
            ->onlyMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllAjaxesFromRepositoryAndAssignsThemToView(): void
    {
        $allAjaxes = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $ajaxRepository = $this->getMockBuilder(\Zimk\StylingCockpit\Domain\Repository\TestRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $ajaxRepository->expects(self::once())->method('findAll')->will(self::returnValue($allAjaxes));
        $this->subject->_set('ajaxRepository', $ajaxRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('ajaxes', $allAjaxes);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenAjaxToView(): void
    {
        $ajax = new \Zimk\stylingcockpit\Domain\Model\Ajax();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('ajax', $ajax);

        $this->subject->showAction($ajax);
    }
}
