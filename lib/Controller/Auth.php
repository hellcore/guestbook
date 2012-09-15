<?php
require_once 'Controller/Generic.php';
require_once 'Services/Auth/Manager.php';

class Controller_Auth extends Controller_Generic {

	public function __construct() {
		
	}

	public function run() {
		$this->addCondition(isset($_REQUEST['logout'])	, 'logout');									  
		$this->addCondition(isset($_REQUEST['login'])	, 'login');
		$this->addCondition(true, 'renderDefault');
		parent::run();
	}

	public function logout() {
		session_unset();
		session_destroy();
		header("location: /"); // redirect to guestbook
		exit;
	}

	public function login() {
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$login = Services_Auth_Manager::getInstance()->validateUser($username, $password);
		if (!(int) $login['count']) {
			$this->renderDefault("Login fail");
			exit;
		} else {
			session_regenerate_id();
			$_SESSION['login'] = true;
			header("location: /"); // redirect to guestbook
			exit;
		}
	}

	public function renderDefault($msg = "Insert your credentials") {
		ob_clean();
		$this->renderPageStart();
		include 'Templates/Auth/login_form.html';
		$this->renderPageEnd();
	}
}