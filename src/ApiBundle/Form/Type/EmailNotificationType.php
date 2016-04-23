<?php
namespace ApiBundle\Form\Type;

use PizzaBundle\Entity\EmailNotification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class EmailNotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('email', 'text');
        $builder->add('active');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', EmailNotification::class);
        $resolver->setDefault('csrf_protection', false);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return '';
    }
}