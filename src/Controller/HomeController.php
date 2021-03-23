<?php

namespace  App\Controller;

use App\Entity\MemeTemplate;
use App\Form\AddTemplateFormType;
use App\Form\FileUploadFormType;
use App\Repository\MemeTemplateRepository;
use http\Message\Body;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }
    /**
     * @Route("/", name="app_workshop")
     * @Method({"GET"})
     */
    public function home(): Response {
        $repository = $this -> getDoctrine() -> getRepository(MemeTemplate::class);
        $templates = $repository -> findAll();
        return $this->render('home/home.html.twig', [
            'templates' => $templates,
            'gallery_templates' => '/images/templates/'
        ]);
    }

    /**
     * @Route("/saveImage", name="app_save_image")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function saveImage(Request $request): Response {
        echo($request);

        $data = $request->getContent();
        $image = explode('base64,',$data);
        file_put_contents('img.png', base64_decode($image[1]));

        return $this->render('workshop/workshop.html.twig');
    }

    /**
     * @Route("/gallery", name="app_gallery")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function gallery(Request $request): Response {

        return $this->render('home/gallery.html.twig');
    }

    /**
     * @Route("/about", name="app_about")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function about(Request $request): Response {

        return $this->render('home/about.html.twig');
    }

    /**
     * @Route("/addNewTemplate", name="app_add_Template")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function addNewTemplate(Request $request): Response {

        $form = $this->createForm(AddTemplateFormType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->security->getUser();
            $data->setUploader($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirect('/');
        }
        return $this->render('workshop/addTemplate.html.twig', [
            'newTemplateForm'=> $form->createView()
        ]);
    }
}