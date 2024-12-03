<?php

namespace App\Controller\API;

use App\Entity\Matiere;
use App\Repository\MatiereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class MatiereApiController extends AbstractController
{
    // *[CREATE]*

    #[Route("/api/matieres", methods: "POST")]
    public function create(
        #[MapRequestPayload(serializationContext: ['groups' => ['matieres.create']])] Matiere $matiere,
        EntityManagerInterface $em
    ) {
        $em->persist($matiere);
        $em->flush();
        return $this->json($matiere, Response::HTTP_CREATED, [], [
            'groups' => ['matieres.show']
        ]);
    }

    // *[READ]*

    #[Route("/api/matieres", methods: "GET")]
    public function findAll(MatiereRepository $repository)
    {
        $matieres = $repository->findAll();
        return $this->json($matieres, Response::HTTP_OK, [], [
            'groups' => ['matieres.show']
        ]);
    }

    #[Route("/api/matieres/{id}", methods: "GET", requirements: ['id' => '\d+'])]
    public function findById(Matiere $matiere)
    {
        return $this->json($matiere, Response::HTTP_OK, [], [
            'groups' => ['matieres.show']
        ]);
    }

    // *[UPDATE]*

    #[Route("/api/matieres/{id}", methods: "PUT", requirements: ['id' => '\d+'])]
    public function update(
        int $id,
        Request $request,
        MatiereRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer,
    ) {
        // Récupérer la matière existante
        $matiere = $repository->find($id);
        if (!$matiere) {
            throw new NotFoundHttpException('Matière non trouvée');
        }

        // Mise à jour des données
        $updatedMatiere = $serializer->deserialize(
            $request->getContent(),
            Matiere::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $matiere, 'groups' => ['matieres.update']]
        );

        $em->persist($updatedMatiere);
        $em->flush();
        return $this->json($updatedMatiere, Response::HTTP_OK, [], [
            'groups' => ['matieres.show']
        ]);
    }

    // *[DELETE]*

    #[Route("/api/matieres/{id}", methods: "DELETE", requirements: ['id' => '\d+'])]
    public function delete(
        int $id,
        MatiereRepository $repository,
        EntityManagerInterface $em
    ) {
        $matiere = $repository->find($id);
        if (!$matiere) {
            throw new NotFoundHttpException('Matière non trouvée');
        }

        $em->remove($matiere);
        $em->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
