<?php

namespace App\Controller\User;

use App\Repository\InvoiceRepository;
use App\Repository\QuotationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_USER')]
class PdfController extends AbstractController
{
    #[Route('/generate-pdf', name: 'generate_pdf', methods: ['GET'])]
    public function generatePdf(Request $request, QuotationRepository $quotationRepository, InvoiceRepository $invoiceRepository): Response
    {
        $id = $request->query->get('id');
        $type = $request->query->get('type');

        if ($type === 'devis') {
            $forPdf = $quotationRepository->findOneById($id);
        } else {
            $forPdf = $invoiceRepository->findOneById($id);
        }

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        $html = $this->renderView('user/pdf/pdf_template.html.twig', [
            'forPdf' => $forPdf,
            'type' => $type,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
