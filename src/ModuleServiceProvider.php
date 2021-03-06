<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-10-08 17:12
 */
namespace Notadd\Content;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Notadd\Content\Events\RegisterArticleTemplate;
use Notadd\Content\Events\RegisterArticleType;
use Notadd\Content\Events\RegisterCategoryTemplate;
use Notadd\Content\Events\RegisterCategoryType;
use Notadd\Content\Events\RegisterPageTemplate;
use Notadd\Content\Events\RegisterPageType;
use Notadd\Content\Listeners\CsrfTokenRegister;
use Notadd\Content\Listeners\RouteRegister;
use Notadd\Content\Managers\ArticleManager;
use Notadd\Content\Managers\CategoryManager;
use Notadd\Content\Managers\PageManager;
use Notadd\Content\Models\Article;
use Notadd\Content\Models\ArticleDraft;
use Notadd\Content\Observers\ArticleObserver;
use Notadd\Content\Observers\DraftObserver;

/**
 * Class Module.
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Boot service provider.
     */
    public function boot()
    {
        Article::observe(ArticleObserver::class);
        ArticleDraft::observe(DraftObserver::class);
        $this->app->make(Dispatcher::class)->subscribe(CsrfTokenRegister::class);
        $this->app->make(Dispatcher::class)->subscribe(RouteRegister::class);
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../databases/migrations'));
        $this->loadTranslationsFrom(realpath(__DIR__ . '/../resources/translations'), 'content');
        $this->loadViewsFrom(realpath(__DIR__ . '/../resources/views'), 'content');
        $this->publishes([
            realpath(__DIR__ . '/../resources/mixes/administration/dist/assets/content/administration') => public_path('assets/content/administration'),
            realpath(__DIR__ . '/../resources/mixes/foreground/dist/assets/content/foreground') => public_path('assets/content/foreground'),
        ], 'public');
    }

    /**
     * Register services.
     */
    public function register()
    {
        $this->app->alias('article.manager', ArticleManager::class);
        $this->app->alias('category.manager', CategoryManager::class);
        $this->app->alias('page.manager', PageManager::class);
        $this->app->singleton('article.manager', function ($app) {
            $manager = new ArticleManager($app, $app['events']);
            $this->app->make(Dispatcher::class)->fire(new RegisterArticleTemplate($app, $manager));
            $this->app->make(Dispatcher::class)->fire(new RegisterArticleType($app, $manager));

            return $manager;
        });
        $this->app->singleton('category.manager', function ($app) {
            $manager = new CategoryManager($app, $app['events']);
            $this->app->make(Dispatcher::class)->fire(new RegisterCategoryTemplate($app, $manager));
            $this->app->make(Dispatcher::class)->fire(new RegisterCategoryType($app, $manager));

            return $manager;
        });
        $this->app->singleton('page.manager', function ($app) {
            $manager = new PageManager($app, $app['events']);
            $this->app->make(Dispatcher::class)->fire(new RegisterPageTemplate($app, $manager));
            $this->app->make(Dispatcher::class)->fire(new RegisterPageType($app, $manager));

            return $manager;
        });
    }

    /**
     * Get script of extension.
     *
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function script()
    {
        return asset('assets/content/administration/js/module.js');
    }

    /**
     * Get stylesheet of extension.
     *
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function stylesheet()
    {
        return [
            asset('assets/content/administration/css/module.css'),
        ];
    }
}
