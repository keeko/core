<?php
namespace keeko\core\action;

use keeko\core\action\ActionInterface;
use keeko\core\entities\Action;
use keeko\core\module\ModuleInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

trait ActionTrait {

	/* @var $action Action */
	protected $action;
	
	/* @var $module ModuleInterface */
	protected $module;
	
	protected $params = [];
	
	/* (non-PHPdoc)
	 * @see \keeko\core\action\ActionInterface::setParams()
	*/
	public function setParams($params) {
		$resolver = new OptionsResolver();
		$this->setDefaultParams($resolver);
		$this->params = $resolver->resolve($params);
	}
	
	abstract protected function setDefaultParams(OptionsResolverInterface $resolver);
	
	abstract protected function getData();
	
	/* (non-PHPdoc)
	 * @see \keeko\core\action\ActionInterface::setEntity()
	 */
	public function setEntity(Action $action) {
		$this->action = $action;
	}
	
	/* (non-PHPdoc)
	 * @see \keeko\core\action\ActionInterface::setModule()
	 */
	public function setModule(ModuleInterface $module) {
		$this->module = $module;
		$this->root = sprintf('%s/%s/templates/%s', KEEKO_PATH_MODULES, $module->getEntity()->getName(),
			$module->getKeeko()->getEntity()->getApplicationType()->getName());
	}

}