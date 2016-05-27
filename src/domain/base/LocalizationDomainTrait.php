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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			}
			$related = ApplicationUriQuery::create()->findOneById($entry['id']);
			$model->addApplicationUri($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_APPLICATION_URIS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(LocalizationEvent::POST_APPLICATION_URIS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for LanguageVariant';
			}
			$related = LanguageVariantQuery::create()->findOneById($entry['id']);
			$model->addLanguageVariant($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LANGUAGE_VARIANTS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LANGUAGE_VARIANTS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Localization';
			}
			$related = LocalizationQuery::create()->findOneById($entry['id']);
			$model->addLocalization($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LOCALIZATIONS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LOCALIZATIONS_ADD, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $serializer->hydrate(new Localization(), $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_CREATE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$model->save();
		$dispatcher->dispatch(LocalizationEvent::POST_CREATE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);
		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a Localization with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// delete
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_DELETE, $event);
		$model->delete();

		if ($model->isDeleted()) {
			$dispatcher->dispatch(LocalizationEvent::POST_DELETE, $event);
			return new Deleted(['model' => $model]);
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
		$model = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $model]);
	}

	/**
	 * Returns one Localization with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		return new Found(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			}
			$related = ApplicationUriQuery::create()->findOneById($entry['id']);
			$model->removeApplicationUri($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_APPLICATION_URIS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(LocalizationEvent::POST_APPLICATION_URIS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for LanguageVariant';
			}
			$related = LanguageVariantQuery::create()->findOneById($entry['id']);
			$model->removeLanguageVariant($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LANGUAGE_VARIANTS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LANGUAGE_VARIANTS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Localization';
			}
			$related = LocalizationQuery::create()->findOneById($entry['id']);
			$model->removeLocalization($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LOCALIZATIONS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LOCALIZATIONS_REMOVE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Sets the ExtLang id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setExtLangId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// update
		if ($model->getExtLanguageId() !== $relatedId) {
			$model->setExtLanguageId($relatedId);

			$event = new LocalizationEvent($model);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(LocalizationEvent::PRE_EXT_LANG_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
			$model->save();
			$dispatcher->dispatch(LocalizationEvent::POST_EXT_LANG_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);
			
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Sets the Language id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setLanguageId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// update
		if ($model->getLanguageId() !== $relatedId) {
			$model->setLanguageId($relatedId);

			$event = new LocalizationEvent($model);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(LocalizationEvent::PRE_LANGUAGE_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
			$model->save();
			$dispatcher->dispatch(LocalizationEvent::POST_LANGUAGE_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);
			
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Sets the Parent id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setParentId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// update
		if ($model->getParentId() !== $relatedId) {
			$model->setParentId($relatedId);

			$event = new LocalizationEvent($model);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(LocalizationEvent::PRE_PARENT_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
			$model->save();
			$dispatcher->dispatch(LocalizationEvent::POST_PARENT_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);
			
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Sets the Script id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setScriptId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// update
		if ($model->getScriptId() !== $relatedId) {
			$model->setScriptId($relatedId);

			$event = new LocalizationEvent($model);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(LocalizationEvent::PRE_SCRIPT_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
			$model->save();
			$dispatcher->dispatch(LocalizationEvent::POST_SCRIPT_UPDATE, $event);
			$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);
			
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// hydrate
		$serializer = Localization::getSerializer();
		$model = $serializer->hydrate($model, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(LocalizationEvent::POST_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		$payload = ['model' => $model];

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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove all relationships before
		ApplicationUriQuery::create()->filterByLocalization($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			}
			$related = ApplicationUriQuery::create()->findOneById($entry['id']);
			$model->addApplicationUri($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_APPLICATION_URIS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(LocalizationEvent::POST_APPLICATION_URIS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove all relationships before
		LocalizationVariantQuery::create()->filterByLocalization($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for LanguageVariant';
			}
			$related = LanguageVariantQuery::create()->findOneById($entry['id']);
			$model->addLanguageVariant($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LANGUAGE_VARIANTS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LANGUAGE_VARIANTS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// remove all relationships before
		LocalizationQuery::create()->filterByParent($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Localization';
			}
			$related = LocalizationQuery::create()->findOneById($entry['id']);
			$model->addLocalization($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new LocalizationEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(LocalizationEvent::PRE_LOCALIZATIONS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(LocalizationEvent::POST_LOCALIZATIONS_UPDATE, $event);
		$dispatcher->dispatch(LocalizationEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * @param mixed $query
	 * @param mixed $filter
	 * @return void
	 */
	protected function applyFilter($query, $filter) {
		foreach ($filter as $column => $value) {
			$pos = strpos($column, '.');
			if ($pos !== false) {
				$rel = NameUtils::toStudlyCase(substr($column, 0, $pos));
				$col = substr($column, $pos + 1);
				$method = 'use' . $rel . 'Query';
				if (method_exists($query, $method)) {
					$sub = $query->$method();
					$this->applyFilter($sub, [$col => $value]);
					$sub->endUse();
				}
			} else {
				$method = 'filterBy' . NameUtils::toStudlyCase($column);
				if (method_exists($query, $method)) {
					$query->$method($value);
				}
			}
		}
	}

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

		$model = LocalizationQuery::create()->findOneById($id);
		$this->pool->set($id, $model);

		return $model;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
