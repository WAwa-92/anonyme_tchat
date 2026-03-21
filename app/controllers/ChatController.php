<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Managers\MessageManager;
use App\Managers\SalonManager;

class ChatController
{
    private SalonManager $salonManager;
    private MessageManager $messageManager;

    public function __construct()
    {
        $this->salonManager = new SalonManager();
        $this->messageManager = new MessageManager();
    }

    public function home(): void
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

    public function salon(): void
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

    public function createSalon(): void
    {
        $name = trim((string) ($_POST['name'] ?? ''));

        if ($name !== '') {
            $newId = $this->salonManager->create($name);
            header('Location: index.php?route=salon&id=' . $newId);
            exit;
        }

        header('Location: index.php?route=home');
        exit;
    }

    public function sendMessage(): void
    {
        $salonId = (int) ($_POST['salon_id'] ?? 0);
        $content = trim((string) ($_POST['content'] ?? ''));

        if ($salonId > 0 && $content !== '') {
            $this->messageManager->create($salonId, $content);
        }

        header('Location: index.php?route=salon&id=' . $salonId);
        exit;
    }

    public function pinMessage(): void
    {
        $salonId = (int) ($_POST['salon_id'] ?? 0);
        $messageId = (int) ($_POST['message_id'] ?? 0);

        if ($salonId > 0 && $messageId > 0) {
            $this->messageManager->pinOne($salonId, $messageId);
        }

        header('Location: index.php?route=salon&id=' . $salonId);
        exit;
    }

    public function about(): void
    {
        $this->render('about/index', [
            'pageTitle' => 'À propos',
        ]);
    }

    private function render(string $view, array $data): void
    {
        extract($data);

        ob_start();
        include __DIR__ . '/../views/' . $view . '.phtml';
        $content = ob_get_clean();

        include __DIR__ . '/../views/layout.phtml';
    }
}
