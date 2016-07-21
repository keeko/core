<?php
namespace keeko\core\model;

use keeko\core\model\Base\Activity as BaseActivity;
use keeko\core\serializer\ActivitySerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_activity' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Activity extends BaseActivity implements ApiModelInterface {

	/**
	 */
	const VERB_ACCEPT = 'accept';

	/**
	 */
	const VERB_ACCESS = 'access';

	/**
	 */
	const VERB_ACKNOWLEDGE = 'acknowledge';

	/**
	 */
	const VERB_ADD = 'add';

	/**
	 */
	const VERB_AGREE = 'agree';

	/**
	 */
	const VERB_APPEND = 'append';

	/**
	 */
	const VERB_APPROVE = 'approve';

	/**
	 */
	const VERB_ARCHIVE = 'archive';

	/**
	 */
	const VERB_ASSIGN = 'assign';

	/**
	 */
	const VERB_AT = 'at';

	/**
	 */
	const VERB_ATTACH = 'attach';

	/**
	 */
	const VERB_ATTEND = 'attend';

	/**
	 */
	const VERB_AUTHOR = 'author';

	/**
	 */
	const VERB_AUTHORIZE = 'authorize';

	/**
	 */
	const VERB_BOOKMARK = 'bookmark';

	/**
	 */
	const VERB_BORROW = 'borrow';

	/**
	 */
	const VERB_BUILD = 'build';

	/**
	 */
	const VERB_CANCEL = 'cancel';

	/**
	 */
	const VERB_CHANGE = 'change';

	/**
	 */
	const VERB_CHECKIN = 'checkin';

	/**
	 */
	const VERB_CLOSE = 'close';

	/**
	 */
	const VERB_COMMENT = 'comment';

	/**
	 */
	const VERB_COMPLETE = 'complete';

	/**
	 */
	const VERB_CONFIRM = 'confirm';

	/**
	 */
	const VERB_CONSUME = 'consume';

	/**
	 */
	const VERB_CREATE = 'create';

	/**
	 */
	const VERB_DELETE = 'delete';

	/**
	 */
	const VERB_DELIVER = 'deliver';

	/**
	 */
	const VERB_DENY = 'deny';

	/**
	 */
	const VERB_DISAGREE = 'disagree';

	/**
	 */
	const VERB_DISLIKE = 'dislike';

	/**
	 */
	const VERB_EDIT = 'edit';

	/**
	 */
	const VERB_EXPERIENCE = 'experience';

	/**
	 */
	const VERB_FAVORITE = 'favorite';

	/**
	 */
	const VERB_FIND = 'find';

	/**
	 */
	const VERB_FLAG_AS_INAPPROPRIATE = 'flag-as-inappropriate';

	/**
	 */
	const VERB_FOLLOW = 'follow';

	/**
	 */
	const VERB_FORK = 'fork';

	/**
	 */
	const VERB_GIVE = 'give';

	/**
	 */
	const VERB_HOST = 'host';

	/**
	 */
	const VERB_IGNORE = 'ignore';

	/**
	 */
	const VERB_INSERT = 'insert';

	/**
	 */
	const VERB_INSTALL = 'install';

	/**
	 */
	const VERB_INTERACT = 'interact';

	/**
	 */
	const VERB_INVITE = 'invite';

	/**
	 */
	const VERB_JOIN = 'join';

	/**
	 */
	const VERB_LEAVE = 'leave';

	/**
	 */
	const VERB_LIKE = 'like';

	/**
	 */
	const VERB_LISTEN = 'listen';

	/**
	 */
	const VERB_LOSE = 'lose';

	/**
	 */
	const VERB_LOVE = 'love';

	/**
	 */
	const VERB_MAKE_FRIEND = 'make-friend';

	/**
	 */
	const VERB_OPEN = 'open';

	/**
	 */
	const VERB_PLAY = 'play';

	/**
	 */
	const VERB_POST = 'post';

	/**
	 */
	const VERB_PRESENT = 'present';

	/**
	 */
	const VERB_PURCHASE = 'purchase';

	/**
	 */
	const VERB_PUSH = 'push';

	/**
	 */
	const VERB_QUALIFY = 'qualify';

	/**
	 */
	const VERB_READ = 'read';

	/**
	 */
	const VERB_REJECT = 'reject';

	/**
	 */
	const VERB_REMOVE = 'remove';

	/**
	 */
	const VERB_REMOVE_FRIEND = 'remove-friend';

	/**
	 */
	const VERB_REPLACE = 'replace';

	/**
	 */
	const VERB_REQUEST = 'request';

	/**
	 */
	const VERB_REQUEST_FRIEND = 'request-friend';

	/**
	 */
	const VERB_RESOLVE = 'resolve';

	/**
	 */
	const VERB_RETRACT = 'retract';

	/**
	 */
	const VERB_RETURN = 'return';

	/**
	 */
	const VERB_RSVP_MAYBE = 'rsvp-maybe';

	/**
	 */
	const VERB_RSVP_NO = 'rsvp-no';

	/**
	 */
	const VERB_RSVP_YES = 'rsvp-yes';

	/**
	 */
	const VERB_SATISFY = 'satisfy';

	/**
	 */
	const VERB_SAVE = 'save';

	/**
	 */
	const VERB_SCHEDULE = 'schedule';

	/**
	 */
	const VERB_SEARCH = 'search';

	/**
	 */
	const VERB_SELL = 'sell';

	/**
	 */
	const VERB_SEND = 'send';

	/**
	 */
	const VERB_SHARE = 'share';

	/**
	 */
	const VERB_SPONSOR = 'sponsor';

	/**
	 */
	const VERB_STAR = 'star';

	/**
	 */
	const VERB_START = 'start';

	/**
	 */
	const VERB_STOP = 'stop';

	/**
	 */
	const VERB_STOP_FOLLOWING = 'stop-following';

	/**
	 */
	const VERB_SUBMIT = 'submit';

	/**
	 */
	const VERB_TAG = 'tag';

	/**
	 */
	const VERB_TERMINATE = 'terminate';

	/**
	 */
	const VERB_TIE = 'tie';

	/**
	 */
	const VERB_UNFAVORITE = 'unfavorite';

	/**
	 */
	const VERB_UNLIKE = 'unlike';

	/**
	 */
	const VERB_UNSATISFY = 'unsatisfy';

	/**
	 */
	const VERB_UNSAVE = 'unsave';

	/**
	 */
	const VERB_UNSHARE = 'unshare';

	/**
	 */
	const VERB_UPDATE = 'update';

	/**
	 */
	const VERB_UPLOAD = 'upload';

	/**
	 */
	const VERB_USE = 'use';

	/**
	 */
	const VERB_WATCH = 'watch';

	/**
	 */
	const VERB_WIN = 'win';

	/**
	 */
	private static $serializer;

	/**
	 * @return ActivitySerializer
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new ActivitySerializer();
		}

		return self::$serializer;
	}
}
