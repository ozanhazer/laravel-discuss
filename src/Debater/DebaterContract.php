<?php


namespace Alfatron\Discussions\Debater;


interface DebaterContract
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Alfatron\Discussions\Models\Thread[]
     */
    public function createdThreads();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Alfatron\Discussions\Models\Thread[]
     */
    public function participatedThreads();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Alfatron\Discussions\Models\Thread[]
     */
    public function followedThreads();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Alfatron\Discussions\Models\Thread[]
     */
    public function discussionPosts();
}
