<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-10-10 16:59
 */
namespace Notadd\Content\Abstracts;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Notadd\Content\Events\RegisterCategoryTemplate as RegisterTemplateEvent;
use Notadd\Content\Managers\CategoryManager;
use Notadd\Foundation\Abstracts\EventSubscriber;
/**
 * Class AbstractCategoryTemplateRegistrar
 * @package Notadd\Content\Abstracts
 */
abstract class CategoryTemplateRegistrar extends EventSubscriber {
    /**
     * @var \Notadd\Content\Managers\CategoryManager
     */
    protected $manager;
    /**
     * AbstractCategoryTemplateRegistrar constructor.
     * @param \Illuminate\Container\Container $container
     * @param \Illuminate\Events\Dispatcher $events
     * @param \Notadd\Content\Managers\CategoryManager $manager
     */
    public function __construct(Container $container, Dispatcher $events, CategoryManager $manager) {
        parent::__construct($container, $events);
        $this->manager = $manager;
    }
    /**
     * @return string
     */
    protected function getEvent() {
        return RegisterTemplateEvent::class;
    }
    /**
     * @return void
     */
    abstract public function handle();
}