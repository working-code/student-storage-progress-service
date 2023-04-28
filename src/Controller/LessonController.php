<?php

namespace App\Controller;

use App\DTO\Builder\LessonDTOBuilder;
use App\DTO\LessonDTO;
use App\DTO\TaskDTO;
use App\Entity\Task;
use App\Exception\NotFoundException;
use App\Exception\ValidationException;
use App\Form\Type\CreateLessonType;
use App\Form\Type\DeleteLessonType;
use App\Form\Type\LessonType;
use App\Form\Type\UpdateLessonType;
use App\Service\LessonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lesson')]
class LessonController extends AbstractController
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly LessonService        $lessonService,
        private readonly LessonDTOBuilder     $lessonDTOBuilder,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    #[Route('/create', methods: ['GET', 'POST'])]
    public function store(Request $request): Response
    {
        $lessonDTO = (new LessonDTO())->setTasks([new TaskDTO(), new TaskDTO()]);

        $form = $this->formFactory->create(CreateLessonType::class, $lessonDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lessonDTO = $form->getData();
            $lesson = $this->lessonService->createLessonWithTasksFromLessonDTO($lessonDTO);

            return $this->redirectToRoute('lesson_show', ['id' => $lesson->getId()]);
        }

        return $this->renderForm('lesson.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @throws NotFoundException
     * @throws ValidationException
     */
    #[Route('/update/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'PATCH'])]
    public function update(Request $request, int $id): Response
    {
        $lesson = $this->getLessonById($id);
        $lessonDTO = $this->lessonDTOBuilder->buildFromEntity($lesson);
        $form = $this->formFactory->create(UpdateLessonType::class, $lessonDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lessonDTO = $form->getData();
            $lesson = $this->lessonService->updateLessonWithTasksByLessonDTO($lesson, $lessonDTO);

            return $this->redirectToRoute('lesson_show', ['id' => $lesson->getId()]);
        }

        return $this->renderForm('lesson.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @throws NotFoundException
     */
    private function getLessonById(int $id): Task
    {
        $lesson = $this->lessonService->findLessonById($id);

        if (!$lesson) {
            throw new NotFoundException('not found lesson');
        }

        return $lesson;
    }

    /**
     * @throws NotFoundException
     */
    #[Route('/{id}', name: 'lesson_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): Response
    {
        $lesson = $this->getLessonById($id);
        $lessonDTO = $this->lessonDTOBuilder->buildFromEntity($lesson);
        $form = $this->formFactory->create(LessonType::class, $lessonDTO);

        return $this->renderForm('lesson.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @throws NotFoundException
     */
    #[Route('/delete/{id}', name: 'lesson_delete', requirements: ['id' => '\d+'], methods: ['GET', 'DELETE'])]
    public function delete(int $id, Request $request): Response
    {
        $lesson = $this->getLessonById($id);
        $lessonDTO = $this->lessonDTOBuilder->buildFromEntity($lesson);
        $form = $this->formFactory->create(DeleteLessonType::class, $lessonDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->lessonService->deleteLessonWithTasks($lesson);

            return $this->redirectToRoute('lesson_delete', ['id' => $id]);
        }

        return $this->renderForm('lesson.html.twig', [
            'form' => $form
        ]);
    }
}
