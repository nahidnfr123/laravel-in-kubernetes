<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public string $cacheKey = 'users';
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    public function saving(User $user): void
    {
        cache()->forget($this->cacheKey);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        cache()->forget($this->cacheKey);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
