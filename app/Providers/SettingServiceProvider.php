<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Corcel\Model\Option;


class SettingServiceProvider extends ServiceProvider
{
	protected $defer = false;
	
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $options= Option::where('autoload','yes')->pluck('option_value','option_name')->toArray();
        if(count($options)>0){
            \Config::set('setting',$options);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    	
    }
    
  
    
}

