<?php

namespace Xtreem\AddressBookBundle\Controller;

use Xtreem\AddressBookBundle\Controller\AddressBookController;
use JMS\DiExtraBundle\HttpKernel\ControllerResolver as BaseControllerResolver;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Controller resolver override.
 *
 * @category  Controller
 * @package   XtreemSimpleAddresBook
 * @author    Benjamin Ugbene <benjamin.ugbene@googlemail.com>
 * @copyright 2013 Benjamin Ugbene
 */
class ControllerResolver extends BaseControllerResolver
{
    /**
     * {@inheritDoc}
     */
    protected function createController($controller)
    {
        if (false === $pos = strpos($controller, '::')) {
            $count = substr_count($controller, ':');
            if (2 == $count) {
                // controller in the a:b:c notation then
                $controller = $this->parser->parse($controller);
                $pos = strpos($controller, '::');
            } elseif (1 == $count) {
                // controller in the service:method notation
                list($service, $method) = explode(':', $controller);

                return array($this->container->get($service), $method);
            } else {
                throw new \LogicException(sprintf('Unable to parse the controller name "%s".', $controller));
            }
        }

        $class = substr($controller, 0, $pos);
        $method = substr($controller, $pos+2);

        if (! class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $injector = $this->createInjector($class);
        $controller = call_user_func($injector, $this->container);

        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        if ($controller instanceof AddressBookController) {
            $controller->resolve();
        }

        return array($controller, $method);
    }
}