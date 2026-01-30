<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;

class DocumentService
{
    /**
     * Applies a watermark and date to a PDF and returns the raw binary string.
     */
    public function generateWatermarkedPdf($tempFilePath, $watermarkPath = null)
    {

        if (is_null($watermarkPath)) {
            $watermarkPath = public_path('images/watermark.png');
        }

        // Require libraries if not using Composer
        require_once(app_path('Libraries/fpdf/fpdf.php'));
        require_once(app_path('Libraries/fpdi/src/autoload.php'));

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($tempFilePath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            // --- Position: Top Right ---
            $wWidth = 40;
            $margin = 10;
            $x = $size['width'] - $wWidth - $margin;
            $y = $margin;

            // --- Add Watermark ---
            // Ensure the PNG has transparency for the "opacity" effect
            $pdf->Image($watermarkPath, $x, $y, $wWidth);

            // --- Add Date Below Watermark ---
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetTextColor(128, 128, 128); // Grey color
            $pdf->SetXY($x, $y + ($wWidth * 0.5)); // Position text below image
            $pdf->Cell($wWidth, 10, time(), 0, 0, 'C');
        }

        return $pdf->Output('S'); // Return binary string
    }
}
