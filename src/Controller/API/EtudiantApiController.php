<?php

namespace App\Controller\API;

use App\Annotation\TokenRequired;
use App\Entity\Etudiant;
use App\Repository\EtudiantRepository;
use App\Service\DeleteService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class EtudiantApiController extends AbstractController
{
    // *[CREATE]*

    #[Route("/api/etudiants", methods: "POST")]
    #[TokenRequired]
    public function create(
        #[MapRequestPayload(serializationContext: [
            'groups' => ['etudiants.create']
        ])] Etudiant $etudiant,
        EntityManagerInterface $em
    ) {
        $em->persist($etudiant);
        $em->flush();

        return $this->json($etudiant, Response::HTTP_CREATED, [], [
            'groups' => ['etudiants.show']
        ]);
    }

    // *[READ]*

    #[Route("/api/etudiants", methods: "GET")]
    //#[TokenRequired]
    public function findAll(EtudiantRepository $repository)
    {
        $etudiants = $repository->findAll();

        return $this->json($etudiants, Response::HTTP_OK, [], [
            'groups' => ['etudiants.show']
        ]);
    }

    #[Route("/api/etudiants/{id}", methods: "GET", requirements: ['id' => Requirement::DIGITS])]
    #[TokenRequired]
    public function findById(Etudiant $etudiant)
    {
        return $this->json($etudiant, Response::HTTP_OK, [], [
            'groups' => ['etudiants.show']
        ]);
    }

    // *[UPDATE]*

    #[Route("/api/etudiants/{id}", methods: "PUT")]
    #[TokenRequired]
    public function update(
        int $id,
        Request $request,
        EtudiantRepository $repository,
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ) {
        $etudiant = $repository->find($id);

        if (!$etudiant) {
            throw new NotFoundHttpException('Étudiant non trouvé');
        }

        $updatedEtudiant = $serializer->deserialize(
            $request->getContent(),
            Etudiant::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $etudiant, 'groups' => ['etudiants.update']]
        );

        $em->persist($updatedEtudiant);
        $em->flush();

        return $this->json($updatedEtudiant, Response::HTTP_OK, [], [
            'groups' => ['etudiants.show']
        ]);
    }

    // *[DELETE]*

    #[Route("/api/etudiants/{id}", methods: "DELETE")]
    #[TokenRequired]
    public function delete(
        int $id,
        DeleteService $deleteService,
        EtudiantRepository $repository
    ) {
        $etudiant = $repository->find($id);

        if (!$etudiant) {
            throw new NotFoundHttpException('Étudiant non trouvé');
        }

        $deleteService->softDelete($etudiant);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
