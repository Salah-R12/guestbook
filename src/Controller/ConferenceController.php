<?php

namespace App\Controller;



use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ConferenceController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig=$twig;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index(ConferenceRepository $conferenceRepository,Environment $twig)
    {

        return new Response($twig->render('conference/index.html.twig',
                                    ['conferences'=>$conferenceRepository->findAll(),]));
    }
    /**
     * @Route("/conference/{id}", name="conference")
     */
    public function show(Conference $conference, CommentRepository $commentRepository, Environment
    $twig,Request $request)
    {
        $offset = max(0,$request->query->getInt('offset',0));
        $paginator = $commentRepository->getCommentPaginator($conference,$offset);
        return new Response($twig->render('conference/show.html.twig',['conference'=>$conference,
         'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' =>min(count($paginator),$offset + CommentRepository::PAGINATOR_PER_PAGE),
            ]));
    }
}
