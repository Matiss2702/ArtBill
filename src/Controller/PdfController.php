<?php

namespace App\Controller;

use App\Repository\InvoiceRepository;
use App\Repository\QuotationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

        $path = $this->getParameter('kernel.project_dir') . '/public/assets/images/logo.png';
        $typePath = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logo = 'data:image/' . $typePath . ';base64,' . base64_encode($data);

        $dompdf = new Dompdf($options);

        $html = $this->renderView('_partials/_pdf_generate.html.twig', [
            'forPdf' => $forPdf,
            'type' => $type,
            'logo' => $logo,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $idPartiel = substr($id, 0, 5);

        $pdfName = $idPartiel . '_' . $type . '.pdf';

        $dompdf->stream($pdfName, array('Attachment' => 0));

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    #[Route('/preview-pdf/', name: 'preview_pdf', methods: ['GET'])]
    public function previewPdf(Request $request, QuotationRepository $quotationRepository, InvoiceRepository $invoiceRepository): Response
    {
        $id = $request->query->get('id');
        $type = $request->query->get('type');

        if ($type === 'devis') {
            $forPdf = $quotationRepository->findOneById($id);
        } else {
            $forPdf = $invoiceRepository->findOneById($id);
        }

        return $this->render('_partials/_pdf_template.html.twig', [
            'forPdf' => $forPdf,
            'type' => $type,
        ]);
    }
}
