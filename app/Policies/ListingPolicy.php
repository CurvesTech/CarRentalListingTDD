<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Listing;

class ListingPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function edit(User $user, Listing $listing): bool {
        return $user->id == $listing->user_id;
    }

    public function delete(User $user, Listing $listing) : bool {
        return $user->id == $listing->user_id;
    }
}
