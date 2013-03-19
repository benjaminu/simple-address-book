<?php

namespace Xtreem\AddressBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\Query;

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
     * @var \Symfony\Component\HttpFoundation\Reques
     */
    protected $request;

    /**
     * Init action.
     *
     * @return void
     */
    protected function init()
    {
        $this->service = $this->get($this->serviceName);
        $this->request = $this->getRequest();

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

        return array();
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
                // Set flash message
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
                // Set flash message
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
        // confirm ajax  equest
        try {
            $this->service->delete($id, $this->request);
            $response = array();
        } catch (Exception $e) {
            $response = array();
        }

        return $response;
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
}