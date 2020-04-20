<?php

namespace Alfatron\Discuss\Discuss\UniqueChecker;

use Jaybizzle\CrawlerDetect\Fixtures\Headers;

class UniqueChecker
{
    /**
     * @var UniqueCheckerStorage
     */
    protected $storage;

    public function __construct(UniqueCheckerStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Checks if the key is unique and returns true if the
     * key is found and not expired.
     *
     * @param $threadId
     *
     * @return bool
     */
    public function keyExists($threadId)
    {
        // Create a unique key to check. Use md5 to limit the length of the key and
        // to add an additional layer for security since user agent is not a safe input.
        $uniqueKey = $threadId . ':' . md5(request()->ip() . $this->getUserAgent());

        // Remove the expired keys before lookup
        $this->storage->removeExpired();

        // Check if exists
        $found = $this->storage->check($uniqueKey);

        // Create or update record
        $this->storage->touch($uniqueKey);

        return $found;
    }

    /**
     * Guesses the user agent from http headers.
     *
     * @return string
     */
    protected function getUserAgent()
    {
        $headers = new Headers();

        $userAgent = '';
        foreach ($headers->getAll() as $altHeader) {
            if ($ua = request()->server($altHeader)) {
                $userAgent .= $ua;
            }
        }

        return $userAgent;
    }
}
