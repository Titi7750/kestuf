<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class PublishController extends AbstractController
{
    #[Route('/publish', name: 'app_publish', methods: ['POST'])]
    public function publish(Request $request, HubInterface $hub): Response
    {
        $message = $request->get('message');

        $update = new Update(
            'http://example.com/chat',
            json_encode(['message' => $message])
        );

        $hub->publish($update);

        return $this->redirectToRoute('app_index');
    }
}
