<?php

namespace OAuthBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UserElement
 * @package OAuthBundle\Admin
 */
class UserElement extends CRUDElement
{
    /**
     * @inheritdoc
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        $datagrid = $factory->createDataGrid($this->getId());

        $datagrid->addColumn(
            'username',
            'text',
            array(
                'label' => 'admin.user.username'
            )
        );

        $datagrid->addColumn(
            'email',
            'text',
            array(
                'label' => 'admin.user.email',
            )
        );

        $datagrid->addColumn(
            'actions',
            'action',
            array(
                'label' => 'admin.user.action',
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

        $datasource->addField('email', 'text', 'like');

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

        $form->add('email', 'email');
        $form->add('username', 'text');
        $form->add(
            'enabled',
            'choice',
            array(
                'choices' => array(
                    '0' => 'form.choices.no',
                    '1' => 'form.choices.yes'
                )
            )
        );
        $form->add('plainPassword', 'text');
        $form->add(
            'locked',
            'choice',
            array(
                'choices' => array(
                    '0' => 'form.choices.no',
                    '1' => 'form.choices.yes'
                )
            )
        );
        $form->add('roles', 'choice', array(
            'multiple' => true,
            'choices' => \OAuthBundle\Entity\User::$ROLES
        ));

        return $form;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "allow_delete" => true,
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
        return 'OAuthBundle\Entity\User';
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return 'admin_user';
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'admin.user';
    }
}
