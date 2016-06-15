<?php
namespace Bad;
/**
 * author 逆雪寒
 * version 0.9.1
 */
class Rabbit {

	const YES = 1;
	const NO = 0;

	private static $instance = NULL;
	private $host = '';

	public $trace = self::NO;

	private function __construct($host,$port){ 
		$this->host($host,$port);
	}
	 
	public function __clone(){
		trigger_error('Clone is not allow!',E_USER_ERROR);
	}

	/**
	 * 调试接口返回.
	 *
	 * @param string $msg .
	 * @return $this A reference to the current instance.
	 */
	private function trace($msg) {
		if($this->trace){
			exit($msg);
		}
	}

	private function decode($data) {
		$this->trace($data);
		return json_decode($data,true);
	}

	private function host($host,$port) {
		$this->host = "http://" . $host . ":" . $port."/";
	}

	public function factory($host = 'localhost',$port = 9394) {
		if(is_null(self::$instance)) {
			self::$instance = new self($host,$port);
		}
		return self::$instance;
	}

	private function request($do,Array $parameter = [],$method = 'GET') {
		$query = '';

		$ch = curl_init(); 

		if($method != 'GET') {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $parameter);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

		}else{
			$query = "?" . http_build_query($parameter);
		}

		curl_setopt($ch, CURLOPT_URL, $this->host.$do.$query);
		curl_setopt($ch, CURLOPT_TIMEOUT,60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

		$data = curl_exec($ch);
		if(curl_errno($ch)){ 
		  return false;
		}
		curl_close($ch);
		return $data;
	}

	/**
	 * 内容过滤.
	 *
	 * @param string 	$contents 要过滤的内容
	 * @return bool or array
	 */
	public function filter($contents) {
		$result = $this->request("filter",['contents' => $contents],'POST');
		if($result !== false) {
			$code = $this->decode($result);
			if(isset($code['success']) && $code['success'] === self::NO) {
				return false;
			}
			return $code;
		}
		return false;
	}

	/**
	 * 色图过滤.
	 *
	 * @param string 	$file 要过滤的图片 本地地址 或者 网络地址
	 * @return bool or int  分数  建议：65 - 85判断为性感 85以上为色情
	 */
	public function porn($file) {
		$result = $this->request("porn",['file' => $file],'POST');
		if($result !== false) {
			$code = $this->decode($result);
			if(isset($code['success']) && $code['success'] === self::NO) {
				return false;
			}
			return $code['score'];
		}
		return false;
	}

	/**
	 * 脏词删除.
	 *
	 * @param int 	$id 脏词id （必须）
	 * @return bool or array
	 */
	public function delete($id) {
		if(!$id) return false;
		$result = $this->request("delete",['id' => $id],'DELETE');
		if($result !== false) {
			$code = $this->decode($result);
			if(isset($code['success']) && $code['success'] === self::NO) {
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * 添加脏词.
	 *
	 * @param array 
	 * 			string  word 脏词 
	 * 			int 	category 脏词分类
	 * 			int 	rate 黑名单OR灰名单 1 or 2
 	 * 			int 	correct 是否畸形纠正  1  or 2
	 * @return bool or array.
	 */
	public function create(Array $parameter) {
		$result = $this->request("create",$parameter,'POST');
		if($result !== false) {
			$code = $this->decode($result);
			if(isset($code['success']) && $code['success'] === self::NO) {
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * 修改脏词.
	 *
	 * @param array 
	 *			int 	id 脏词id （必须）
	 * 			string  word 脏词 
	 * 			int 	category 脏词分类
	 * 			int 	rate 黑名单OR灰名单 1 or 2
 	 * 			int 	correct 是否畸形纠正  1  or 2
	 * @return bool
	 */
	public function revise(Array $parameter) {

		if(!isset($parameter['id']) || count($parameter) < 1) {
			return false;
		}

		$result = $this->request("revise",$parameter,'PUT');
		if($result !== false) {
			$code = $this->decode($result);
			if(isset($code['success']) && $code['success'] === self::NO) {
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * 查询脏词.
	 *
	 * @param array 
	 *			int 	id 脏词id
	 * 			string  word 脏词 
	 * 			int 	category 脏词分类
	 * 			int 	rate 黑名单OR灰名单 1 or 2
 	 * 			int 	correct 是否畸形纠正  1  or 2
	 * @return bool or array
	 */
	public function query(Array $parameter) {
		$result = $this->request("query",$parameter,'GET');
		if($result !== false) {
			$code = $this->decode($result);

			if(isset($code['success']) && $code['success'] === self::NO) {
				return false;
			}
			return $code;
		}
		return false;
	}

	/**
	 * 脏词分类.
	 * ID:分类名   1:个性化  2:低俗信息  3:灌水信息 5:政治敏感 6:违约广告  7:跨站追杀  8:色情信息 9:违法信息  10:垃圾广告
	 * @return bool or array
	 */
	public function category() {
		$result = $this->request("category");
		if($result !== false) {
			$code = $this->decode($result);

			if(isset($code['success']) && $code['success'] === self::NO) {
				return false;
			}
			
			return $code;
		}
		return false;
	}

	/**
	 * 脏词重新载入.
	 *
	 * @return bool
	 */
	public function reload() {
		$result = $this->request("reload");
		if($result !== false) {
			$code = $this->decode($result);

			if(isset($code['success']) && $code['success'] == self::NO) {
				return false;
			}
			return true;
		}
		return false;
	}
}

$rabbit = Rabbit::factory('127.0.0.1',9394);

// $rabbit->trace = true;

//$data = $rabbit->filter('万人敬仰+大兄弟 15009987776');

// $data = $rabbit->create([
// 			'word' => '什么狗屎+猪头三:phone',
// 			'category' => 3,
// 			'rate' => 1,
// 			'correct' => 1
// ]);

//$data = $rabbit->delete(29);

// $data = $rabbit->reload();

$score = $rabbit->porn("/Users/yehuiming/Downloads/6609c93d70cf3bc7e43db93dd500baa1cd112a25.jpg");

var_dump($score);
