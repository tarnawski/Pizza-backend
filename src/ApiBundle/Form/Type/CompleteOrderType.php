<?php
namespace ApiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use PizzaBundle\Entity\Order;

class CompleteOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('first_name', 'text');
        $builder->add('last_name', 'text');
        $builder->add('email', 'text');
        $builder->add('phone', 'text');
        $builder->add('address', 'text');
        $builder->add('description', 'text');
        $builder->add('item');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', 'ApiBundle\Model\CompleteOrder');
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