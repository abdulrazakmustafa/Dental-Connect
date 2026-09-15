<?php

namespace App\Modules\TrustSupport\Policies;

use App\Models\User;
use App\Modules\TrustSupport\Models\Review;

class ReviewPolicy
{
    /** A patient may only review their own completed appointment, once. */
    public function create(User $user, ?Review $review = null): bool
    {
        return $user->hasRole('patient');
    }

    public function moderate(User $user): bool
    {
        return $user->can('reviews.moderate');
    }
}
