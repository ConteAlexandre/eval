<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\CreateFilmFormType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FilmController
 *
 * @Route("/api")
 */
class FilmController extends AbstractController
{
    /**
     * @Route("/film/create", name="create", methods={"POST"})
     *
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface     $validator
     *
     * @return JsonResponse
     */
    public function createAction(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $film = new Film();
        $form = $this->createForm(CreateFilmFormType::class, $film);
        $form->submit($data);

        $violation = $validator->validate($film);


        if (0 !== count($violation)) {
            foreach ($violation as $error) {
                return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        $entityManager->persist($film);
        $entityManager->flush();

        return new JsonResponse('The film has been created', Response::HTTP_OK);
    }

    /**
     * @Route("/list", name="list", methods={"GET"})
     *
     * @param FilmRepository $filmRepository
     *
     * @return JsonResponse
     */
    public function listAction(FilmRepository $filmRepository): JsonResponse
    {
        $films = $filmRepository->findAll();
        return new JsonResponse($films, Response::HTTP_OK);
    }

    /**
     * @Route("/show/{title}", name="show", methods={"GET"})
     *
     * @ParamConverter("film", options={"title" = "title"})
     *
     * @param FilmRepository $filmRepository
     * @param string         $title
     *
     * @return JsonResponse
     */
    public function showAction(FilmRepository $filmRepository, string $title): JsonResponse
    {
        $film = $filmRepository->findBy(['title' => $title]);

        if (!$film) {
            return new JsonResponse(sprintf("The film with %s not found", $title));
        } else {
            return new JsonResponse($film, Response::HTTP_OK);
        }
    }
}