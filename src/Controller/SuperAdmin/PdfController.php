<?php

namespace App\Controller\SuperAdmin;

use App\Repository\QuotationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_SUPERADMIN')]
class PdfController extends AbstractController
{
    #[Route('/generate-pdf', name: 'generate_pdf', methods: ['GET'])]
    public function generatePdf(Request $request, QuotationRepository $quotationRepository): Response
    {
        $id = $request->query->get('id');
        $quotation = $quotationRepository->findOneById($id);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        $html = $this->renderView('superadmin/pdf/pdf_template.html.twig', [
            'quotation' => $quotation,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
