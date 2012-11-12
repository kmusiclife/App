<?php

namespace App\CommonBundle\EventListener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ControllerListener
{
    protected $container, $uploadKey;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->uploadKey = $this->container->get('session')->getFlash('uploadKey');
    }
    public function onKernelController(FilterControllerEvent $event){}
    public function prePersist(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();
                
        if (property_exists($entity, 'createdUser')) {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $entity->setCreatedUser($user);
        }
        if (property_exists($entity, 'createdAt')) {
            $entity->setCreatedAt(new \DateTime());
        }
        if (property_exists($entity, 'updatedAt')) {
            $entity->setUpdatedAt(new \DateTime());
        }
                
    }
    public function preUpdate(LifecycleEventArgs $args)
    {
        
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        
        $recompute = false;
        
        if (property_exists($entity, 'updatedAt'))
        {
            $entity->setUpdatedAt(new \DateTime());
            $recompute = true;
        }
        if (property_exists($entity, 'updatedUser'))
        {
            $user = $this->container->get('security.context')->getToken()->getUser();
            $entity->setUpdatedUser($user);
            $recompute = true;
        }
        if ($recompute)
        {
            $meta = $em->getClassMetadata(get_class($entity));
            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
        }
        
    }
    public function postPersist(LifecycleEventArgs $args){}
    public function postUpdate(LifecycleEventArgs $args){}
    public function postRemove(LifecycleEventArgs $args){}
    public function preRemove(LifecycleEventArgs $args){}
    public function postLoad(LifecycleEventArgs $args){}
    
}
