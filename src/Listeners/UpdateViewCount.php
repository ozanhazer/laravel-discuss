<?php

namespace Alfatron\Discuss\Listeners;

use Alfatron\Discuss\Discuss\UniqueChecker\UniqueChecker;
use Alfatron\Discuss\Events\ThreadVisited;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class UpdateViewCount
{
    /**
     * @var UniqueChecker
     */
    protected $uniqueChecker;

    /**
     * @param UniqueChecker $uniqueChecker
     */
    public function __construct(UniqueChecker $uniqueChecker)
    {
        $this->uniqueChecker = $uniqueChecker;
    }

    public function handle(ThreadVisited $event)
    {
        // Ignore crawlers
        if ((new CrawlerDetect())->isCrawler()) {
            return;
        }

        // Respect privacy concerns
        if (config('discuss.view_count.honor_dnt') && request()->header('dnt')) {
            return;
        }

        // Unique check
        if ($this->uniqueChecker->keyExists($event->thread->id)) {
            return;
        }

        $event->thread->increment('view_count');
    }
}
