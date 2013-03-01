<?php defined('SYSPATH') OR die('No Direct Script Access');

Class Controller_GameList extends Controller {
	
	
	public function action_index() {
		$sorting = Arr::get($_GET, 'a');
		
		$this -> response -> body('hello, world!333' . date('y-m-d H:i:s', 1353662499));
	}

	public function action_game() {
		
		$gameWindow = ORM::factory('Xgame_Game');
		$xgame = ORM::factory('Xgame_Xgame');
		
		$flashUrl = $gameWindow->where('aid', '=', Arr::get($_GET, 'gameId')) -> find();
		$title = $xgame->where('id', '=', Arr::get($_GET, 'gameId')) -> find();
		
		$view = View::factory('game/game');
		$view->flash = $flashUrl -> flash;
		$view->title = iconv('gbk', 'utf8',$title -> title);
		
		$this->response->body($view);
	}
	/**
	 * 查询
	 * 不传参数默认为推荐游戏
	 * 1.推荐游戏
	 * 2.男生游戏
	 * 3.女生游戏
	 * 4.小游戏
	 * 5.热门游戏
	 * 6.对战游戏
	 */
	public function action_select() {
		$type = $this -> request -> param('kind');

		$xgame = ORM::factory('Xgame_Xgame');
		
		$xgameData = $xgame->limit(80)->offset(20)->find_all();
		
		$arr1 = array('gameId' => 1, 'gameName' => 'test', 'gameIcon' => 'http://xgame.rxwan.com/uploads/allimg/121123/1_11231H1394936.jpg', 'url' => 'http://xgame.rxwan.com/html/11/2012/1123/24522.html', 'type' => '1');
		$arr2 = array('gameId' => 2, 'gameName' => 'test', 'gameIcon' => 'http://xgame.rxwan.com/uploads/allimg/121123/1_11231H1394936.jpg', 'url' => 'http://xgame.rxwan.com/html/11/2012/1123/24522.html', 'type' => '2');
		$arr3 = array('gameId' => 3, 'gameName' => 'test', 'gameIcon' => 'http://xgame.rxwan.com/uploads/allimg/121123/1_11231H1394936.jpg', 'url' => 'http://xgame.rxwan.com/html/11/2012/1123/24522.html', 'type' => '1');
		$arr4 = array('gameId' => 4, 'gameName' => 'test', 'gameIcon' => 'http://xgame.rxwan.com/uploads/allimg/121123/1_11231H1394936.jpg', 'url' => 'http://xgame.rxwan.com/html/11/2012/1123/24522.html', 'type' => '2');

		switch ($type)
		{
		case 1:
		  $arrTeam = Array($arr1, $arr2, $arr3, $arr4);;
		  break;
		case 2:
		  $arrTeam = Array($arr1, $arr4, $arr3, $arr2);
		  break;
		case 3:
		  $arrTeam = Array($arr3, $arr4, $arr2, $arr1);
		  break;
		case 4:
		  $arrTeam = Array($arr3, $arr1, $arr2, $arr4);
		  break;
		case 5:
		  $arrTeam = Array($arr3, $arr2, $arr4, $arr1);
		  break;
		case 6:
		  $arrTeam = Array($arr3, $arr2, $arr1, $arr4);
		case 7:
		  $arrList = $xgameData -> as_array();
			for ($i=0; $i < count($arrList); $i++) {
				
				 $game = array(
				 	'gameIcon' => 'http://xgame.rxwan.com'.$arrList[$i]-> litpic,
				 	'gameId' => $arrList[$i] -> id,
				 	'url' => 'http://api.rxwan.com/index.php/gamelist/game?gameId='.$arrList[$i] -> id,
				 	//'url' =>'http://xgame.rxwan.com/html/'. $arrList[$i]->typeid. '/'. date('Y/md/', $arrList[$i]->senddate). $arrList[$i] -> id. '.html',
				 	'gameName' => iconv('gbk', 'utf8', $arrList[$i] -> title),
				 	'type' => 1
				 );
				 $arrTeam[$i] = $game;
			}
		  break;
		default:
		  $arrTeam = Array($arr1, $arr2, $arr3, $arr4);;
		}
		
		$this -> response -> body(json_encode($arrTeam));
	}

}
