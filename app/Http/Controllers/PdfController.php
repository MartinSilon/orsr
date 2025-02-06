<?php

namespace App\Http\Controllers;

use App\Models\Konatelia;
use PDF;
use setasign\Fpdi\Fpdi;
use Carbon\Carbon;

class PdfController extends Controller
{


    public function generateCompanyPdfs()
    {
        ini_set('memory_limit', '1024M');

        $pdfPath = public_path('pdfs');
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0777, true);
        }

        $pdfFiles = $this->generateIndividualPdfs($pdfPath);
        $mergedPdfFileName = 'konatelia-' . Carbon::now()->format('Y-m-d') . '.pdf';
        $mergedPdfPath = $this->mergePdfs($pdfFiles, $pdfPath, $mergedPdfFileName);

        $pdfFileUrl = asset('pdfs/' . $mergedPdfFileName);
        return view('download-redirect', compact('pdfFileUrl'));
    }

    /**
     * Generuje jednotlivé PDF súbory a vracia ich zoznam.
     */
    private function generateIndividualPdfs($pdfPath)
    {
        $konatelia = Konatelia::all();
        $pdfFiles = [];

        foreach ($konatelia as $konatel) {
            $fileName = $pdfPath . '/konatel-' . $konatel->id . '.pdf';
            $singlePdf = PDF::loadView('pdf/company', compact('konatel'));
            $singlePdf->save($fileName);
            $pdfFiles[] = $fileName;

            // Zmazanie záznamu z databázy
            $konatel->delete();
        }

        return $pdfFiles;
    }

    /**
     * Spojí všetky PDF súbory do jedného veľkého PDF a vymaže jednotlivé súbory.
     */
    private function mergePdfs($pdfFiles, $pdfPath, $mergedPdfFileName)
    {
        $mergedPdfPath = $pdfPath . '/' . $mergedPdfFileName;
        $pdf = new FPDI();

        foreach ($pdfFiles as $file) {
            $pageCount = $pdf->setSourceFile($file);
            for ($i = 1; $i <= $pageCount; $i++) {
                $tplIdx = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($tplIdx);
            }
        }

        $pdf->Output($mergedPdfPath, 'F');

        foreach ($pdfFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        return $mergedPdfPath;
    }


}
