<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     * @return Response
     */
    public function listAction()
    {
        return $this->render('task/list.html.twig',[
            'tasks' => $this->getDoctrine()->getRepository('App:Task')->findBy(['isDone'=> 0])
            ]);
    }

    /**
     * @Route("/tasks/done", name="task_list_done")
     * @return Response
     */
    public function listDoneAction()
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $this->getDoctrine()->getRepository('App:Task')->findBy(['isDone'=> 1])
            ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @Route("/tasks/edit/{id}", name="task_edit")
     * @IsGranted("ROLE_USER")
     * @param Task $task
     * @param Request $request
     * @return Response
     */
    public function formAction(Task $task = null, Request $request)
    {
        if (!$task) {
            $task = new task();
            $task->setUser($this->getUser());
        }
        
        $edit = $task->getId() !== null;
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($task);
            $this->getDoctrine()->getManager()->flush();

            if ($edit) {
                $this->addFlash('success', 'La tâche a bien été modifiée.');
                return $this->redirectToRoute('task_list');
            }

            $this->addFlash('success', 'La tâche a bien été créée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/form.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
            'edit' => $edit
        ]);
    }

    /**
     * @Route("/tasks/toggle/{id}", name="task_toggle")
     * @IsGranted("ROLE_USER")
     * @param Task $task
     * @return Response
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/delete/{id}", name="task_delete")
     * @IsGranted("ROLE_USER")
     * @param Task $task
     * @param Request $request
     * @return Response
     */
    public function deleteTaskAction(Task $task, Request $request)
    {
        if((($this->getUser()->getRoles()[0] === 'ROLE_ADMIN') && ($task->getUser() === null)) || $task->getUser() === $this->getUser()) {
            if($this->isCsrfTokenValid('deletethattask'.$task->getId(), $request->get('token'))) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($task);
                $em->flush();
        
                $this->addFlash('success', 'La tâche a bien été supprimée.');
                return $this->redirectToRoute('task_list');
            } 
                $this->addFlash('error', 'Token Csrf non valide');
                return $this->redirectToRoute('task_list');
        } 
        $this->addFlash('error', 'Vous n\'avez pas les droits nécessaires afin de supprimer cette tâche.');
        return $this->redirectToRoute('task_list');
    }
}
