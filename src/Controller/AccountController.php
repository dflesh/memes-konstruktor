<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\FileUploadFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/profile/edit", name="app_edit_user_profile")
     * @Method({"POST"})
     * @param Request $request
     * @return Response
     */
    public function editProfile(Request $request): Response {
        $user = $this->getUser();
        $user = $this->userRepository->findOneByEmail($user->getUsername());
        $user->setAvatar(null);
        $form = $this->createForm(FileUploadFormType::class, $user);
        if ($request->isMethod('GET')) {
            return $this->render('profile/edit_profile.html.twig', [
                'gallery_avatars' => '/images/avatars/',
                'fileUploadForm' => $form->createView(),
            ]);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $nickname = $user->getNickname();
            // $file stores the uploaded file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $user->getAvatar();

            // Generate a unique name for the file before saving it
            if ($file != null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                // Move the file to the directory where user avatars are stored
                $file->move(
                // This parameter should be configured
                    $this->getParameter('avatars'),
                    $fileName
                );

                // Update the 'avatar' property to store file name
                // instead of its contents
                $user->setAvatar($fileName);

            }

            if ($nickname != null)
                $user->setNickname($nickname);

            // ... persist the $user variable or any other work and redirect
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->render('profile/profile.html.twig', [
                'gallery_avatars' => '/images/avatars/',
                'user' => $user
            ]);
        }

        return $this->render('profile/profile.html.twig', [
            'gallery_avatars' => '/images/avatars/'
        ]);
    }

    /**
     * @Route("/profile/{id}", name="app_user_profile")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function getProfile(Request $request): Response {
        $id = $request->request->get('id');
        $user = $this->userRepository->findOneById($id);
        return $this->render('profile/profile.html.twig', [
            'gallery_avatars' => '/images/avatars/',
            'user' => $user
        ]);
    }
}