<?php

namespace App\MessageHandler;
use App\Message\CommentMessage;
use App\Notification\CommentReviewNotification;
use App\Repository\CommentRepository;
use App\SpamChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use Psr\Log\LoggerInterface;

class CommentMessageHandler implements MessageHandlerInterface
{
    private $spamChecker;
    private $entityManager;
    private $commentRepository;
    private $bus;
    private $workflow;
    private $logger;
    private $notifier;
    private $adminEmail;
    public function __construct(EntityManagerInterface $entityManager, SpamChecker $spamChecker,
                                CommentRepository $commentRepository, MessageBusInterface $bus,
                                WorkflowInterface $commentStateMachine, LoggerInterface $logger = null,
                                MailerInterface $mailer, string $adminEmail,NotifierInterface $notifier)
    {
        $this->entityManager = $entityManager;
        $this->spamChecker = $spamChecker;
        $this->commentRepository = $commentRepository;
        $this->bus = $bus;
        $this->workflow = $commentStateMachine;
        $this->logger = $logger;
        $this->notifier = $notifier;

    }

    public function __invoke(CommentMessage $message)
    {
        $comment = $this->commentRepository->find($message->getId());
        if (!$comment) {
            return;
        }
        if ($this->workflow->can($comment, 'accept')) {
            $score = $this->spamChecker->getSpamScore($comment, $message->getContext());
            $transition = 'accept';
            if (2 === $score) {
                $transition = 'reject_spam';
            } elseif (1 === $score) {
                $transition = 'might_be_spam';
            }
            $this->workflow->apply($comment, $transition);
            $this->entityManager->flush();

            $this->bus->dispatch($message);

        } elseif ($this->workflow->can($comment, 'publish') || $this->workflow->can($comment, 'publish_ham')) {
            //$this->workflow->apply($comment, $this->workflow->can($comment,'publish') ? 'publish' : 'publish_ham');
            //$this->entityManager->flush();
            $notification = new CommentReviewNotification($comment, $message->getReviewUrl());
            $this->notifier->send($notification, ...$this->notifier->getAdminRecipients());
        } elseif ($this->logger) {
            $this->logger->debug('Dropping comment message', ['comment' =>$comment->getId(), 'state' => $comment->getState()]);
        }
    }
}