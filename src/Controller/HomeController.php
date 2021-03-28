<?php

namespace  App\Controller;

use App\Entity\Mem;
use App\Entity\MemeTemplate;
use App\Entity\SearchData;
use App\Entity\User;
use App\Form\AddTemplateFormType;
use App\Form\FileUploadFormType;
use App\Form\SearchFormType;
use App\Repository\MemeTemplateRepository;
use App\Repository\MemRepository;
use App\Repository\UserRepository;
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
    private $userRepository;
    private $memRepository;

    public function __construct(Security $security, UserRepository $userRepository, MemRepository $memRepository)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->memRepository = $memRepository;
    }

    /**
     * @Route("/", name="app_workshop")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function home(Request $request): Response {
        $data = $request->get('data');
        $repository = $this -> getDoctrine() -> getRepository(MemeTemplate::class);
        $templates = $repository->findAll();
        $query = null;

        if ($data != null) {
            $form = $this->createForm(SearchFormType::class, $data);
            $form->handleRequest($request);
            $formData = ($form->getData());
            $query = $formData->getQ('q');
        }
        else $form = $this->createForm(SearchFormType::class, null);

        if ($query != null)
            if ($query != '') {
                dump($query);
                $filter = '%'.$query.'%';
                $templates = $repository->findByFilter($filter);
                dump($templates);
            }
        else {
            $templates = $repository->findAll();
        }
        return $this->render('home/home.html.twig', [
            'templates' => $templates,
            'gallery_templates' => '/images/templates/',
            'searchForm' => $form->createView()
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
        $fileName = substr(md5(time()), 0, 16).'.jpg';
        file_put_contents('../public/images/mems/'.$fileName, base64_decode($image[1]));

        $user = $this->userRepository->findOneByEmail($this->getUser()->getUsername());

        $mem = new Mem();
        $mem->setName('newMeme');
        $mem->setCreator($user);
        $mem->setMem($fileName);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($mem);
        $entityManager->flush();

        return $this->redirectToRoute('/');
    }

    /**
     * @Route("/gallery", name="app_gallery")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function gallery(Request $request): Response {
        $memes = $this->memRepository->findAll();
        return $this->render('home/gallery.html.twig', [
            'memes' => $memes,
            'gallery_memes' => '/images/mems/'
        ]);
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

    /**
     * @Route("/gallery/{id}", name="app_user_gallery")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function userGallery(Request $request): Response {
        $id = $request->request->get('id');
        $memes = $this->memRepository->findByCreatorId($id);
        return $this->render('home/user_gallery.html.twig', [
            'memes' => $memes,
            'gallery_memes' => '/images/mems/'
        ]);
    }
}