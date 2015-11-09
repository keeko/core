<?php
namespace keeko\core\schema;

use phootwork\collection\ArrayList;
use phootwork\collection\Collection;
use phootwork\collection\CollectionUtils;
use phootwork\collection\Map;
use phootwork\lang\Arrayable;
use phootwork\lang\Text;

/**
 * @author thomas
 *
 */
class PackageSchema extends RootSchema implements Arrayable {
	
	/** @var Map */
	private $data;
	
	/** @var string */
	private $name;
	
	/** @var string */
	private $vendor;
	
	/** @var string */
	private $fullName;
	
	/** @var string */
	private $description;
	
	/** @var string */
	private $type;
	
	/** @var string */
	private $license;
	
	/** @var ArrayList<AuthorSchema> */
	private $authors;
	
	/** @var Map<string, string> */
	private $require;
	
	/** @var Map<string, string> */
	private $requireDev;
	
	/** @var AutoloadSchema */
	private $autoload;
	
	/** @var Map<string, mixed> */
	private $extra;
	
	/** @var KeekoSchema */
	private $keeko;

	public function __construct($contents = []) {
		$this->parse($contents);
	}
	
	private function parse($contents) {
		$data = new Map($contents);
	
		$this->setFullName($data->get('name'));
		
		$this->description = $data->get('description');
		$this->type = $data->get('type');
		$this->license = $data->get('license');
		
		$this->authors = new ArrayList();
		if ($data->has('authors')) {
			foreach ($data->get('authors') as $authorData) {
				$this->authors->add(new AuthorSchema($authorData));
			}
		}

		$this->autoload = new AutoloadSchema($data->get('autoload', []));
		$this->require = new Map($data->get('require', []));
		$this->requireDev = new Map($data->get('require-dev', []));
		$this->extra = CollectionUtils::toMap($data->get('extra', []));
		
		if ($this->extra->has('keeko')) {
			$this->keeko = new KeekoSchema($this, $this->extra->get('keeko'));
		}
		
		$this->data = $data;
	}
	
	public function toArray() {
		$authors = [];
		foreach ($this->authors as $author) {
			$authors[] = $author->toArray();
		}
		
		$sort = [
			'name' => $this->fullName,
			'description' => $this->description,
			'type' => $this->type,
			'license' => $this->license,
			'authors' => $authors,
			'autoload' => $this->autoload->toArray(),
			'require' => $this->require->toArray(),
			'require-dev' => $this->requireDev->toArray(),
			'extra' => $this->collectionToArray($this->extra)
		];
		
		if ($this->keeko !== null) {
			$sort['extra']['keeko'] = $this->keeko->toArray();
		}
		
		$arr = array_merge($sort, $this->collectionToArray($this->data));
		
		return $arr;
	}
	
	private function collectionToArray(Collection $coll) {
		$arr = [];
		
		foreach ($coll as $k => $v) {
			if ($v instanceof Collection) {
				$v = $this->collectionToArray($v);
			}
			
			$arr[$k] = $v;
		}
		
		return $arr;
	}
	
	/**
	 * Sets the full name (vendor/name) of the package
	 *
	 * @param string $name
	 * @return $this
	 */
	public function setFullName($name) {
		$fullName = new Text($name);
		
		$this->fullName = $name;
		$this->name = $fullName->substring($fullName->indexOf('/') + 1)->toString();
		$this->vendor = $fullName->substring(0, $fullName->indexOf('/'))->toString();
		
		return $this;
	}
	
	/**
	 * Sets the vendor part of the package's full name
	 *
	 * @param string $name
	 * @return $this
	 */
	public function setVendor($vendor) {
		$this->setFullName($vendor . '/' . $this->name);
		return $this;
	}
	
	/**
	 * Sets the name part of the package's full name
	 *
	 * @param string $name
	 * @return $this
	 */
	public function setName($name) {
		$this->setFullName($name . '/' . $this->vendor);
		return $this;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getVendor() {
		return $this->vendor;
	}
	
	public function getFullName() {
		return $this->fullName;
	}
	
	public function getCanonicalName() {
		return str_replace('/', '.', $this->fullName);
	}

	/**
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 *
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 *
	 * @return the string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 *
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	/**
	 *
	 * @return the string
	 */
	public function getLicense() {
		return $this->license;
	}

	/**
	 *
	 * @param string $license
	 */
	public function setLicense($license) {
		$this->license = $license;
		return $this;
	}
	
	/**
	 * @return AutoloadSchema
	 */
	public function getAutoload() {
		return $this->autoload;
	}
	
	/**
	 * Returns the authors
	 *
	 * @return ArrayList<AuthorSchema>
	 */
	public function getAuthors() {
		return $this->authors;
	}

	/**
	 *
	 * @return Map
	 */
	public function getRequire() {
		return $this->require;
	}
	
	/**
	 *
	 * @return Map
	 */
	public function getRequireDev() {
		return $this->requireDev;
	}
	
	/**
	 * @return Map
	 */
	public function getExtra() {
		return $this->extra;
	}
	
	/**
	 * @return KeekoSchema
	 */
	public function getKeeko() {
		return $this->keeko;
	}
	
}
