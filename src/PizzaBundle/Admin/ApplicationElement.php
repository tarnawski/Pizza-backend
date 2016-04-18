<?php

namespace PizzaBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use OAuthBundle\Entity\User;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ApplicationElement
 * @package PizzaBundle\Admin
 */
class ApplicationElement extends CRUDElement
{
    /**
     * @inheritdoc
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        $datagrid = $factory->createDataGrid($this->getId());

        $datagrid->addColumn(
            'id',
            'number',
            array(
                'label' => 'Id'
            )
        );

        $datagrid->addColumn(
            'name',
            'text',
            array(
                'label' => 'Name'
            )
        );
        $datagrid->addColumn(
            'homepage',
            'text',
            array(
                'label' => 'Homepage'
            )
        );


        $datagrid->addColumn(
            'actions',
            'action',
            array(
                'label' => 'Actions',
                'field_mapping' => array('id'),
                'actions' => array(
                    'edit' => array(
                        'url_attr' => array(
                            'class' => 'btn btn-warning btn-small-horizontal',
                            'title' => 'edit user'
                        ),
                        'content' => '<span class="icon-eject icon-white"></span>',
                        'route_name' => 'fsi_admin_crud_edit',
                        'parameters_field_mapping' => array(
                            'id' => 'id'
                        ),
                        'additional_parameters' => array(
                            'element' => $this->getId()
                        )
                    )
                )
            )
        );

        return $datagrid;
    }

    /**
     * @inheritdoc
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        $datasource = $factory->createDataSource(
            'doctrine',
            array(
                'entity' => $this->getClassName()
            ),
            $this->getId()
        );

        $datasource->addField('name', 'text', 'like');
        $datasource->addField('homepage', 'text', 'like');


        return $datasource;
    }

    /**
     * @inheritdoc
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $form = $factory->create(
            'form',
            $data,
            array(
                'data_class' => $this->getClassName()
            )
        );

        $form->add('name', 'text');
        $form->add('description', 'text');
        $form->add('homepage', 'text');
        $form->add(
            'demo',
            'choice',
            array(
                'choices' => array(
                    '0' => 'No',
                    '1' => 'Yes'
                )
            )
        );
        $form->add('users', 'entity', array(
            'multiple' => true,
            'class' => User::class,
            'choice_label' => 'username',
        ));
        $form->add('create_date', 'date', array(
            'data' => new \DateTime()
        ));

        return $form;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "allow_delete" => false,
            "allow_add" => true,
            "template_list" => "@FSiAdmin/CRUD/list.html.twig",
            "template_form" => "@FSiAdmin/Form/form.html.twig",
            "template_crud_list" => "@FSiAdmin/CRUD/list.html.twig",
            "template_crud_create" => "@FSiAdmin/CRUD/create.html.twig",
            "template_crud_edit" => "@FSiAdmin/CRUD/edit.html.twig",
        ));
    }

    /**
     * @inheritdoc
     */
    public function getClassName()
    {
        return 'PizzaBundle\Entity\Application';
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return 'admin_application';
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'admin.application';
    }
}
