<?php

namespace Xtreem\AddressBookBundle\Service;

use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

/**
 * AddressBook Service.
 *
 * @category  Service
 * @package   XtreemSimpleAddresBook
 * @author    Benjamin Ugbene <benjamin.ugbene@googlemail.com>
 * @copyright 2013 Benjamin Ugbene
 */
class AddressBookService
{
    /**
     * Entity name.
     *
     * @var AddressBook
     */
    protected $entityName = 'XtreemAddressBookBundle:AddressBook';

    /**
     * Entity.
     *
     * @var Doctrine\Entity
     */
    protected $entity;

    /**
     * Entity manager.
     *
     * @var EntityManager
     */
    protected $em;

    /**
     * Controller instance.
     *
     * @var Controller
     */
    protected $controller;

    /**
     * Container.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Init action.
     *
     * @return void
     */
    public function init(ContainerInterface $container)
    {
        $this->setContainer($container);
        $this->em = $this->container->get('doctrine')->getManager();
    }

    /**
     * Sets controller instance.
     *
     * @param Controller $controller Controler instance.
     *
     * @return void
     */
    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Set container.
     *
     * @param ContainerInterface $container Container object.
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Find all entities.
     *
     * @return array
     */
    public function findAll()
    {
        return $this->em->getRepository($this->entityName)->findAll();
    }

    /**
     * Find entity.
     *
     * @return Doctrine\Entity
     */
    public function find($id)
    {
        $this->entity = $this->em->getRepository($this->entityName)->find($id);

        if (! $this->entity) {
            throw new NotFoundHttpException($this->entityName.' with ID: '.$id.' does not exist.');
        }


        return $this->entity;
    }

    /**
     * Get datatable for invoices.
     *
     * @return Datatable
     */
    public function initDatatable()
    {
        return $this->container->get('datatable')
            ->setEntity($this->entityName, 'entity')
            ->setFields(
                array(
                    'ID'           => 'entity.id',
                    'First name'   => 'entity.firstName',
                    'Last name'    => 'entity.lastName',
                    '_identifier_' => 'entity.id',
                )
            );
    }

    /**
     * Create new addressbook entity.
     *
     * @param Request $request Request.
     *
     * @return array
     */
    public function create(Request $request)
    {
        $entityName   = $this->getEntityName();
        $this->entity = new $entityName;
        $formType     = $this->getEntityFormTypeName();
        $form         = $this->createForm(new $formType, $this->entity);

        $form->bind($request);

        if ($form->isValid()) {
            $this->em->persist($this->entity);
            $this->em->flush();

            return array(
                'result' => true,
                'id'     => $this->entity->getId(),
            );
        }

        return array(
            'result' => false,
            'form'   => $form
        );
    }

    /**
     * Update addressbook record.
     *
     * @param integer $id      Record id.
     * @param Request $request Request.
     *
     * @return array
     */
    public function update($id, Request $request)
    {
        $this->entity = $this->find($id);
        $formType     = $this->getEntityFormTypeName();
        $form         = $this->createForm(new $formType, $this->entity);

        $form->bind($request);

        if ($form->isValid()) {
            $this->em->persist($this->entity);
            $this->em->flush();

            return array(
                'result' => true,
                'id'     => $this->entity->getId(),
            );
        }

        return array(
            'result' => false,
            'form'   => $form
        );
    }

    /**
     * Get form.
     *
     * @param integer $id Entity id to update.
     *
     * @return Form
     */
    public function getForm($id = null)
    {
        $entityName = $this->getEntityName();
        $entity     = new $entityName;
        if ($id) {
            $entity = $this->find($id);
        }

        $formType = $this->getEntityFormTypeName();

        return $this->createForm(new $formType, $entity);
    }

    /**
     * Get entity name.
     *
     * @return string
     */
    public function getEntityName()
    {
        return $this->em->getClassMetadata($this->entityName)->name;
    }

    /**
     * Get entity type name.
     *
     * @return string
     */
    public function getEntityFormTypeName()
    {
        return str_replace('Entity', 'Form', $this->getEntityName()).'Type';
    }

    /**
     * Create form.
     *
     * @param mixed $type    Type oject.
     * @param mixed $data    Data.
     * @param array $options Options.
     *
     * @return void
     */
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }

    /**
     * Returns a form's error messages as an array.
     *
     * @param Form $form Form from which error messages are to be retrieved.
     *
     * @return array
     */
    private function getErrorMessages(Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $message = $this->container->get('translator')->trans($error->getMessage(), array(), 'validators');

            $errors[$key] = $message;
        }

        if ($form->hasChildren()) {
            foreach ($form->getChildren() as $child) {
                if (! $child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
        }

        return $errors;
    }
}