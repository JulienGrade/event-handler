<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{


    /**
     * Permet d'afficher la page d'inscription
     * @param Request $request
     * @param UserPasswordHasherInterface $encoder
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $encoder,
        EntityManagerInterface $entityManager): Response
    {
        $notification = null;

        $user = new User();
        $registerForm = $this->createForm(RegistrationFormType::class, $user);
        $registerForm->handleRequest($request);

        if($registerForm->isSubmitted() && $registerForm->isValid()){
            $user = $registerForm->getData();
            $search_email = $entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if(!$search_email){
                $password = $encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('register_success', 'Vous êtes bien inscrit vous pouvez vous connecter');
                return $this->redirectToRoute('app_login');
            }else{
                $notification = "Cet email est déjà utilisé";
            }
        }

        return $this->render('register/index.html.twig', [
            'registerForm' => $registerForm->createView(),
            'notification' => $notification,
        ]);
    }
}
