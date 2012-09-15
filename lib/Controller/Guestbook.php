<?php
require_once 'Controller/Generic.php';
require_once 'Services/Guestbook/Dao.php';

class Controller_Guestbook extends Controller_Generic {

	public function __construct() {
		
	}

	public function run() {
		$this->addCondition(isset($_REQUEST['action']) && $_REQUEST['action'] == 'new', 								'insertEntrie');
		$this->addCondition(isset($_SESSION['login']) && $_SESSION['login'] === true && 
							!empty($_GET['id']) && isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete' , 		'deleteEntrie');
		$this->addCondition(true, 																						'renderDefault');
		parent::run();
	}

	protected function deleteEntrie() {
		$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
		try {
			if (empty($id)) throw new Exception("not valid id");
			Services_Guestbook_Dao::getInstance()->deleteById($id);
			header("location: /"); exit;
		} catch (Exception $e) {
			print $e->getMessage();
			// @todo render error page
		}
	}

	protected function insertEntrie() {
		$entrie = array();
		$entrie['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$entrie['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$entrie['comment'] = htmlentities($_POST['comment']); //filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);

		try {
			$errors = array();
			if (false === $entrie['email']) {$errors[] = "email not valid";}
			if (strlen($entrie['email']) > 65) {$errors[] = "your email can't have more that 65 characters"; }
			if (empty($entrie['name'])) {$errors[] = "name can't be empty";}
			if (strlen($entrie['name']) > 45) {$errors[] = "your name can't have more that 45 characters"; }
			if (empty($entrie['comment'])) {$errors[] = "comment can't be empty";}
			if (!empty($errors)) throw new Exception(implode("\r\n", $errors));

			Services_Guestbook_Dao::getInstance()->insert($entrie);
			//$this->renderDefault(); // render default page again
			header("location: /"); exit;
		} catch (Exception $e) {
			// @todo render error page
		}
	}

	protected function renderDefault() {
		ob_clean();
		$allGuestbookEntries = Services_Guestbook_Dao::getInstance()->fetchPage(0); // @todo implement paging
		$admin = isset($_SESSION['login']) && $_SESSION['login'];

		$this->renderPageStart();
		include 'Templates/Guestbook/listComments.html';
		include 'Templates/Guestbook/newComment.html';
		$this->renderPageEnd();
	}
}