<?php defined('SYSPATH') or die('No direct script access.');

class Controller_GameUrl extends Controller {

	public function action_index() {
		$this -> response -> body('hello, Test!');
	}

	public function action_goto() {
		$kind = $this -> request -> param('kind');
		switch ($kind) {
			case 1 :
				$view = View::factory('game/gamelist_index');
				break;
			case 2 :
				$view = View::factory('game/gamelist_all');
				break;
			case 3 :
				$view = View::factory('game/gamelist_webgame');
				break;
			case 4 :
				$view = View::factory('game/gamelist_xgame');
				break;
			case 5 :
				$view = View::factory('game/gamelist_phone');
				break;
			default :
				$view = View::factory('game/gamelist_index');
		}
		$this -> response -> body($view);
	}

}
