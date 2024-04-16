<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Gate::define('check-if-assignee-has-user-role', function (User $user, $userId) {
            return User::where('id', $userId)->first()->hasRole('user');
        });

        Gate::define('check-if-dependencies-are-not-completed', function (User $user, $task){
            $dependenciesStatuses= Task::whereIn('id',Json_decode($task['dependencies']))->pluck('status')->toArray();
            if(in_array('pending', $dependenciesStatuses) || in_array('canceled', $dependenciesStatuses)){
                return false;
            }
            return true;
        });
       
        Gate::define('check-if-user-assigned-to-task', function(User $user, $task){
            if($task->user_id != $user->id){
                return false;
            }
            return true;
        });
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
