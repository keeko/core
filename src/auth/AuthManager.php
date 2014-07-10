<?php
namespace keeko\core\auth;

use Symfony\Component\HttpFoundation\Request;
use keeko\core\model\UserQuery;
use keeko\core\model\User;
use keeko\core\model\AuthQuery;

class AuthManager {
	
	/**
	 * @var User
	 */
	private $user;
	
	private $authenticated = false;
	
	private $authorized = false;
	
	public function __construct() {
		$request = Request::createFromGlobals();
		$strategies = ['header', 'basic', 'cookie'];

		foreach ($strategies as $strategy) {
			$method = 'auth' . ucfirst($strategy);
			if ($this->$method($request)) {
				return;
			}
		}

		// auth failed - fetch guest
		$this->user = UserQuery::create()->findOneById(-1);
		$this->initUser($this->user);
	}
	
	private function authCookie(Request $request) {
		if ($request->cookies->has('Bearer')) {
			$bearer = $request->cookies->get('Bearer');
			return $this->authToken($bearer);
		}
		return false;
	}

	private function authHeader(Request $request) {
		if ($request->headers->has('Authorization')) {
			$auth = $request->headers->get('Authorization');
			list(, $bearer) = explode(' ', $auth);
			return $this->authToken($bearer);
		}
		return false;
	}
	
	private function authToken($token) {
		$user = AuthQuery::create()->innerJoinUser()->findOneByToken($token);
		
		if ($user !== null) {
			$this->user = $user;
			$this->authenticated = true;
			$this->authorized = true;
			$this->initUser($user);
			return true;
		}
		
		return false;
	}
	
	private function authBasic(Request $request) {
		return $this->login($request->getUser(), $request->getPassword());
	}
	
	/**
	 * TODO: Probably not the best location/method-name and response (throw an exception?)
	 *
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function login($username, $password) {
		$user = UserQuery::create()->filterByLoginName($username)->findOne();

		if ($user) {
			$this->user = $user;
			$this->authenticated = true;
			
			if (password_verify($password, $user->getPassword())) {
				$this->authorized = true;
				$this->initUser($user);
				return true;
			}
		}
		
		return false;
	}
	
	private function initUser(User $user) {
		$user->updatePermissions();
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function isAuthenticated() {
		return $this->authenticated;
	}
	
	public function isAuthorized() {
		return $this->authorized;
	}
}
