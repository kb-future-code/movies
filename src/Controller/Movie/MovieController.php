<?php

declare(strict_types=1);

namespace App\Controller\Movie;

use App\Entity\User\FavouriteMovie;
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
     * @Route("/movie/list", name="movie_list")
     *
     * @param Request $request
     * @param MovieManager $manager
     * @param CustomPaginatorManager $customPaginator
     * @return Response
     */
    public function listAction(Request $request, MovieManager $manager, CustomPaginatorManager $customPaginator): Response
    {
        $this->denyAccessUnlessGranted(User::MAIN_ROLE);

        $movies = [];
        $favouriteMovies = [];

        $movieSearch = new MovieSearch();
        $form = $this->createForm(MovieSearchType::class, $movieSearch, ['method' => 'GET']);
        $form->handleRequest($request);

        $page = $request->query->getInt('page', 1);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $manager->search($movieSearch->getTitle(), $movieSearch->getYear(), $movieSearch->getType(), $page);

            if (!in_array($result->code, [Response::HTTP_CREATED, Response::HTTP_OK]) or $result->body->Response === 'False') {
                $this->addFlash('danger', 'flash_msg.danger_could_not_find_movies');
            } else {
                $movies = $result->body->Search;
                $customPaginator->setPage($page);
                $customPaginator->setTotalResults((int) $result->body->totalResults);
                $movieIds = $movieSearch->getApiMoviesIds($movies);
                $favouriteMovies = $this->getDoctrine()->getRepository(FavouriteMovie::class)->getByImdbIds($this->getUser(), $movieIds);
            }
        }

        return $this->render('movie/list.html.twig', [
            'form' => $form->createView(),
            'movies' => $movies,
            'page' => $page,
            'favouriteMovies' => $favouriteMovies,
            'paginator' => $customPaginator->getPaginator(),
        ]);
    }

    /**
     * View detailed information about specific movie.
     *
     * @Route("/movie/show/{movieId}", name="movie_show")
     *
     * @param MovieManager $manager
     * @param string $movieId
     * @return Response
     */
    public function showAction(MovieManager $manager, string $movieId): Response
    {
        $this->denyAccessUnlessGranted(User::MAIN_ROLE);

        $result = $manager->getById($movieId);
        $favouriteMovie = null;

        if (!in_array($result->code, [Response::HTTP_CREATED, Response::HTTP_OK]) or $result->body->Response === 'False') {
            $this->addFlash('danger', 'flash_msg.danger_could_not_find_movies');
            $movie = null;
        } else {
            $movie = $result->body;
            $favouriteMovie = $this->getDoctrine()->getRepository(FavouriteMovie::class)
                ->findOneBy(['user' => $this->getUser(), 'imdbMovieId' => $movie->imdbID]);
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'favouriteMovie' => $favouriteMovie,
        ]);
    }
}
