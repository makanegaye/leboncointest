<?php

namespace App\Controllers;

use App\Controllers\ControllerInterface;
use InvalidArgumentException;
use Exception;
use App\Models\ContactModel;

class ContactController extends MainController implements ControllerInterface
{
    /** @var int $userId */
    protected $userId;

    /**
     * ContactController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->userId = $_SESSION['auth']['id'];
        $this->loadModel('Contact');
    }

    /**
     * Affichage de la liste des contacts de l'utilisateur connecté
     */
    public function index()
    {
        $contacts = [];
        if (!empty($this->userId)) {
            $contacts = $this->Contact->getContactByUser($this->userId);
        }
        echo $this->twig->render('index.html.twig', ['contacts' => $contacts]);
    }

    /**
     * Ajout d'un contact
     */
    public function add()
    {
        $error = false;
        // Va permettre de personnaliser le message d'erreur affiché en cas de problème
        $error_details = "";
        if (!empty($_POST)) {
            try {
                $response = $this->sanitize($_POST);
                if ($response["response"]) {
                    $result = $this->Contact->create([
                        'nom' => $response['nom'],
                        'prenom' => $response['prenom'],
                        'email' => $response['email'],
                        'userId' => $this->userId
                    ]);
                    if ($result) {
                        header('Location: /index.php?p=contact.index');
                    }
                } else {
                    $error = true;
                    $error_details = $response["message"];
                }
            } catch (Exception $e) {
                $error_details = $e->getMessage();
            }
        }
        echo $this->twig->render('add.html.twig', ['error' => $error, 'error_details' => $error_details]);
    }

    /**
     * Modification d'un contact
     */
    public function edit()
    {
        $id = intval($_GET['id']);
        if (!empty($_POST)) {
            $response = $this->sanitize($_POST);
            if ($response["response"]) {
                $result = $this->Contact->update($id, [
                    'nom'    => $response['nom'],
                    'prenom' => $response['prenom'],
                    'email'  => $response['email'],
                    //'userId' => $this->userId
                ]);
                if ($result) {
                    header('Location: /index.php?p=contact.index');
                }
            } else {
                $error = true;
            }
        }
        if(! empty($id)) {
            $data = $this->Contact->findById($id);
            if($data === FALSE) {
                echo $this->twig->render('add.html.twig', ['error' => true]);
            } else {
                echo $this->twig->render('add.html.twig', ['data' => $data]);
            }
        }
    }

    /**
     * Suppression d'un contact
     */
    public function delete()
    {
        $result = $this->Contact->delete($_GET['id']);
        if ($result) {
            header('Location: /index.php?p=contact.index');
        }
    }

    /**
     * Permet de nettoyer les données reçues
     *
     * @param array $data
     * @return array
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function sanitize(array $data = []): array
    {
        $prenom = strtoupper($data['prenom']);
        $nom    = strtoupper($data['nom']);
        $email  = strtolower($data['email']);

        if (empty($nom)) {
            throw new Exception('Le nom est obligatoire');
        }

        if (empty($prenom)) {
            throw new Exception('Le prenom est obligatoire');
        }

        if (empty($email)) {
            throw new Exception('L\'email est obligatoire');
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
            throw new InvalidArgumentException('Le format de l\'email est invalide');
        }

        $isPalindrome = $this->apiClient(['name' => $nom, 'request' => 'palindrome']);
        $isEmail = $this->apiClient(['email' => $email, 'request' => 'email']);
        if ((!$isPalindrome->response) && $isEmail->response && $prenom) {
            return [
                'response' => true,
                'email'    => $email,
                'prenom'   => $prenom,
                'nom'      => $nom
            ];
        } else {
            $message = $isPalindrome->response ? $isPalindrome->message : '';
            $message.= !$isEmail->response ? ' ' . $isEmail->message : '';
            return [
                'response' => false,
                'message'  => $message
            ];
        }
    }

    /**
     * @see add()
     */
    public function create()
    {

    }
}