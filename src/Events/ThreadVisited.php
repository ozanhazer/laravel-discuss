<?php

namespace Alfatron\Discuss\Events;

use Alfatron\Discuss\Models\Thread;
use Illuminate\Queue\SerializesModels;

class ThreadVisited
{
    use SerializesModels;

    public $thread;

    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }
}
