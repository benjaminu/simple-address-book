<?php

namespace Xtreem\AddressBookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * AddressBook Form Type.
 *
 * @category  Form
 * @package   XtreemSimpleAddresBook
 * @author    Benjamin Ugbene <benjamin.ugbene@googlemail.com>
 * @copyright 2013 Benjamin Ugbene
 */
class AddressBookType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName',
                null,
                array(
                    'error_bubbling' => true,
                    'label'          => 'First name',
                )
            )
            ->add(
                'lastName',
                null,
                array(
                    'error_bubbling' => true,
                    'label'          => 'Last name',
                )
            )
            ->add(
                'address',
                'address_type',
                array('data_class' => 'Xtreem\AddressBookBundle\Entity\AddressBook')
            );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'Xtreem\AddressBookBundle\Entity\AddressBook',
            'csrf_protection' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'address_book_type';
    }
}