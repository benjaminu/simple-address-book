<?php

namespace Xtreem\AddressBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\QueryException;

/**
 * AddressBook Controller.
 *
 * @category  Controller
 * @package   XtreemSimpleAddresBook
 * @author    Benjamin Ugbene <benjamin.ugbene@googlemail.com>
 * @copyright 2013 Benjamin Ugbene
 */
class AddressBookController extends Controller
{
    /**
     * Flash message success type.
     *
     * @var string
     */
    const TYPE_FLASH_MESSAGE_SUCCESS = 'success';

    /**
     * Flash message error type.
     *
     * @var string
     */
    const TYPE_FLASH_MESSAGE_ERROR = 'error';

    /**
     * Flash message warning type.
     *
     * @var string
     */
    const TYPE_FLASH_MESSAGE_WARNING = 'warning';

    /**
     * Flash message info type.
     *
     * @var string
     */
    const TYPE_FLASH_MESSAGE_INFO = 'info';

    /**
     * Service name.
     *
     * @var string
     */
    protected $serviceName = 'address_book_service';

    /**
     * Service.
     *
     * @var \Xtreem\AddressBookBundle\Service\AddressBookService
     */
    protected $service;

    /**
     * Current request.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * Session object.
     *
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;


    /**
     * Init action.
     *
     * @return void
     */
    protected function init()
    {
        $this->service = $this->get($this->serviceName);
        $this->request = $this->get('request');
        $this->session = $this->get('session');

        $this->service->setController($this);
        $this->service->init($this->container);
    }

    /**
     * Controller resolver action after container set.
     *
     * @return void
     */
    public function resolve()
    {
        $this->init();
    }

    /**
     * Index action.
     *
     * @Route("/", name="home")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $this->service->initDatatable();

        $token = $this->getCsrfToken();

        return array('_token' => $token);
    }

    /**
     * Show action.
     *
     * @Route("/{id}/show", requirements={"id" = "\d+"}, name="show")
     * @Template()
     *
     * @return array
     */
    public function showAction($id)
    {
        return array('entity' => $this->service->find($id));
    }

    /**
     * Add action.
     *
     * @Route("/add", name="add")
     * @Template()
     *
     * @return array
     */
    public function addAction()
    {
        $form = $this->service->getForm();
        if ($this->isPost()) {
            $result = $this->service->create($this->request);
            if ($result['result']) {
                $this->setFlashMessage(
                    self::TYPE_FLASH_MESSAGE_SUCCESS,
                    'Address book record was successfully added!'
                );

                return $this->redirect(
                    $this->generateUrl('show', array('id' => $result['id']))
                );
            }

            $form = $result['form'];
        }

        return array(
            'form'   => $form->createView(),
            'errors' => $form->getErrors(),
        );
    }

    /**
     * Edit action.
     *
     * @param integer $id Entry id.
     *
     * @Route("/{id}/edit", requirements={"id" = "\d+"}, name="edit")
     * @Template()
     *
     * @return array
     */
    public function editAction($id)
    {
        $form = $this->service->getForm($id);

        if ($this->isPost()){
            $result = $this->service->update($id, $this->request);
            if ($result['result']) {
                $this->setFlashMessage(
                    self::TYPE_FLASH_MESSAGE_SUCCESS,
                    'Address book record was successfully updated!'
                );

                return $this->redirect(
                    $this->generateUrl('show', array('id' => $result['id']))
                );
            }

            $form = $result['form'];
        }

        return array(
            'entity'   => $this->service->find($id),
            'editForm' => $form->createView(),
            'errors'   => $form->getErrors()
        );
    }

    /**
     * Delete action.
     *
     * @param integer $id Entry id.
     *
     * @Route(
     *     "/{id}/delete",
     *     requirements={"id" = "\d+", "_format" = "json"},
     *     name="delete"
     * )
     * @Template()
     *
     * @return array
     */
    public function deleteAction($id)
    {
        if (! $this->request->isXmlHttpRequest()) {
            throw new HttpException(400, 'Bad request.');
        }

        if (! $this->isCsrfTokenValid($this->request->request->get('_token'))) {
            throw new HttpException(500, 'Invalid CSRF token.');
        }

        try {
            $this->service->delete($id, $this->request);
            $response = array(
                'result'  => true,
                'message' => 'Address book record was deleted successfully.'
            );
        } catch (QueryException $e) {
            $response = array(
                'result'  => false,
                'message' => 'An error occured while attempting to delete address book record ',
            );
        }

        return new JsonResponse($response);
    }

    /**
     * Datatable grid action.
     *
     * @Route(
     *     "/grid",
     *     requirements={"_format" = "json"},
     *     name="grid"
     * )
     *
     * @return Response
     */
    public function gridAction()
    {
        return $this->service->initDatatable()->execute(Query::HYDRATE_OBJECT);
    }

    /**
     * Is it Post.
     *
     * @return boolean
     */
    protected function isPost()
    {
        return $this->getRequest()->getMethod() == 'POST';
    }

    /**
     * Sets flash messages.
     *
     * @param string $type    Type of flash message.
     * @param string $message Message to be displayed.
     *
     * @return void
     */
    protected function setFlashMessage($type, $message)
    {
        $this->session->getFlashBag()->add($type, $message);
    }

    /**
     * Returns a CSRF token.
     *
     * @return void
     */
    protected function getCsrfToken()
    {
        $csrfProvider = $this->get('form.csrf_provider');

        $token = $csrfProvider->generateCsrfToken(
            $this->container->getParameter('intention')
        );

        return $token;
    }

    /**
     * Validates CSRF token.
     *
     * @param string $token Token to validate.
     *
     * @return void
     */
    protected function isCsrfTokenValid($token)
    {
        $csrfProvider = $this->get('form.csrf_provider');

        return $csrfProvider->isCsrfTokenValid(
            $this->container->getParameter('intention'),
            $token
        );
    }
}