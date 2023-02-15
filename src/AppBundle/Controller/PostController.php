<?php

namespace AppBundle\Controller;

use AppBundle\Entity\contacto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class PostController extends Controller
{
    /**
     * @Route("/posts", name="View_all_posts")
     */
    public function indexAction(Request $request)
    {
        $contacto = $this->getDoctrine()->getRepository('AppBundle:contacto')->findAll();
        // replace this example code with whatever you need
        return $this->render('pages/index.html.twig', 
    [
        'contacto'=> $contacto
    ]
    );
    }

    /**
     * @Route("/create", name="create")
     */
    public function createPostAction(Request $request)
    {
        $contacto = new contacto();
        $form = $this->createFormBuilder($contacto)
            ->add('nombre', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'))) 
            ->add('telefono', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('correo', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('direccion', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))

            ->add('save', SubmitType::class, array('label' => 'Guardar Contacto', 'attr' => array('class' => "btn btn-outline-success", 'style' => 'margin-bottom:15px'))) 
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $nombre = $form['nombre']->getData();
            $telefono = $form['telefono']->getData();
            $correo = $form['correo']->getData();
            $direccion = $form['direccion']->getData();

            $contacto->setNombre($nombre);
            $contacto->setTelefono($telefono);
            $contacto->setCorreo($correo);
            $contacto->setDireccion($direccion);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($contacto);
            $em->flush();
            $this->addFlash(
                'notice',
                'contacto Added'
            );
            return $this->redirectToRoute('View_all_posts');
        }
        return $this->render('pages/form.html.twig', array(
            'contacto' => $contacto,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function editAction($id, Request $request){
    $contacto=$this->getDoctrine()->getRepository('AppBundle:contacto')->find($id);
    $contacto->setNombre($contacto->getNombre());
    $contacto->setTelefono($contacto->getTelefono());
    $contacto->setCorreo($contacto->getCorreo());
    $contacto->setDireccion($contacto->getDireccion());
    $form=$this->createFormBuilder($contacto)
    ->add('nombre', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'))) 
    ->add('telefono', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
    ->add('correo', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
    ->add('direccion', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))

    ->add('save', SubmitType::class, array('label' => 'Editar Contacto', 'attr' => array('class' => 'btn btn-outline-success', 'style' => 'margin-bottom:15px'))) 
    
        ->getForm();
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        $nombre = $form['nombre']->getData();
        $telefono = $form['telefono']->getData();
        $correo = $form['correo']->getData();
        $direccion = $form['direccion']->getData();
        $em = $this->getDoctrine()->getManager();
        $contacto=$em->getRepository('AppBundle:Contacto')->find($id);
        $contacto->setNombre($nombre);
        $contacto->setTelefono($telefono);
        $contacto->setCorreo($correo);
        $contacto->setDireccion($direccion);
        $em->flush();
        return $this->redirectToRoute('View_all_posts');
    }
    return $this->render('pages/edit.html.twig',
        array('form' => $form->createView()));
}

    /**
     * @Route("/view", name="view")
     */
    public function viewAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('pages/view.html.twig');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('AppBundle:contacto')->find($id);
        $em->remove($contacto);
        $em->flush();
        return $this->redirectToRoute('View_all_posts');
    }

        /**
     * Creates a form to delete a event entity.
     *
     * @param contacto $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(contacto $contacto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('events_delete', array('id' => $contacto->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}