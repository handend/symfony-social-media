<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use App\Entity\Comment;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class MicroPostController extends AbstractController
{
    #[Route('/micro/post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts, EntityManagerInterface $entityManager): Response
    {
        //$microPost = new MicroPost();
        //$microPost->setTitle('It comes from controller');
        //$microPost->setText('hehehe');
        //$microPost->setCreated(new DateTime());

        //$microPost = $posts-> find(4);
        //$microPost-> setTitle('Directly welcome!');

        //$posts-> add($microPost, true);
        //entityManager -> persist($microPost);
        //$entityManager -> flush();
        //dd($posts->findAll());
        $allPosts = $posts->findAllWithComments();
        return $this->render('micro_post/index.html.twig', [
            'posts' => $allPosts,
        ]);
    }

    #[Route('/micro/post/{id}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);

    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority:2)]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $microPost = new MicroPost();
        $form = $this -> createFormBuilder($microPost)
            ->add('title')
            ->add('text')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreated(new DateTime());
            $entityManager->persist($post);
            $entityManager->flush();

            // Add a flash message
            $this->addFlash('success', 'Your micro post have been added!');
            // Redirect to a different page
            return $this->redirectToRoute('app_micro_post');

        }
        return $this->render(
            'micro_post/add.html.twig',
            [
                'form' => $form,
            ]);

    }

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    public function edit(MicroPost $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this -> createFormBuilder($post)
            ->add('title')
            ->add('text')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $entityManager->persist($post);
            $entityManager->flush();

            // Add a flash message
            $this->addFlash('success', 'Your micro post has been updated!');
            // Redirect to a different page
            return $this->redirectToRoute('app_micro_post');

        }
        return $this->render(
            'micro_post/edit.html.twig',
            [
                'form' => $form,
                'post' => $post,
            ]);

    }

    #[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
    public function addComment(MicroPost $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();

        $form = $this -> createFormBuilder($comment)
            ->add('text')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment ->setPost($post);
            $entityManager->persist($comment);
            $entityManager->flush();

            // Add a flash message
            $this->addFlash('success', 'Your comment has been added!');
            // Redirect to a different page
            return $this->redirectToRoute(
                'app_micro_post_show',
                ['id' => $post->getId()]
            );

        }
        return $this->render(
            'micro_post/comment.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]);

    }

}
