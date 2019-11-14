<?php

/*
 * This file is part of the Ivory Ordered Form package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\OrderedForm;

use Ivory\OrderedForm\Builder\OrderedFormBuilder;
use Ivory\OrderedForm\OrderedResolvedFormType;
use Ivory\OrderedForm\Orderer\FormOrderer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class OrderedResolvedFormTypeTest extends TestCase
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * @var OrderedResolvedFormType
     */
    private $type;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->factory = $this->createMock(FormFactoryInterface::class);

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $this->createMockFormType(),
            [],
            new OrderedResolvedFormType(new FormOrderer(), $this->createMockFormType())
        );
    }

    public function testCreateBuilderWithButtonInnerType()
    {
        /** @var ButtonType $innerType */
        $innerType = $this->createMock(ButtonType::class);

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $innerType,
            [],
            new OrderedResolvedFormType(new FormOrderer(), $innerType)
        );

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Builder\OrderedButtonBuilder',
            $this->type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    public function testCreateBuilderWithSubmitButtonInnerType()
    {
        /** @var SubmitType $innerType */
        $innerType = $this->createMock(SubmitType::class);

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $innerType,
            [],
            new OrderedResolvedFormType(new FormOrderer(), $innerType)
        );

        $this->assertInstanceOf(
            'Ivory\OrderedForm\Builder\OrderedSubmitButtonBuilder',
            $this->type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    public function testCreateBuilderWithFormInnerType()
    {
        $innerType = $this->createMockFormType();

        $this->type = new OrderedResolvedFormType(
            new FormOrderer(),
            $innerType,
            [],
            new OrderedResolvedFormType(new FormOrderer(), $innerType)
        );

        $this->assertInstanceOf(
            OrderedFormBuilder::class,
            $this->type->createBuilder($this->createMockFormFactory(), 'name')
        );
    }

    /**
     * @return AbstractType|MockObject
     */
    private function createMockFormType()
    {
        return $this->createMock(AbstractType::class);
    }

    /**
     * @return FormFactoryInterface|MockObject
     */
    private function createMockFormFactory()
    {
        return $this->createMock(FormFactoryInterface::class);
    }
}
