<?php

namespace App\Controller;



use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ConferenceController extends AbstractController
{
    private $twig;
    private $entityManager;

    public function __construct(Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="homepage")
     * @param ConferenceRepository $conferenceRepository
     * @param Environment $twig
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(ConferenceRepository $conferenceRepository,Environment $twig)
    {

        return new Response($twig->render('conference/index.html.twig',
                                    ['conferences'=>$conferenceRepository->findAll(),]));
    }

    /**
     * @Route("/conference/{slug}", name="conference")
     * @param Conference $conference
     * @param CommentRepository $commentRepository
     * @param Environment $twig
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function show(Conference $conference, CommentRepository $commentRepository, Environment $twig, Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $comment->setConference($conference);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return  $this->redirectToRoute('conference',['slug'=>$conference->getSlug()]);
        }

        $offset = max(0,$request->query->getInt('offset',0));
        $paginator = $commentRepository->getCommentPaginator($conference,$offset);
        return new Response($twig->render('conference/show.html.twig',['conference'=>$conference,
         'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' =>min(count($paginator),$offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form->createView(),
            ]));
    }
}
