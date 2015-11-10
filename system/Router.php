<?php
namespace Skeleton\System;

/**
 * Class Router
 */
class Router
{
    /** @type array */
    private $uri = array();
    /** @type Registry */
    private $registry;

    /**
     * @param Registry $registry
     * @constructor
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;

        if (isset($_GET['uri'])) {
            $this->uri = explode('/', $_GET['uri']);
            $this->uri = array_filter($this->uri);
        }
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function render()
    {
        $controller = 'Index';
        $action = 'index';
        $admin_prefix = '';
        $url_position = 0;

        if (isset($this->uri[$url_position])) {
            $controller = $this->uri[$url_position];

            if ($controller == get_config('admin_path')) {
                $admin_prefix = get_config('admin_path') . '/';

                $url_position++;

                if (isset($this->uri[$url_position])) {
                    $controller = $this->uri[$url_position];
                } else {
                    $controller = 'Index';
                }
            }

            if (isset($this->uri[($url_position + 1)])) {
                $action = $this->uri[($url_position + 1)];
            }
        }

        $controller_file = ROOT_PATH . '/Controllers/' . $admin_prefix . $controller . 'Controller.php';

        if (file_exists($controller_file) === false) {
            throw new \Exception('Controller not found: ' . $controller_file);
        }

        include_once $controller_file;

        $className = $controller . 'Controller';

        $className = '\\Skeleton\\Controllers\\' . $className;

        $controller = new $className($this->registry);

        /** @type Application $controller */
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            $controller->index();
        }
    }
}