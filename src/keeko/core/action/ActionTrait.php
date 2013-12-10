<?php
namespace keeko\core\action;

use keeko\core\action\ActionInterface;
use keeko\core\entities\Action;
use keeko\core\module\ModuleInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use keeko\core\entities\Module;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait ActionTrait {

	/**
	 * 
	 * @var Action
	 */
	protected $action;
	
	/**
	 * 
	 * @var Module
	 */
	protected $module;
	
	/**
	 * 
	 * @var \Twig_Environment
	 */
	protected $twig;
	
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
		$templatePath = sprintf('%s/%s/templates/%s', KEEKO_PATH_MODULES, $module->getEntity()->getName(),
			$module->getKeeko()->getEntity()->getApplicationType()->getName());
		
		$loader = new \Twig_Loader_Filesystem($templatePath);
		$this->twig = new \Twig_Environment($loader);
	}

}