<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{
    private array $messages = [
        ['message'=> 'hello', 'created'=> '2025/07/28'],
        ['message'=> 'hi', 'created'=> '2025/06/28'],
        ['message'=> 'bye', 'created'=> '2024/05/28']
    ];


    #[Route('/', name: 'app_index')]
    public function index(UserProfileRepository $profiles, EntityManagerInterface $entityManager, CommentRepository $comments, MicroPostRepository $posts): Response
    {
//        $user = new User();
//        $user->setEmail('email@email.com');
//        $user->setPassword('12345678');
//
//
//        $profile = new UserProfile();
//        $profile->setUser($user);
//        $entityManager->persist($profile); // Hazırla
//        $entityManager->flush();           // Kaydet

//        $profile = $profiles->find(1);
//        if ($profile) {
//            $entityManager->remove($profile); // Silinmek üzere işaretle
//            $entityManager->flush();          // İşlemi veritabanına uygula (COMMIT)
//        }

//         $post = new MicroPost();
//         $post->setTitle('Hello');
//         $post->setText('Hello');
//         $post->setCreated(new DateTime());
//
//         $entityManager->persist($post);


          $post = $posts->find(25);
//
//        $comment = new Comment();
//        $comment ->setText('bi şeyleri yanlış yaptın da neyse');
//
//        $comment->setPost($post);
//
//        $entityManager->persist($comment);
//        $entityManager->flush();

        if (!$post) {
            throw $this->createNotFoundException('Aradığınız Post veritabanında bulunamadı! ID değişmiş olabilir.');
        }
        $comments = $post->getComment(); // Veya getComments() metodun adı neyse

        if (count($comments) > 0) {
            $comment = $comments[0]; // İlk yorumu al

            $post->removeComment($comment);
            $entityManager->persist($post);
            $entityManager->flush();

            return new Response('Yorum silindi!');
        } else {
            return new Response('Bu postun silinecek hiç yorumu yok.');
        }



        return $this->render(
            'hello/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' => 3,
            ]
        );
    }

    #[Route('/messages/{id<\d+>}', name: 'app_showOne')]
    public function showOne(int $id): Response
    {
        return $this->render(
            'hello/showOne.html.twig',
            [
                'message' => $this->messages[$id] ?? 'Message not found',
            ]
        );
        #return new Response($this->messages[$id]);

    }
}
