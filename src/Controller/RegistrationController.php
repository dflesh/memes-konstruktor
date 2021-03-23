<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class RegistrationController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/register", name="app_register")
     * @Method({"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('registration/register.html.twig', [
                'error' => '',
                'success' => ''
            ]);
        }

        $data = $request->request->get('form');
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        dump($data);
        dump($email);
        $user = $this->userRepository->findOneBy([
            'email' => $email
        ]);

        if (!is_null($user)) {
            return $this->render('registration/register.html.twig', [
                'error' => 'Account with such email already exist',
                'success' => ''
            ]);
        }

        // create new user()
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($passwordEncoder->encodePassword($user, $password));
        if (strlen($password) < 8) {
            return $this->render('registration/register.html.twig', [
                'error' => 'Password must contain 8 and more symbols.',
                'success' => ''
            ]);
        }
        $user->setRoles(['ROLE_USER']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->render('registration/register.html.twig', [
            'success' => 'Account created.',
            'error' => ''
        ]);
    }
}
