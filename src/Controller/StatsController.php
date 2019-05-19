<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdherentRepository;
use App\Repository\LivreRepository;
class StatsController extends AbstractController
{
    /**
     * @Route("/stats", name="stats")
     */
    public function index()
    {
        return $this->render('stats/index.html.twig', [
            'controller_name' => 'StatsController',
        ]);
    }
    /**
     * Renvoie le nombre de pret par adherents
     *
     * @Route(
     *      path="apiPlatform/adherents/nbPretParAdherent",
     *      name="adherents_nbPrets",
     *      methods={"GET"}
     * )
     */
    public function statNbPretsParAdherent(AdherentRepository $repo)
    {
        $nbPretParAdherent = $repo->nbPretsParAdherent();
        return $this->json($nbPretParAdherent);
    }
    /**
     * Renvoie les 5 meilleurs livres
     * 
     * @Route(
     *      path="apiPlatform/livres/meilleurslivres",
     *      name="meilleurslivres",
     *      methods={"GET"}
     * )
     *
     */
    public function meilleurslivres(LivreRepository $repo)
    {
        $meilleurLivres = $repo->trouveMeilleursLivres();
        return $this->json($meilleurLivres);
    }
}