<?php

namespace Alfatron\Discuss\Listeners;

use Alfatron\Discuss\Events\ThreadVisited;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class UpdateViewCount
{
    public function handle(ThreadVisited $event)
    {
        // Ignore crawlers
        if((new CrawlerDetect())->isCrawler()) {
            return;
        }

        // Respect privacy concerns
        if (config('discuss.honor_dnt') && request()->header('dnt')) {
            return;
        }

        $event->thread->increment('view_count');
    }
}
