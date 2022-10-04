<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\Builder;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro('whereLike', function(string $attribute, string $keyword, int $position = 0) {
            $keyword = addcslashes($keyword, '\_%');
            $condition = [
                1 => "{$keyword}%",
                -1 => "%{$keyword}",
            ][$position] ?? "%{$keyword}%";
    
            return $this->where($attribute, 'LIKE', $condition);
        });
    }
}
