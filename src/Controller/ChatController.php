<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/', name: 'chats.')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ChatController extends Controller
{
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    #[Route(path: '/', name: 'index')]
    public function index(): Response
    {
        return $this->render('chats/index.html.twig');
    }

    #[Route(path: '/{id}', name: 'show')]
    public function show(string $id): Response
    {
        $chat = $this->messageRepository->getMessages($id);

        return $this->render('chats/show.html.twig', ['chat' => $chat]);
    }
}
