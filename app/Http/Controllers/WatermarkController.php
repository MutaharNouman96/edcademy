<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi; // The namespace used by FPDI 2.x

class WatermarkController extends Controller
{
    public function applyWatermark(Request $request)
    {
        // 1. Validate the upload
        $request->validate([
            'document' => 'required|mimes:pdf',
        ]);

        // 2. Path to Libraries (Manually require them)
        require_once(app_path('Libraries/fpdf/fpdf.php'));
        require_once(app_path('Libraries/fpdi/src/autoload.php'));

        $file = $request->file('document');
        $inputPath = $file->getRealPath();
        $watermarkPath = public_path('images/watermark.png');

        // 3. Start FPDI Logic
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($inputPath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($templateId);

            // Add page matching the original orientation/size
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            // 4. Place Watermark Image
            // We'll place it in the center. Adjust 100 (width) as needed.
            $wWidth = 100;
            $x = ($size['width'] - $wWidth) / 2;
            $y = ($size['height'] - 100) / 2;

            $pdf->Image($watermarkPath, $x, $y, $wWidth);
        }

        // 5. Return the PDF as a download
        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="watermarked.pdf"');
    }
}
