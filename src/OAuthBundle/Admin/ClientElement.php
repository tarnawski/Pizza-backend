<?php

namespace OAuthBundle\Admin;

use FOS\OAuthServerBundle\Util\Random;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use OAuth2\OAuth2;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientElement extends CRUDElement
{

    /**
     * @inheritdoc
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        $datagrid = $factory->createDataGrid($this->getId());

        $datagrid->addColumn('credentials', 'text', array(
            'label' => 'Credentials',
            'field_mapping' => array('publicId', 'secret'),
            'value_format' => function ($value) {
                return sprintf(
                    "<strong>ID:</strong> %s <br><strong>Secret:</strong> %s",
                    $value['publicId'],
                    $value['secret']
                );
            }
        ));

        $datagrid->addColumn('redirectUris', 'collection', array(
            'collection_glue' => '<br>'
        ));

        $datagrid->addColumn('action', 'action', array(
            'label' => 'Action',
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
        ));

        return $datagrid;
    }

    /**
     * @inheritdoc
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        $datasource = $factory->createDataSource('doctrine', array(
            'entity' => $this->getClassName()
        ), $this->getId());

        return $datasource;
    }

    /**
     * @inheritdoc
     */
    protected function initForm(FormFactoryInterface $factory, $data = null)
    {

        if (!$data) {
            $className = $this->getClassName();
            $data = new $className();
        }

        $form = $factory->create('form', $data, array(
            'data_class' => $this->getClassName()
        ));
        $form->add('secret', 'text', array(
            'required' => true
        ));

        $form->add('allowed_grant_types', 'choice', array(
            'choices' => array(
                OAuth2::GRANT_TYPE_AUTH_CODE => OAuth2::GRANT_TYPE_AUTH_CODE,
                OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS => OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS,
                OAuth2::GRANT_TYPE_REFRESH_TOKEN => OAuth2::GRANT_TYPE_REFRESH_TOKEN,
                OAuth2::GRANT_TYPE_USER_CREDENTIALS => OAuth2::GRANT_TYPE_USER_CREDENTIALS,
                OAuth2::GRANT_TYPE_IMPLICIT => OAuth2::GRANT_TYPE_IMPLICIT,
            ),
//            ),
            'multiple' => true,
            'label' => 'admin.client.allowed_grant_types',
        ));

        $form->add('redirectUris', 'collection', array(
            'type' => 'url',
            'allow_add' => true,
            'allow_delete' => true,

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
        return 'OAuthBundle\Entity\Client';
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return 'admin_client';
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'admin.client';
    }
}
