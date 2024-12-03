<?php

namespace App\Form;

use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Service\FileUploadService;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;

class TaskType extends AbstractType
{
    public function __construct(private FileUploadService $uploadService)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('file', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Ajouter un fichier'
            ])
            ->add('slug', TextType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => TaskStatus::getChoices(),
                'choice_label' => function($choice, $key, $value) {
                    return $key; // Utilise la clé comme étiquette
                }
            ])
            ->add('description')
            ->add('estimates', TextType::class, [
                'label' => 'Estimation'
            ])
            ->add('dueDate', DateTimeType::class, [
                'label' => 'Date d\'échéance'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->attachTimestamps(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->handleUpload(...))
        ;
    }

    private function autoSlug(PreSubmitEvent $event) : void {
        $data = $event->getData();

        if(empty($data['slug'])) {
            $slugger = new AsciiSlugger();
            // Slugify title
            $data['slug'] = strtolower($slugger->slug($data['title']));
            $event->setData($data);
        }
    }

    private function handleUpload(PostSubmitEvent $event) : void {
        $task = $event->getData(); // L'objet Task
        $form = $event->getForm();

        // Vérifie si un fichier a été uploadé
        /** @var UploadedFile|null $file */
        $file = $form->get('file')->getData();
        if ($file) {
            // Utilise le service FileUploadService pour uploader le fichier
            $filename = $this->uploadService->upload($file);
            
            // Assigner le nom du fichier à une propriété de l'entité (par exemple, fileName)
            $task->setAttachments($filename);
        }
    
    }

    private function attachTimestamps(PostSubmitEvent $event) : void {
        $data = $event->getData();

        if(! ($data instanceof Task)) {
            return;
        }

        $data->setUpdatedAt(new DateTimeImmutable());

        if(!$data->getId()) {
            $data->setCreatedAt(new DateTimeImmutable());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
