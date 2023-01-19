<?php

//slug generator
if(!function_exists('link_rewrite')){
    function link_rewrite($table,$primaryKey='id',$name,$id=0,$field='slug'){
        $slug= \Str::slug($name,'-');
        $data=\DB::table($table)->where($field, 'like', $slug.'%')
        ->where($primaryKey, '<>', $id)
        ->get();
        if (! $data->contains('slug', $slug)){
            return $slug;
        }
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $data->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        throw new \Exception('Can not create a unique slug');
    }
}
//credit https://en.gravatar.com/site/implement/images/php/
/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source https://gravatar.com/site/implement/images/php/
 */
if(!function_exists('get_gravatar')){ 
    function get_gravatar( $email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array() ) {
    	$url = 'https://www.gravatar.com/avatar/';
    	$url .= md5( strtolower( trim( $email ) ) );
    	$url .= "?s=$s&d=$d&r=$r";
    	if ( $img ) {
    		$url = '<img src="' . $url . '"';
    		foreach ( $atts as $key => $val )
    			$url .= ' ' . $key . '="' . $val . '"';
    		$url .= ' />';
    	}
    	return $url;
    }
}
if(!function_exists('profile_picture')){
    function profile_picture(){
        if(auth()->check() && auth()->user()->meta->profile_avatar && !empty(auth()->user()->meta->profile_avatar)){
            $profile_picture=\Corcel\Model\Post::find(auth()->user()->meta->profile_avatar);
            return $profile_picture->url;
        }    
        return get_gravatar(auth()->user()->email)??false;
    }
}