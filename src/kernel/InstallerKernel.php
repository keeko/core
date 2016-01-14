<?php
namespace keeko\core\kernel;

use keeko\core\installer\InstallerApplication;
use keeko\core\model\Application;
use Symfony\Component\HttpFoundation\Request;

class InstallerKernel extends AbstractKernel {

	public function main(array $options = []) {
		try {
			$steps = isset($options['steps']) ? $options['steps'] : ['setup'];
			
			$uri = '';
			$locale = InstallerApplication::DEFAULT_LOCALE;
			if (isset($steps['setup'])) {
				$uri = $options['uri'];
				$locale = isset($options['locale']) ? $options['locale'] : $locale;
			} else if (KEEKO_DATABASE_LOADED) {
				$prefs = $this->service->getPreferenceLoader()->getSystemPreferences();
				$uri = $prefs->getRootUrl();
			}
			
			$request = Request::create($uri);
			$request->setDefaultLocale('en');
			$request->setLocale($locale);

			$model = new Application();
			$model->setName('keeko/core/installer');
			
			$app = new InstallerApplication($model, $this->service, $options['io']);
			$this->app = $app;

			$response = $this->handle($app, $request);
			
			return $response;
		} catch (\Exception $e) {
			printf('<b>[%s] %s</b><pre>%s</pre>', get_class($e), $e->getMessage(), $e->getTraceAsString());
		}
	}
	
	/**
	 * Returns the application
	 *
	 * @return InstallerApplication
	 */
	public function getApplication() {
		return $this->app;
	}
}
