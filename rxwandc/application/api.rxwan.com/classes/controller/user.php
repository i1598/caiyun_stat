<?php defined('SYSPATH') OR die('No Direct Script Access');

Class Controller_User extends Controller {
	
	private $code;
	
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request,$response);
		$this->code = Kohana::$config->load('code');
	}
	
	public function action_index() {
		$sorting = Arr::get($_GET, 'a');

		$this -> response -> body('hello, world!__'.time().'_____'.$this->generate_password(16));
	}

	/**
	 * 使用uuid新增用户
	 *
	 */
	public function action_getCasualAccount() {
		// Create an instance of a model
		$user = ORM::factory('User_User');
		$uuid = Arr::get($_GET, 'uuid');
		
		if($user -> where("uuid", "=", $uuid)-> count_all() >= 1){
			if($user -> where("uuid", "=", $uuid)->and_where("isActive","=",1)-> count_all() >= 1){
				
				$returnAccount = array('error' => 'this uuid is active');
				$this -> response -> body(json_encode($returnAccount));
			}else if($user -> where("uuid", "=", $uuid)->and_where("isActive","=",0)-> count_all() >= 1){
				$user = ORM::factory('User_User');
				$userAccount = $user->where("uuid","=",$uuid)->find();
				$returnAccount = array('casualAccount' => $userAccount -> casualAccount, 'casualPassword' => $userAccount -> casualPassword);
				$this -> response -> body(json_encode($returnAccount));
			}
		}else{
			$isInsert = true;
			do{
				//rand account
				$randAccount = 'rx'.time();
				$user -> casualAccount = $randAccount;
				$user -> casualPassword = $this->generate_password(16);
				$user -> status = 1;
				$user -> createTime = time();
				$user -> ip = $this->getIP();
				$user -> isActive = 0;
				$user -> uuid = $uuid;
				
				if($user -> where("casualAccount", "=", $randAccount) -> count_all() < 1){
					$user -> save();
					$returnAccount = array('casualAccount' => $user -> casualAccount, 'casualPassword' => $user -> casualPassword);
					//反馈账号
					$this -> response -> body(json_encode($returnAccount));
					$isInsert = false;
				}		
			}while($isInsert);
		}
	}

	/**
	 * 使用uuid激活账号
	 *
	 */
	public function action_activeAccount() {
		$uuid = Arr::get($_POST, 'uuid');
		
		$casualAccount = Arr::get($_POST, 'casualAccount');
		$casualPassword = Arr::get($_POST, 'casualPassword');
		
		// Create an instance of a model
		$user = ORM::factory('User_User');
		$user -> where("casualAccount", "=", $casualAccount) -> and_where("casualPassword", "=", $casualPassword) ->and_where("isActive","=",0)-> find();
		if($user -> loaded()){
			$user -> account = Arr::get($_POST, 'account');
			$user -> password = md5(Arr::get($_GET, 'password'));
			$user -> isActive = 1;
			$user -> save();
			$message = array('result' => true, 'data' => 'account active');
			$this -> response -> body(json_encode($message));
		}else{
			$message = array('result' => false, 'data' => 'active false');
			$this -> response -> body(json_encode($message));
		}
	}

	/**
	 * 登陆账号
	 */
	public function action_login() {
		$type = Arr::get($_POST, 'type');
		$uuid = Arr::get($_POST, 'uuid');
		$account = Arr::get($_POST, 'account');
		$password = Arr::get($_POST, 'password');
		
		$user = ORM::factory('User_User');
		
		$errorMessage =array('error' => 'login false!');
		
		if($type == 0){
			// Create an instance of a model
			$user -> where("casualAccount", "=", $account) -> and_where("casualPassword", "=", $password)->and_where("isActive","=",0)-> find();
			if($user->loaded()){
				$message = array(
					'account' => $user->casualAccount,
					'token' => $this->encrypt(array('uid' => $user->id,'timestamp' => time())),
					'img' => 'http://xgame.rxwan.com/uploads/allimg/121123/1_11231H1394936.jpg' 
				);
				$this -> response -> body(json_encode($message));
			}else{
				$this -> response -> body(json_encode($errorMessage));
			}
			
		}else{
			// Create an instance of a model
			$user -> where("account", "=", $account) -> and_where("password", "=", md5($password)) -> and_where("uuid", "=", $uuid) -> find();
			if($user->loaded()){
				$message = array(
					'account'=>$user->account,
					'token' => $this->encrypt(array('uid' => $user->id,'timestamp' => time())),
					'img' => 'http://xgame.rxwan.com/uploads/allimg/121123/1_11231H1394936.jpg' 
				);
				$this -> response -> body(json_encode($message));
			}else{
				$this -> response -> body(json_encode($errorMessage));
			}
		}

	}

	/**
	 * 退出账号
	 */
	public function action_logout() {
		$message = array('logout'=>'true');
		$this -> response -> body(json_encode($message));
	}
	
	public function action_test1()
	{		
		$decode = $this->encrypt(array('a'=>'a','b'=>'b'));
		$encode = $this->decrypt(urldecode($decode));
		echo json_encode($encode);
	}

	public function action_select() {
		// Create an instance of a model
		$members = ORM::factory('User_User');
		// Get all members with the first name "Peter" find_all()
		// means we get all records matching the query.
		$usera = $members -> where('account', '=', 'beingchou') -> find_all();
		// Count records in the $members object
		$members -> count_all();

		$this -> response -> body(json_encode($usera[0] -> as_array()));
	}

	/**
	 * 随机生成密码
	 */
	public function generate_password($length = 8) {
		// 密码字符集，可任意添加你需要的字符
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

		$password = '';
		for ($i = 0; $i < $length; $i++) {
			// 这里提供两种字符获取方式
			// 第一种是使用 substr 截取$chars中的任意一位字符；
			// 第二种是取字符数组 $chars 的任意元素
			// $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
			$password .= $chars[mt_rand(0, strlen($chars) - 1)];
		}

		return $password;
	}
	/**
	 * 获取来源IP
	 */
	public function getIP() {
		if (getenv("HTTP_X_FORWARDED_FOR"))
	        $ip = getenv("HTTP_X_FORWARDED_FOR");
	    else if (getenv("HTTP_CLIENT_IP"))
	        $ip = getenv("HTTP_CLIENT_IP");
	    else if (getenv("REMOTE_ADDR"))
	        $ip = getenv("REMOTE_ADDR");
	    else
	        $ip = "Unknown";
	    return $ip;
	}
	
	    /**
     * 加密
     * @param unknown_type $string
     */
    public function encrypt($array){
        $key = $this->code['key'];
        $iv = $this->code['iv'];
        $content = trim(json_encode($array));
        $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, ''); 
        $cipherText_b64 = '';
        if (mcrypt_generic_init($cipher, $key, $iv) != -1){  
            $cipherText = mcrypt_generic($cipher,$content);  
            mcrypt_generic_deinit($cipher);  
            mcrypt_module_close($cipher);  
            $cipherText_b64 = base64_encode($cipherText);
        }
        return urlencode($cipherText_b64);
    }
    
    /**
     * 解密
     * @param unknown_type $string
     */
    public function decrypt($string){
        $key = $this->code['key'];
        $iv = $this->code['iv'];
        $cipherText = $string;
        $cipherText = base64_decode($cipherText);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $p_t = '';
        if (mcrypt_generic_init($td, $key, $iv) != -1){  
            $p_t = (string)mdecrypt_generic($td, $cipherText);  
            mcrypt_generic_deinit($td);  
            mcrypt_module_close($td);  
        }
        return json_decode(trim($p_t),true);
    }
}
