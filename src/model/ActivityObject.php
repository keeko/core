<?php
namespace keeko\core\model;

use keeko\core\model\Base\ActivityObject as BaseActivityObject;
use keeko\core\serializer\ActivityObjectSerializer;
use keeko\framework\model\ApiModelInterface;

/**
 * Skeleton subclass for representing a row from the 'kk_activity_object' table.
 * 
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ActivityObject extends BaseActivityObject implements ApiModelInterface {

	/**
	 */
	const TYPE_ABILITY = 'ability';

	/**
	 */
	const TYPE_ANSWER = 'answer';

	/**
	 */
	const TYPE_ARTICLE = 'article';

	/**
	 */
	const TYPE_BOOK = 'book';

	/**
	 */
	const TYPE_COMMENT = 'comment';

	/**
	 */
	const TYPE_EVENT = 'event';

	/**
	 */
	const TYPE_EXERCISE = 'exercise';

	/**
	 */
	const TYPE_FRIEND = 'friend';

	/**
	 */
	const TYPE_GAME = 'game';

	/**
	 */
	const TYPE_GROUP = 'group';

	/**
	 */
	const TYPE_LIFE_EVENT = 'life-event';

	/**
	 */
	const TYPE_MOVIE = 'movie';

	/**
	 */
	const TYPE_MUSIC = 'music';

	/**
	 */
	const TYPE_NEWS = 'news';

	/**
	 */
	const TYPE_NOTE = 'note';

	/**
	 */
	const TYPE_OBJECT = 'object';

	/**
	 */
	const TYPE_PHOTO = 'photo';

	/**
	 */
	const TYPE_PICTURE = 'picture';

	/**
	 */
	const TYPE_POST = 'post';

	/**
	 */
	const TYPE_PRODUCT = 'product';

	/**
	 */
	const TYPE_QUESTION = 'question';

	/**
	 */
	const TYPE_REFERENCE = 'reference';

	/**
	 */
	const TYPE_SKILL = 'skill';

	/**
	 */
	const TYPE_SONG = 'song';

	/**
	 */
	const TYPE_SPORT = 'sport';

	/**
	 */
	const TYPE_TEST = 'test';

	/**
	 */
	const TYPE_VIDEO = 'video';

	/**
	 */
	private static $serializer;

	/**
	 * @return ActivityObjectSerializer
	 */
	public static function getSerializer() {
		if (self::$serializer === null) {
			self::$serializer = new ActivityObjectSerializer();
		}

		return self::$serializer;
	}
}
