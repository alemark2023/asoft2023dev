<?php

namespace Modules\LevelAccess\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\LevelAccess\Traits\SystemActivityTrait;


class UserEventSubscriber
{

    use SerializesModels, SystemActivityTrait;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * Handle user login event.
     */
    public function onUserLogin($event) 
    {
        $this->saveSystemActivityUser($event, 'login');
    }

 
    /**
     * Handle user logout event.
     */
    public function onUserLogout($event) 
    {
        $this->saveSystemActivityUser($event, 'logout');
    }

    
    /**
     * Handle user failed login event.
     */
    public function onUserFailed($event) 
    {
        $this->saveSystemActivityUser($event, 'failed');
    }

 
    public function onUserLockout($event) 
    {
        \Log::info("onUserLockout");
        // $this->saveSystemActivityUser($event, 'failed');
    }
    


    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'Modules\LevelAccess\Events\UserEventSubscriber@onUserLogin'
        );
 
        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'Modules\LevelAccess\Events\UserEventSubscriber@onUserLogout'
        );
        
        $events->listen(
            'Illuminate\Auth\Events\Failed',
            'Modules\LevelAccess\Events\UserEventSubscriber@onUserFailed'
        );
        
        $events->listen(
            'Illuminate\Auth\Events\Lockout',
            'Modules\LevelAccess\Events\UserEventSubscriber@onUserLockout'
        );
    }

}
