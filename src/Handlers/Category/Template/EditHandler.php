<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-11-25 15:16
 */
namespace Notadd\Content\Handlers\Category\Template;

use Illuminate\Container\Container;
use Notadd\Content\Models\CategoryTemplate;
use Notadd\Foundation\Passport\Abstracts\SetHandler;

/**
 * Class EditHandler.
 */
class EditHandler extends SetHandler
{
    /**
     * EditHandler constructor.
     *
     * @param \Notadd\Content\Models\CategoryTemplate $categoryTemplate
     * @param \Illuminate\Container\Container         $container
     */
    public function __construct(
        CategoryTemplate $categoryTemplate,
        Container $container
    ) {
        parent::__construct($container);
        $this->model = $categoryTemplate;
    }

    /**
     * Http code.
     *
     * @return int
     */
    public function code()
    {
        return 200;
    }

    /**
     * Errors for handler.
     *
     * @return array
     */
    public function errors()
    {
        return [
            $this->translator->trans('content::category_template.update.fail'),
        ];
    }

    /**
     * Execute Handler.
     *
     * @return bool
     */
    public function execute()
    {
        $categoryTemplate = $this->model->newQuery()->find($this->request->input('id'));
        if ($categoryTemplate === null) {
            return false;
        }
        $categoryTemplate->update($this->request->all());

        return true;
    }

    /**
     * Messages for handler.
     *
     * @return array
     */
    public function messages()
    {
        return [
            $this->translator->trans('content::category_template.update.success'),
        ];
    }
}
