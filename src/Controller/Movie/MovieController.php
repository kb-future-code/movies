<?php

declare(strict_types=1);

namespace App\Controller\Movie;

use App\Entity\User\User;
use App\Form\Movie\MovieSearchType;
use App\Model\Movie\MovieSearch;
use App\Service\OmdbApi\MovieManager;
use App\Service\Paginator\CustomPaginatorManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * List of a movies from omdbApi with search form.
     *
     * @Route("/movie/list/{page}", name="movie_list")
     *
     * @param Request $request
     * @param MovieManager $manager
     * @param CustomPaginatorManager $customPaginator
     * @param int|null $page
     * @return Response
     */
    public function listAction(
        Request $request, MovieManager $manager,
        CustomPaginatorManager $customPaginator,
        int $page = 1
    ): Response
    {
        $this->denyAccessUnlessGranted(User::MAIN_ROLE);

        $movies = [];

        $movieSearch = new MovieSearch();
        $form = $this->createForm(MovieSearchType::class, $movieSearch, ['method' => 'GET']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $manager->search($movieSearch->getTitle(), $movieSearch->getYear(), $movieSearch->getType(), $page);

            if (!in_array($result->code, [Response::HTTP_CREATED, Response::HTTP_OK]) or $result->body->Response === 'False') {
                $this->addFlash('danger', 'flash_msg.danger_could_not_find_movies');
            } else {
                $movies = $result->body->Search;
                $customPaginator->setPage($page);
                $customPaginator->setTotalResults((int) $result->body->totalResults);
            }
        }

        return $this->render('movie/list.html.twig', [
            'form' => $form->createView(),
            'movies' => $movies,
            'page' => $page,
            'paginator' => $customPaginator->getPaginator(),
        ]);
    }

    /**
     * View detailed information about specific movie.
     *
     * @Route("/movie/show/{id}", name="movie_show")
     *
     * @param MovieManager $manager
     * @param string $id
     * @return Response
     */
    public function showAction(MovieManager $manager, string $id): Response
    {
        $this->denyAccessUnlessGranted(User::MAIN_ROLE);

        $result = $manager->getById($id);

        if (!in_array($result->code, [Response::HTTP_CREATED, Response::HTTP_OK]) or $result->body->Response === 'False') {
            $this->addFlash('danger', 'flash_msg.danger_could_not_find_movies');
            $movie = null;
        } else {
            $movie = $result->body;
        }

        return $this->render('movie/show.html.twig', ['movie' => $movie,]);
    }
}
