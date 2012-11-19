<?php

namespace App\UploadBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\UploadBundle\Entity\Image;
use App\UploadBundle\Form\ImageType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image controller.
 *
 */
class ImageController extends Controller
{

    public function ajaxAction(Request $request)
    {

        switch($request->get('mode'))
        {
            case 'list':

                $jsons = array();
                $em = $this->getDoctrine()->getManager();
                $entities = $em->getRepository('AppUploadBundle:Image')->findBy(
                    array('createdUser'=>$this->getUser()),
                    array('id'=>'desc')
                );
                $response = $this->render('AppUploadBundle:Image:jquery.upload.ajax.list.js.twig', array(
                    'entities' => $entities
                ));
                break;
                
            default:
                $response = $this->render('AppUploadBundle:Image:jquery.upload.ajax.js.twig');
                break;
                
        }
        $response->headers->set('Content-Type', "text/javascript");
        return $response;
    }
    public function jsAction(Request $request)
    {
        $response = $this->render('AppUploadBundle:Image:jquery.upload.main.js.twig');
        $response->headers->set('Content-Type', "text/javascript");
        return $response;
    }
    public function indexAction()
    {   
        return $this->render('AppUploadBundle:Image:index.html.twig');
    }
    public function listAction(Request $request)
    {
        
        $jsons = array();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppUploadBundle:Image')->findBy(
            array('createdUser'=>$this->getUser()),
            array('id'=>'desc')
        );
        return $this->render('AppUploadBundle:Image:result.json.twig', array(
            'entities' => $entities
        ));
        
    }
    public function createAction(Request $request)
    {
        
        $entities = $jsons = array();
        
        foreach($request->files as $files){
            
            foreach($files as $file){
                
                $entity  = new Image();
                
                // echo $file->getMimeType();
                // throw new Exception("File size limit not defined for upload");                
                
                $filename = uniqid().'.'.$file->guessExtension();
                $uploadDir = $entity->getUploadRootDir() . $entity->getUploadDir();
                
                $entity->setTitle($file->getClientOriginalName());
                $entity->setBody('');
                $entity->setType($file->getMimeType());
                $entity->setSize($file->getClientSize());
                $entity->setUploadpath($entity->getUploadDir().'/'.$filename);
                $entity->setCreatedAt(new \DateTime());
                $entity->setUpdatedAt(new \DateTime());
                $entity->setCreatedUser($this->getUser());

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                
                $em->flush();
                $file->move($uploadDir, $filename);
                array_push($entities, $entity);

            }
        }
        return $this->render('AppUploadBundle:Image:result.json.twig', array(
            'entities' => $entities
        ));
    }
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppUploadBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $editForm = $this->createForm(new ImageType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppUploadBundle:Image:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppUploadBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ImageType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('upload_edit', array('id' => $id)));
        }

        return $this->render('AppUploadBundle:Image:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function deleteAction(Request $request, $id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppUploadBundle:Image')->find(
            array('id'=>$id, 'createdUser'=>$this->getUser())
        );
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }
        unlink($entity->getUploadRootDir().$entity->getUploadpath());
        $em->remove($entity);
        $em->flush();
        
        return $this->render('AppUploadBundle:Image:delete.json.twig', array(
            'entity' => $entity
        ));
        
    }
    
}
