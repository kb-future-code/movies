<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User\FavouriteMovie;
use App\Entity\User\User;
use App\Form\User\UserRegistrationType;
use App\Form\User\UserType;
use App\Service\OmdbApi\MovieManager;
use App\Service\Paginator\CustomPaginatorManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    const FAVOURITE_ELEMENTS_ON_PAGE = 5;

    /**
     * @Route("/user/login", name="user_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function loginAction(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted(User::MAIN_ROLE)) {
            throw $this->createAccessDeniedException('You are already logged in!');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/user/register", name="user_register")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->isGranted(User::MAIN_ROLE)) {
            throw $this->createAccessDeniedException('You are already logged in!');
        }

        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'flash_msg.success_account_create');

            return $this->redirectToRoute('app_app_index');
        }

        return $this->render('user/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/user/show", name="user_show")
     */
    public function showAction(): Response
    {
        $this->denyAccessUnlessGranted(User::MAIN_ROLE);

        return $this->render('user/show.html.twig', ['user' => $this->getUser()]);
    }

    /**
     * @Route("/user/edit", name="user_edit")
     *
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request): Response
    {
        $this->denyAccessUnlessGranted(User::MAIN_ROLE);

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'flash_msg.success_account_edit');

            return $this->redirectToRoute('app_app_index');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Add/remove favourite movie (depends if user had movie on favourite list).
     *
     * @Route("/user/favourite-movie/{movieId}", name="user_favouriteMovie")
     *
     * @param Request $request
     * @param MovieManager $manager
     * @param string $movieId
     * @return RedirectResponse
     */
    public function favouriteMovieAction(Request $request, MovieManager $manager, string $movieId): RedirectResponse
    {
        $this->denyAccessUnlessGranted(User::MAIN_ROLE);

        $result = $manager->getById($movieId);
        /** @var User $user */
        $user = $this->getUser();

        if (!in_array($result->code, [Response::HTTP_CREATED, Response::HTTP_OK]) or $result->body->Response === 'False') {
            $this->addFlash('danger', 'flash_msg.danger_could_not_find_movies');
            $movie = null;
        } else {
            $movie = $result->body;
        }

        if ($movie and $movie->imdbID) {
            $favouriteMovie = $this->getDoctrine()->getRepository(FavouriteMovie::class)->findOneBy([
                'user' => $user,
                'imdbMovieId' => $movie->imdbID,
            ]);

            if ($favouriteMovie) {
                $user->removeFavouriteMovie($favouriteMovie);
                $this->getDoctrine()->getManager()->remove($favouriteMovie);
                $this->addFlash('success', 'flash_msg.success_delete_from_favourite');
            } else {
                $favouriteMovie = new FavouriteMovie();
                $favouriteMovie->setImdbMovieId($movie->imdbID);
                $favouriteMovie->setUser($user);
                $this->getDoctrine()->getManager()->persist($favouriteMovie);
                $this->addFlash('success', 'flash_msg.success_add_to_favourite');
            }

            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * View user's favourite movies. (5 per page - for each movie need to send a request to get data)
     *
     * @Route("/user/my-favourite-movies", name="user_myFavouriteMovies")
     *
     * @param Request $request
     * @param MovieManager $manager
     * @param CustomPaginatorManager $customPaginator
     * @return JsonResponse
     */
    public function myFavouriteMoviesAction(Request $request, MovieManager $manager, CustomPaginatorManager $customPaginator): Response
    {
        $this->denyAccessUnlessGranted(User::MAIN_ROLE);

        /** @var User $user */
        $user = $this->getUser();

        $movies = [];

        $page = $request->query->getInt('page', 1);

        foreach ($user->getFavouriteMoviesSliced($page, self::FAVOURITE_ELEMENTS_ON_PAGE) as $i => $favouriteMovie) {
            $result = $manager->getById($favouriteMovie->getImdbMovieId());

            if (!in_array($result->code, [Response::HTTP_CREATED, Response::HTTP_OK]) or $result->body->Response === 'False') {
                $this->addFlash('danger', 'flash_msg.danger_could_not_find_movies');
            } else {
                $movies[] = $result->body;
                $customPaginator->setPage($page);
                $customPaginator->setElementsPerPage(5);
                $customPaginator->setTotalResults((int) $user->getFavouriteMovies()->count());
            }
        }

        return $this->render('user/favourite_movies.html.twig', [
            'movies' => $movies,
            'page' => $page,
            'paginator' => $customPaginator->getPaginator(),
        ]);
    }
}
