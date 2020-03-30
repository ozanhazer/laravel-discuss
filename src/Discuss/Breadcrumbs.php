<?php


namespace Alfatron\Discuss\Discuss;


use Countable;
use Illuminate\Support\Facades\Route;
use IteratorAggregate;

class Breadcrumbs implements IteratorAggregate, Countable
{

    protected $breadcrumbs = [];

    public function __construct()
    {

        if (Route::is('discuss.category')) {

            $this->addRootItem();
            $this->appendBreadcrumb(Route::current()->parameter('selectedCategory')->name);

        } elseif (Route::is('discuss.detail')) {

            $category = Route::current()->parameter('category');

            $this->addRootItem();
            $this->appendBreadcrumb($category->name, route('discuss.category', $category));
            $this->appendBreadcrumb(Route::current()->parameter('thread')->title);

        } elseif (Route::is('discuss.my-participation')) {

            $this->addRootItem();
            $this->appendBreadcrumb('My Participation', route('discuss.my-participation'));

        }

    }

    protected function appendBreadcrumb($title, $url = null)
    {
        $this->breadcrumbs[] = (object)compact('url', 'title');
    }

    protected function addRootItem()
    {
        array_unshift($this->breadcrumbs, (object)[
            'title' => __('Forum'),
            'url'   => route('discuss.index'),
        ]);
    }

    public function getIterator()
    {
        foreach ($this->breadcrumbs as $breadcrumb) {
            yield $breadcrumb;
        }
    }

    public function count()
    {
        return count($this->breadcrumbs);
    }
}
