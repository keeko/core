<?php
namespace keeko\core\domain\base;

use keeko\core\model\Localization;
use keeko\core\model\LocalizationQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\utils\Parameters;
use keeko\framework\utils\NameUtils;
use keeko\core\event\LocalizationEvent;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\Updated;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\NotDeleted;
use keeko\core\model\LanguageVariantQuery;
use keeko\core\model\LocalizationVariantQuery;
use keeko\core\model\ApplicationUriQuery;

/**
 */
trait LocalizationDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Adds ApplicationUris to Localization
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addApplicationUris($id, $data) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			}
			$applicationUri = ApplicationUriQuery::create()->findOneById($entry['id']);
			$localization->addApplicationUri($applicationUri);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_APPLICATION_URIS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $localization->save();
		$dispatcher->dispatch(LocalizationEvent::POST_APPLICATION_URIS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Adds LanguageVariants to Localization
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addLanguageVariants($id, $data) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for LanguageVariant';
			}
			$languageVariant = LanguageVariantQuery::create()->findOneById($entry['id']);
			$localization->addLanguageVariant($languageVariant);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LANGUAGE_VARIANTS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $localization->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LANGUAGE_VARIANTS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Adds Localizations to Localization
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addLocalizations($id, $data) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Localization';
			}
			$localization = LocalizationQuery::create()->findOneById($entry['id']);
			$localization->addLocalization($localization);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LOCALIZATIONS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $localization->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LOCALIZATIONS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Creates a new Localization with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$serializer = Localization::getSerializer();
		$localization = $serializer->hydrate(new Localization(), $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($localization)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_CREATE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$localization->save();
		$dispatcher->dispatch(LocalizationEvent::POST_CREATE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);
		return new Created(['model' => $localization]);
	}

	/**
	 * Deletes a Localization with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// delete
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_DELETE, $event);
		$localization->delete();

		if ($localization->isDeleted()) {
			$dispatcher->dispatch(LocalizationEvent::POST_DELETE, $event);
			return new Deleted(['model' => $localization]);
		}

		return new NotDeleted(['message' => 'Could not delete Localization']);
	}

	/**
	 * Returns a paginated result
	 * 
	 * @param Parameters $params
	 * @return PayloadInterface
	 */
	public function paginate(Parameters $params) {
		$sysPrefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$defaultSize = $sysPrefs->getPaginationSize();
		$page = $params->getPage('number');
		$size = $params->getPage('size', $defaultSize);

		$query = LocalizationQuery::create();

		// sorting
		$sort = $params->getSort(Localization::getSerializer()->getSortFields());
		foreach ($sort as $field => $order) {
			$method = 'orderBy' . NameUtils::toStudlyCase($field);
			$query->$method($order);
		}

		// filtering
		$filter = $params->getFilter();
		if (!empty($filter)) {
			$this->applyFilter($query, $filter);
		}

		// paginate
		$localization = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $localization]);
	}

	/**
	 * Returns one Localization with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$localization = $this->get($id);

		// check existence
		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		return new Found(['model' => $localization]);
	}

	/**
	 * Removes ApplicationUris from Localization
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeApplicationUris($id, $data) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			}
			$applicationUri = ApplicationUriQuery::create()->findOneById($entry['id']);
			$localization->removeApplicationUri($applicationUri);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_APPLICATION_URIS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $localization->save();
		$dispatcher->dispatch(LocalizationEvent::POST_APPLICATION_URIS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Removes LanguageVariants from Localization
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeLanguageVariants($id, $data) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for LanguageVariant';
			}
			$languageVariant = LanguageVariantQuery::create()->findOneById($entry['id']);
			$localization->removeLanguageVariant($languageVariant);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LANGUAGE_VARIANTS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $localization->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LANGUAGE_VARIANTS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Removes Localizations from Localization
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeLocalizations($id, $data) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Localization';
			}
			$localization = LocalizationQuery::create()->findOneById($entry['id']);
			$localization->removeLocalization($localization);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LOCALIZATIONS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $localization->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LOCALIZATIONS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Sets the ExtLang id
	 * 
	 * @param mixed $id
	 * @param mixed $extLangId
	 * @return PayloadInterface
	 */
	public function setExtLangId($id, $extLangId) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// update
		if ($localization->getExtLanguageId() !== $extLangId) {
			$localization->setExtLanguageId($extLangId);

			$event = new LocalizationEvent($localization);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(LocalizationEvent::PRE_EXT_LANG_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
			$localization->save();
			$dispatcher->dispatch(LocalizationEvent::POST_EXT_LANG_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);
			
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Sets the Language id
	 * 
	 * @param mixed $id
	 * @param mixed $languageId
	 * @return PayloadInterface
	 */
	public function setLanguageId($id, $languageId) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// update
		if ($localization->getLanguageId() !== $languageId) {
			$localization->setLanguageId($languageId);

			$event = new LocalizationEvent($localization);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(LocalizationEvent::PRE_LANGUAGE_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
			$localization->save();
			$dispatcher->dispatch(LocalizationEvent::POST_LANGUAGE_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);
			
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Sets the Parent id
	 * 
	 * @param mixed $id
	 * @param mixed $parentId
	 * @return PayloadInterface
	 */
	public function setParentId($id, $parentId) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// update
		if ($localization->getParentId() !== $parentId) {
			$localization->setParentId($parentId);

			$event = new LocalizationEvent($localization);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(LocalizationEvent::PRE_PARENT_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
			$localization->save();
			$dispatcher->dispatch(LocalizationEvent::POST_PARENT_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);
			
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Sets the Script id
	 * 
	 * @param mixed $id
	 * @param mixed $scriptId
	 * @return PayloadInterface
	 */
	public function setScriptId($id, $scriptId) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// update
		if ($localization->getScriptId() !== $scriptId) {
			$localization->setScriptId($scriptId);

			$event = new LocalizationEvent($localization);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(LocalizationEvent::PRE_SCRIPT_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
			$localization->save();
			$dispatcher->dispatch(LocalizationEvent::POST_SCRIPT_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);
			
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Updates a Localization with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// hydrate
		$serializer = Localization::getSerializer();
		$localization = $serializer->hydrate($localization, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($localization)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $localization->save();
		$dispatcher->dispatch(LocalizationEvent::POST_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		$payload = ['model' => $localization];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Updates ApplicationUris on Localization
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateApplicationUris($id, $data) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove all relationships before
		ApplicationUriQuery::create()->filterByLocalization($localization)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			}
			$applicationUri = ApplicationUriQuery::create()->findOneById($entry['id']);
			$localization->addApplicationUri($applicationUri);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_APPLICATION_URIS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $localization->save();
		$dispatcher->dispatch(LocalizationEvent::POST_APPLICATION_URIS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Updates LanguageVariants on Localization
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateLanguageVariants($id, $data) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove all relationships before
		LocalizationVariantQuery::create()->filterByLocalization($localization)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for LanguageVariant';
			}
			$languageVariant = LanguageVariantQuery::create()->findOneById($entry['id']);
			$localization->addLanguageVariant($languageVariant);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LANGUAGE_VARIANTS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $localization->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LANGUAGE_VARIANTS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Updates Localizations on Localization
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateLocalizations($id, $data) {
		// find
		$localization = $this->get($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove all relationships before
		LocalizationQuery::create()->filterByParent($localization)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Localization';
			}
			$localization = LocalizationQuery::create()->findOneById($entry['id']);
			$localization->addLocalization($localization);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($localization);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LOCALIZATIONS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $localization->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LOCALIZATIONS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $localization]);
		}

		return NotUpdated(['model' => $localization]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\LocalizationDomain
	 * 
	 * @param LocalizationQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(LocalizationQuery $query, $filter);

	/**
	 * Returns one Localization with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Localization|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$localization = LocalizationQuery::create()->findOneById($id);
		$this->pool->set($id, $localization);

		return $localization;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
