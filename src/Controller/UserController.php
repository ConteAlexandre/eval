<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 */
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     *
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface       $entityManager
     * @param Request                      $request
     * @param ValidatorInterface           $validator
     *
     * @return JsonResponse
     */
    public function newAction(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator)
    {
        $user = new User();

        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->submit($data);

        $violation = $validator->validate($user);

        if (0 !== count($violation)) {
            foreach ($violation as $error) {
                return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        $user->setPassword($encoder->encodePassword($user, $data['password']));

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse('User created', Response::HTTP_OK);
    }
}