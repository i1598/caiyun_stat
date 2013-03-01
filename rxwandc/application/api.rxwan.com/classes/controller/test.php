<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller {

	public function action_index()
	{
		$memcache = Cache::instance('memcache');
		$memcache->set('foo', 'being', 30);
		echo $memcache->get("foo", FALSE);
	}

} // End Welcome