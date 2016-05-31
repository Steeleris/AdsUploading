<?php

namespace MainBundle\Form;

use MainBundle\Entity\Brand;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand', EntityType::class, array(
                'class' => 'MainBundle:Brand',
                'placeholder' => '',
                'choice_label' => 'title'
            ))
            ->add('model', EntityType::class, array(
                'class' => 'MainBundle:Model',
                'choice_label' => 'title',
                'placeholder' => '',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('m');
                    return $qb
                        ->where('m.brand = :brand')
                        ->setParameter('brand', null);
                }
            ))
            ->add('features', EntityType::class, array(
                'class' => 'MainBundle:Feature',
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true
            ))
            ->add('comment')
            ->add('submit', SubmitType::class)
        ;

        $formModifier = function (FormInterface $form, Brand $brand = null) {
            $brandId = null === $brand ? null : $brand->getId();

            $form->add('model', EntityType::class, array(
                'class' => 'MainBundle:Model',
                'choice_label' => 'title',
                'placeholder' => '',
                'query_builder' => function (EntityRepository $er) use ($brandId) {
                    $qb = $er->createQueryBuilder('m');
                    return $qb
                        ->where('m.brand = :brand')
                        ->setParameter('brand', $brandId);
                }
            ));
        };

        $builder->get('brand')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $brand = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $brand);
            }
        );
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Product'
        ));
    }
}
