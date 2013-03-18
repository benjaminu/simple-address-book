<?php

namespace Xtreem\AddressBookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Address Form Type.
 *
 * @category  Form
 * @package   XtreemSimpleAddresBook
 * @author    Benjamin Ugbene <benjamin.ugbene@googlemail.com>
 * @copyright 2013 Benjamin Ugbene
 */
class AddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'line1',
                null,
                array(
                    'error_bubbling' => true,
                    'label'          => 'Address line 1',
                )
            )
            ->add(
                'line2',
                null,
                array(
                    'error_bubbling' => true,
                    'label'          => 'Address line 2',
                    'required'       => false,
                )
            )
            ->add(
                'city',
                null,
                array(
                    'error_bubbling' => true,
                    'label'          => 'Town',
                )
            )
            ->add(
                'postCode',
                null,
                array(
                    'error_bubbling' => true,
                    'label'          => 'Post code',
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('virtual' => true));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'address_type';
    }
}