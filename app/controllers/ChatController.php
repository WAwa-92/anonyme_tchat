<?php

namespace App\Controllers;

use App\Managers\MessageManager;
use App\Managers\SalonManager;

class ChatController
{
    private $salonManager;
    private $messageManager;

    public function __construct()
    {
        $this->salonManager = new SalonManager();
        $this->messageManager = new MessageManager();
    }

    public function home()
    {
        $salons = $this->salonManager->findAll();

        if (count($salons) === 0) {
            $this->render('chat/index', [
                'salons' => [],
                'activeSalon' => null,
                'messages' => [],
                'pageTitle' => 'Tchat anonyme',
            ]);
            return;
        }

        $activeSalon = $salons[0];
        $messages = $this->messageManager->findBySalon($activeSalon->getId());

        $this->render('chat/index', [
            'salons' => $salons,
            'activeSalon' => $activeSalon,
            'messages' => $messages,
            'pageTitle' => 'Tchat anonyme',
        ]);
    }

    public function salon()
    {
        $salonId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $salons = $this->salonManager->findAll();
        $activeSalon = $this->salonManager->findById($salonId);

        if ($activeSalon === null) {
            header('Location: index.php?route=home');
            exit;
        }

        $messages = $this->messageManager->findBySalon($salonId);

        $this->render('chat/index', [
            'salons' => $salons,
            'activeSalon' => $activeSalon,
            'messages' => $messages,
            'pageTitle' => 'Salon - ' . $activeSalon->getName(),
        ]);
    }

    public function createSalon()
    {
        $name = trim((string) ($_POST['name'] ?? ''));

        if ($name !== '') {
            $newId = $this->salonManager->create($name);
            $this->redirect('salon', ['id' => $newId]);
        }

        $this->redirect('home');
    }

    public function sendMessage()
    {
        $salonId = (int) ($_POST['salon_id'] ?? 0);
        $content = trim((string) ($_POST['content'] ?? ''));

        if ($salonId > 0 && $content !== '') {
            $this->messageManager->create($salonId, $content);
        }

        $this->redirect('salon', ['id' => $salonId]);
    }

    public function pinMessage()
    {
        $salonId = (int) ($_POST['salon_id'] ?? 0);
        $messageId = (int) ($_POST['message_id'] ?? 0);

        if ($salonId > 0 && $messageId > 0) {
            $this->messageManager->pinOne($salonId, $messageId);
        }

        $this->redirect('salon', ['id' => $salonId]);
    }

    public function about()
    {
        $this->render('about/index', [
            'pageTitle' => 'À propos',
        ]);
    }

    private function render($view, $data)
    {
        extract($data);
        $title = $pageTitle ?? 'Tchat anonyme';
        $template = __DIR__ . '/../views/' . $view . '.phtml';
        include __DIR__ . '/../views/layout.phtml';
    }

    private function redirect($route, $params = [])
    {
        $query = http_build_query(array_merge(['route' => $route], $params));
        header('Location: index.php?' . $query);
        exit;
    }
}
