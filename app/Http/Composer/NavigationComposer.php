<?php

namespace App\Http\View\Composers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavigationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Default to 0 if the user is not logged in
        $unreadMessageCount = 0;

        // Check if a user is authenticated
        if (Auth::check()) {
            // Perform an efficient query to count unread messages for the logged-in user
            $unreadMessageCount = Message::where('receiver_id', Auth::id())
                                         ->where('is_read', false)
                                         ->count();
        }

        // Share the result with the view the composer is attached to
        $view->with('unreadMessageCount', $unreadMessageCount);
    }
}