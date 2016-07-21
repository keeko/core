<?php
namespace keeko\core\domain\base;

use keeko\core\event\LocalizationEvent;
use keeko\core\model\ApplicationUriQuery;
use keeko\core\model\LanguageVariantQuery;
use keeko\core\model\LocalizationQuery;
use keeko\core\model\LocalizationVariantQuery;
use keeko\core\model\Localization;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotDeleted;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\PayloadInterface;
use keeko\framework\domain\payload\Updated;
use keeko\framework\exceptions\ErrorsException;
use keeko\framework\service\ServiceContainer;
use keeko\framework\utils\NameUtils;
use keeko\framework\utils\Parameters;
use phootwork\collection\Map;
use phootwork\lang\Text;

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

		// pass add to internal logic
		try {
			$this->doAddApplicationUris($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(LocalizationEvent::PRE_APPLICATION_URIS_ADD, $model, $data);
		$this->dispatch(LocalizationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(LocalizationEvent::POST_APPLICATION_URIS_ADD, $model, $data);
		$this->dispatch(LocalizationEvent::POST_SAVE, $model, $data);

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

		// pass add to internal logic
		try {
			$this->doAddLanguageVariants($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(LocalizationEvent::PRE_LANGUAGE_VARIANTS_ADD, $model, $data);
		$this->dispatch(LocalizationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(LocalizationEvent::POST_LANGUAGE_VARIANTS_ADD, $model, $data);
		$this->dispatch(LocalizationEvent::POST_SAVE, $model, $data);

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

		// pass add to internal logic
		try {
			$this->doAddLocalizations($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(LocalizationEvent::PRE_LOCALIZATIONS_ADD, $model, $data);
		$this->dispatch(LocalizationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(LocalizationEvent::POST_LOCALIZATIONS_ADD, $model, $data);
		$this->dispatch(LocalizationEvent::POST_SAVE, $model, $data);

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
		$data = $this->normalize($data);
		$serializer = Localization::getSerializer();
		$model = $serializer->hydrate(new Localization(), $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(LocalizationEvent::PRE_CREATE, $model, $data);
		$this->dispatch(LocalizationEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispatch post save hooks
		$model->save();
		$this->dispatch(LocalizationEvent::POST_CREATE, $model, $data);
		$this->dispatch(LocalizationEvent::POST_SAVE, $model, $data);

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
		$this->dispatch(LocalizationEvent::PRE_DELETE, $model);
		$model->delete();

		if ($model->isDeleted()) {
			$this->dispatch(LocalizationEvent::POST_DELETE, $model);
			return new Deleted(['model' => $model]);
		}

		return new NotDeleted(['message' => 'Could not delete Localization']);
	}

	/**
	 * @param array $data
	 * @return array normalized data
	 */
	public function normalize(array $data) {
		$service = $this->getServiceContainer();
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];


		$data['attributes'] = $attribs;

		return $data;
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
		if ($size == -1) {
			$model = $query->findAll();
		} else {
			$model = $query->paginate($page, $size);
		}

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

		// pass remove to internal logic
		try {
			$this->doRemoveApplicationUris($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(LocalizationEvent::PRE_APPLICATION_URIS_REMOVE, $model, $data);
		$this->dispatch(LocalizationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(LocalizationEvent::POST_APPLICATION_URIS_REMOVE, $model, $data);
		$this->dispatch(LocalizationEvent::POST_SAVE, $model, $data);

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

		// pass remove to internal logic
		try {
			$this->doRemoveLanguageVariants($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(LocalizationEvent::PRE_LANGUAGE_VARIANTS_REMOVE, $model, $data);
		$this->dispatch(LocalizationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(LocalizationEvent::POST_LANGUAGE_VARIANTS_REMOVE, $model, $data);
		$this->dispatch(LocalizationEvent::POST_SAVE, $model, $data);

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

		// pass remove to internal logic
		try {
			$this->doRemoveLocalizations($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(LocalizationEvent::PRE_LOCALIZATIONS_REMOVE, $model, $data);
		$this->dispatch(LocalizationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(LocalizationEvent::POST_LOCALIZATIONS_REMOVE, $model, $data);
		$this->dispatch(LocalizationEvent::POST_SAVE, $model, $data);

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
		if ($this->doSetExtLangId($model, $relatedId)) {
			$this->dispatch(LocalizationEvent::PRE_EXT_LANG_UPDATE, $model);
			$this->dispatch(LocalizationEvent::PRE_SAVE, $model);
			$model->save();
			$this->dispatch(LocalizationEvent::POST_EXT_LANG_UPDATE, $model);
			$this->dispatch(LocalizationEvent::POST_SAVE, $model);

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
		if ($this->doSetLanguageId($model, $relatedId)) {
			$this->dispatch(LocalizationEvent::PRE_LANGUAGE_UPDATE, $model);
			$this->dispatch(LocalizationEvent::PRE_SAVE, $model);
			$model->save();
			$this->dispatch(LocalizationEvent::POST_LANGUAGE_UPDATE, $model);
			$this->dispatch(LocalizationEvent::POST_SAVE, $model);

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
		if ($this->doSetParentId($model, $relatedId)) {
			$this->dispatch(LocalizationEvent::PRE_PARENT_UPDATE, $model);
			$this->dispatch(LocalizationEvent::PRE_SAVE, $model);
			$model->save();
			$this->dispatch(LocalizationEvent::POST_PARENT_UPDATE, $model);
			$this->dispatch(LocalizationEvent::POST_SAVE, $model);

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
		if ($this->doSetScriptId($model, $relatedId)) {
			$this->dispatch(LocalizationEvent::PRE_SCRIPT_UPDATE, $model);
			$this->dispatch(LocalizationEvent::PRE_SAVE, $model);
			$model->save();
			$this->dispatch(LocalizationEvent::POST_SCRIPT_UPDATE, $model);
			$this->dispatch(LocalizationEvent::POST_SAVE, $model);

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
		$data = $this->normalize($data);
		$serializer = Localization::getSerializer();
		$model = $serializer->hydrate($model, $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(LocalizationEvent::PRE_UPDATE, $model, $data);
		$this->dispatch(LocalizationEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispath post save hooks
		$rows = $model->save();
		$this->dispatch(LocalizationEvent::POST_UPDATE, $model, $data);
		$this->dispatch(LocalizationEvent::POST_SAVE, $model, $data);

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

		// pass update to internal logic
		try {
			$this->doUpdateApplicationUris($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(LocalizationEvent::PRE_APPLICATION_URIS_UPDATE, $model, $data);
		$this->dispatch(LocalizationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(LocalizationEvent::POST_APPLICATION_URIS_UPDATE, $model, $data);
		$this->dispatch(LocalizationEvent::POST_SAVE, $model, $data);

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

		// pass update to internal logic
		try {
			$this->doUpdateLanguageVariants($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(LocalizationEvent::PRE_LANGUAGE_VARIANTS_UPDATE, $model, $data);
		$this->dispatch(LocalizationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(LocalizationEvent::POST_LANGUAGE_VARIANTS_UPDATE, $model, $data);
		$this->dispatch(LocalizationEvent::POST_SAVE, $model, $data);

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

		// pass update to internal logic
		try {
			$this->doUpdateLocalizations($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(LocalizationEvent::PRE_LOCALIZATIONS_UPDATE, $model, $data);
		$this->dispatch(LocalizationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(LocalizationEvent::POST_LOCALIZATIONS_UPDATE, $model, $data);
		$this->dispatch(LocalizationEvent::POST_SAVE, $model, $data);

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
		if (is_array($filter)) {

			// filter by fields
			if (isset($filter['fields'])) {
		    	foreach ($filter['fields'] as $column => $value) {
		        	$pos = strpos($column, '.');
		        	if ($pos !== false) {
		        		$rel = NameUtils::toStudlyCase(substr($column, 0, $pos));
		        		$col = substr($column, $pos + 1);
		        		$method = 'use' . $rel . 'Query';
		        		if (method_exists($query, $method)) {
		        			$sub = $query->$method();
		        			$this->applyFilter($sub, ['fields' => [$col => $value]]);
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
		    
		    // filter by features
		    if (isset($filter['features'])) {
		    	$features = new Text($filter['features']);
		    	if ($features->contains('random')) {
		    		$query->addAscendingOrderByColumn('RAND()');
		    	}
		    }
		}

		if (method_exists($this, 'filter')) {
			$this->filter($query, $filter);
		}
	}

	/**
	 * @param string $type
	 * @param Localization $model
	 * @param array $data
	 */
	protected function dispatch($type, Localization $model, array $data = []) {
		$methods = [
			LocalizationEvent::PRE_CREATE => 'preCreate',
			LocalizationEvent::POST_CREATE => 'postCreate',
			LocalizationEvent::PRE_UPDATE => 'preUpdate',
			LocalizationEvent::POST_UPDATE => 'postUpdate',
			LocalizationEvent::PRE_DELETE => 'preDelete',
			LocalizationEvent::POST_DELETE => 'postDelete',
			LocalizationEvent::PRE_SAVE => 'preSave',
			LocalizationEvent::POST_SAVE => 'postSave'
		];

		if (isset($methods[$type])) {
			$method = $methods[$type];
			if (method_exists($this, $method)) {
				$this->$method($model, $data);
			}
		}

		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch($type, new LocalizationEvent($model));
	}

	/**
	 * Interal mechanism to add ApplicationUris to Localization
	 * 
	 * @param Localization $model
	 * @param mixed $data
	 */
	protected function doAddApplicationUris(Localization $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			} else {
				$related = ApplicationUriQuery::create()->findOneById($entry['id']);
				$model->addApplicationUri($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to add LanguageVariants to Localization
	 * 
	 * @param Localization $model
	 * @param mixed $data
	 */
	protected function doAddLanguageVariants(Localization $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for LanguageVariant';
			} else {
				$related = LanguageVariantQuery::create()->findOneById($entry['id']);
				$model->addLanguageVariant($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to add Localizations to Localization
	 * 
	 * @param Localization $model
	 * @param mixed $data
	 */
	protected function doAddLocalizations(Localization $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Localization';
			} else {
				$related = LocalizationQuery::create()->findOneById($entry['id']);
				$model->addLocalization($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to remove ApplicationUris from Localization
	 * 
	 * @param Localization $model
	 * @param mixed $data
	 */
	protected function doRemoveApplicationUris(Localization $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			} else {
				$related = ApplicationUriQuery::create()->findOneById($entry['id']);
				$model->removeApplicationUri($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to remove LanguageVariants from Localization
	 * 
	 * @param Localization $model
	 * @param mixed $data
	 */
	protected function doRemoveLanguageVariants(Localization $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for LanguageVariant';
			} else {
				$related = LanguageVariantQuery::create()->findOneById($entry['id']);
				$model->removeLanguageVariant($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to remove Localizations from Localization
	 * 
	 * @param Localization $model
	 * @param mixed $data
	 */
	protected function doRemoveLocalizations(Localization $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Localization';
			} else {
				$related = LocalizationQuery::create()->findOneById($entry['id']);
				$model->removeLocalization($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Internal mechanism to set the ExtLang id
	 * 
	 * @param Localization $model
	 * @param mixed $relatedId
	 */
	protected function doSetExtLangId(Localization $model, $relatedId) {
		if ($model->getExtLanguageId() !== $relatedId) {
			$model->setExtLanguageId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Internal mechanism to set the Language id
	 * 
	 * @param Localization $model
	 * @param mixed $relatedId
	 */
	protected function doSetLanguageId(Localization $model, $relatedId) {
		if ($model->getLanguageId() !== $relatedId) {
			$model->setLanguageId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Internal mechanism to set the Parent id
	 * 
	 * @param Localization $model
	 * @param mixed $relatedId
	 */
	protected function doSetParentId(Localization $model, $relatedId) {
		if ($model->getParentId() !== $relatedId) {
			$model->setParentId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Internal mechanism to set the Script id
	 * 
	 * @param Localization $model
	 * @param mixed $relatedId
	 */
	protected function doSetScriptId(Localization $model, $relatedId) {
		if ($model->getScriptId() !== $relatedId) {
			$model->setScriptId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Internal update mechanism of ApplicationUris on Localization
	 * 
	 * @param Localization $model
	 * @param mixed $data
	 */
	protected function doUpdateApplicationUris(Localization $model, $data) {
		// remove all relationships before
		ApplicationUriQuery::create()->filterByLocalization($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			} else {
				$related = ApplicationUriQuery::create()->findOneById($entry['id']);
				$model->addApplicationUri($related);
			}
		}

		if (count($errors) > 0) {
			throw new ErrorsException($errors);
		}
	}

	/**
	 * Internal update mechanism of LanguageVariants on Localization
	 * 
	 * @param Localization $model
	 * @param mixed $data
	 */
	protected function doUpdateLanguageVariants(Localization $model, $data) {
		// remove all relationships before
		LocalizationVariantQuery::create()->filterByLocalization($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for LanguageVariant';
			} else {
				$related = LanguageVariantQuery::create()->findOneById($entry['id']);
				$model->addLanguageVariant($related);
			}
		}

		if (count($errors) > 0) {
			throw new ErrorsException($errors);
		}
	}

	/**
	 * Internal update mechanism of Localizations on Localization
	 * 
	 * @param Localization $model
	 * @param mixed $data
	 */
	protected function doUpdateLocalizations(Localization $model, $data) {
		// remove all relationships before
		LocalizationQuery::create()->filterByParent($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Localization';
			} else {
				$related = LocalizationQuery::create()->findOneById($entry['id']);
				$model->addLocalization($related);
			}
		}

		if (count($errors) > 0) {
			throw new ErrorsException($errors);
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
