<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-11-25 15:23
 */
namespace Notadd\Content\Handlers\Finders;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;
use Notadd\Content\Models\PageType;
use Notadd\Foundation\Passport\Abstracts\DataHandler;

/**
 * Class PageTypeFindHandler.
 */
class PageTypeFinderHandler extends DataHandler
{
    /**
     * @var \Notadd\Content\Models\PageType
     */
    protected $pageType;

    /**
     * PageTypeFinderHandler constructor.
     *
     * @param \Illuminate\Container\Container    $container
     * @param \Notadd\Content\Models\PageType    $pageType
     * @param \Illuminate\Http\Request           $request
     * @param \Illuminate\Translation\Translator $translator
     */
    public function __construct(
        Container $container,
        PageType $pageType,
        Request $request,
        Translator $translator
    ) {
        parent::__construct($container, $request, $translator);
        $this->pageType = $pageType;
    }

    /**
     * @return int
     */
    public function code()
    {
        return 200;
    }

    /**
     * @return array
     */
    public function data()
    {
        $pageType = $this->pageType->newQuery()->find($this->request->input('id'));

        return $pageType->getAttributes();
    }

    /**
     * @return array
     */
    public function errors()
    {
        return [
            $this->translator->trans(''),
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            $this->translator->trans(''),
        ];
    }
}
