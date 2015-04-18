<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use AppBundle\Entity\File;
use AppBundle\Form\FileType;

/**
 * File controller.
 *
 * @Route("/file")
 */
class FileController extends Controller
{

    /**
     * Lists all File entities.
     *
     * @Route("/", name="file")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:File')->findReleased();
        $maps = $em->getRepository('AppBundle:Map')->findAll();

        return array(
            'entities' => $entities,
            'maps' => $maps,
        );
    }
    /**
     * Creates a new File entity.
     *
     * @Route("/{map}/create", name="file_create")
     * @Method("POST")
     * @Template("AppBundle:File:new.html.twig")
     */
    public function createAction(Request $request, $map)
    {
        $entity = new File();
        $user = $this->getUser();
        $entity->setUser($user);
        $form = $this->createCreateForm($entity, $map);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('myuploads'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a File entity.
     *
     * @param File $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(File $entity, $map)
    {
        $options = array('mapid' => $map);
        $form = $this->createForm(new FileType($options), $entity, array(
            'action' => $this->generateUrl('file_create', array('map' => $map)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new File entity.
     *
     * @Route("/{map}/new", name="file_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($map)
    {
        $entity = new File();
        $form   = $this->createCreateForm($entity, $map);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a File entity.
     *
     * @Route("/{id}/show", name="file_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Finds and displays a File.
     *
     * @Route("/{id}/get", name="file_get")
     *
     */
    public function getAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('AppBundle:File')->find($id);

        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $path = $helper->asset($file, 'file');
        $ext = strtolower($file->getExt());

        $response = new Response();

        $response->setStatusCode(200);
        switch ($ext) {
            case "png":
                $response->headers->set('Content-Type', 'image/png');
                break;
            case "gif":
                $response->headers->set('Content-Type', 'image/gif');
                break;
            case "jpeg":
                $response->headers->set('Content-Type', 'image/jpeg');
                break;
            case "jpg":
                $response->headers->set('Content-Type', 'image/jpeg');
                break;
            default:
                $response->headers->set('Content-Type', 'application/octet-stream');
        }
        $response->setContent(file_get_contents($path));

        return $response;
    }



    /**
     * Displays a form to edit an existing File entity.
     *
     * @Route("/{id}/edit", name="file_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:File')->find($id);
        $map = $entity->getLocation()->getMap()->getId();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $editForm = $this->createEditForm($entity, $map);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a File entity.
    *
    * @param File $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(File $entity, $map)
    {
        $options = array('mapid' => $map);
        $form = $this->createForm(new FileType($options), $entity, array(
            'action' => $this->generateUrl('file_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing File entity.
     *
     * @Route("/{id}", name="file_update")
     * @Method("PUT")
     * @Template("AppBundle:File:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:File')->find($id);
        $map = $entity->getLocation()->getMap()->getId();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity, $map);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('file'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a File entity.
     *
     * @Route("/{id}", name="file_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:File')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find File entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('myuploads'));
    }

    /**
     * Creates a form to delete a File entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('file_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Lists all Point entities.
     *
     * @Route("/myuploads", name="myuploads")
     * @Method("GET")
     * @Template("AppBundle:File:index.html.twig")
     */
    public function myFilesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $entities = $em->getRepository('AppBundle:File')->findMine($user);
        $maps = $em->getRepository('AppBundle:Map')->findAll();

        return array(
            'entities' => $entities,
            'maps' => $maps,
        );
    }

    /**
     * Lists all unreleased Point entities.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/upload_pending", name="upload_pending")
     * @Method("GET")
     * @Template("AppBundle:File:index.html.twig")
     */
    public function pendingAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:File')->findPending();
        $maps = $em->getRepository('AppBundle:Map')->findAll();

        return array(
            'entities' => $entities,
            'maps' => $maps,
        );
    }

    /**
     * Releases an existing Point entity.
     *
     * @Secure(roles="ROLE_ADMIN")
     * @Route("/{id}/release", name="file_release")
     * @Method("GET")
     * @Template("AppBundle:File:index.html.twig")
     */
    public function releaseAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:File')->find($id);
        $entity->setStatus(1);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $em->flush();

        return $this->redirect($this->generateUrl('file'));
    }


}
