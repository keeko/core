<?php
namespace keeko\core\serializer;

use keeko\framework\model\AbstractModelSerializer;
use keeko\core\serializer\base\SessionSerializerTrait;

/**
 */
class SessionSerializer extends AbstractModelSerializer {

	use SessionSerializerTrait;
}