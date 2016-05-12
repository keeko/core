<?php
namespace keeko\core\event;

use keeko\core\model\Session;
use Symfony\Component\EventDispatcher\Event;

/**
 */
class SessionEvent extends Event {

	/**
	 */
	const POST_CREATE = 'core.session.post_create';

	/**
	 */
	const POST_DELETE = 'core.session.post_delete';

	/**
	 */
	const POST_SAVE = 'core.session.post_save';

	/**
	 */
	const POST_UPDATE = 'core.session.post_update';

	/**
	 */
	const POST_USER_UPDATE = 'core.session.post_user_update';

	/**
	 */
	const PRE_CREATE = 'core.session.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.session.pre_delete';

	/**
	 */
	const PRE_SAVE = 'core.session.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.session.pre_update';

	/**
	 */
	const PRE_USER_UPDATE = 'core.session.pre_user_update';

	/**
	 * @var keeko.core.model
	 */
	protected $session;

	/**
	 * @param Session $session
	 */
	public function __construct(Session $session) {
		$this->session = $session;
	}

	/**
	 * @return Session
	 */
	public function getSession() {
		return $this->session;
	}
}
