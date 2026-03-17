<?php

namespace App\Support;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use TCPDF;

/**
 * Générateur d'exports au format TELC officiel
 * Crée des fichiers Excel et PDF avec les en-têtes exactes du formulaire TELC
 */
class TelcExportBuilder
{
    /**
     * En-têtes TELC officiels en allemand (comme sur l'image)
     */
    protected static $headers = [
        'Nachname',
        'Vorname',
        'E-Mail (optional)',
        "Geburtsdatum\nFormat: TT.MM.JJJJ",
        'Geburtsort',
        "Muttersprache\nBitte auswählen",
        "Geschlecht\nBitte auswählen",
        "Teilnehmernummer / Bognummer\n(Bitte stets Seitenzugabe einer\nvorhergehenden, zur digital Prüfung\nder Prüfungssoftware genannt)",
        "Vollpris\nBitte auswählen",
        "Geburtsland\nBitte auswählen",
        'Wohnort Stadt',
        "Wohnort Land\nBitte auswählen"
    ];

    /**
     * Crée un fichier XLSX au format TELC officiel
     */
    public static function buildExcel(array $rows): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Style pour l'en-tête (gris clair comme sur l'image TELC)
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 9,
                'name' => 'Arial',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'CCCCCC'], // Gris clair TELC
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Style pour les cellules de données
        $dataStyle = [
            'font' => [
                'size' => 10,
                'name' => 'Arial',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Largeurs des colonnes (ajustées pour le format TELC)
        $columnWidths = [
            'A' => 20,  // Nachname
            'B' => 18,  // Vorname
            'C' => 25,  // E-Mail
            'D' => 16,  // Geburtsdatum
            'E' => 15,  // Geburtsort
            'F' => 16,  // Muttersprache
            'G' => 12,  // Geschlecht
            'H' => 28,  // Teilnehmernummer
            'I' => 14,  // Vollpreis
            'J' => 14,  // Geburtsland
            'K' => 16,  // Wohnort Stadt
            'L' => 14,  // Wohnort Land
        ];

        // Appliquer les largeurs
        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Écrire les en-têtes
        $row = 1;
        foreach (self::$headers as $index => $header) {
            $col = chr(65 + $index); // A, B, C, etc.
            $sheet->setCellValue($col . $row, $header);
            $sheet->getStyle($col . $row)->applyFromArray($headerStyle);
        }
        
        // Hauteur de la ligne d'en-tête (plus grande pour les en-têtes multilignes)
        $sheet->getRowDimension(1)->setRowHeight(70);

        // Écrire les données
        $row = 2;
        foreach ($rows as $dataRow) {
            $colIndex = 0;
            foreach ($dataRow as $value) {
                $col = chr(65 + $colIndex);
                $sheet->setCellValue($col . $row, $value);
                $sheet->getStyle($col . $row)->applyFromArray($dataStyle);
                $colIndex++;
            }
            $sheet->getRowDimension($row)->setRowHeight(18);
            $row++;
        }

        // Geler la première ligne (en-tête)
        $sheet->freezePane('A2');

        // Générer le fichier
        $writer = new Xlsx($spreadsheet);
        
        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Crée un fichier PDF au format TELC officiel
     */
    public static function buildPdf(array $rows): string
    {
        // Créer un nouveau PDF en format paysage A4
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        
        // Configuration du document
        $pdf->SetCreator('TELC Preinscription');
        $pdf->SetAuthor('Tara Institut');
        $pdf->SetTitle('TELC Inscriptions');
        $pdf->SetSubject('Liste des inscriptions TELC');
        
        // Supprimer l'en-tête et le pied de page par défaut
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        
        // Marges
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 15);
        
        // Ajouter une page
        $pdf->AddPage();
        
        // Titre
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'TELC Anmeldungen / Inscriptions TELC', 0, 1, 'C');
        $pdf->Ln(5);
        
        // En-tête du tableau
        $pdf->SetFillColor(204, 204, 204); // Gris clair
        $pdf->SetFont('helvetica', 'B', 7);
        
        // Largeurs des colonnes pour PDF (en mm)
        $colWidths = [25, 20, 35, 18, 18, 18, 12, 35, 15, 18, 20, 18];
        $rowHeight = 6;
        
        // En-têtes simplifiés pour le PDF
        $pdfHeaders = [
            'Nachname',
            'Vorname',
            'E-Mail',
            'Geburtsdatum',
            'Geburtsort',
            'Muttersprache',
            'Geschlecht',
            'Teilnehmernr.',
            'Vollpris',
            'Geburtsland',
            'Wohnort Stadt',
            'Wohnort Land'
        ];
        
        // Dessiner l'en-tête
        foreach ($pdfHeaders as $i => $header) {
            $pdf->Cell($colWidths[$i], $rowHeight * 2, $header, 1, 0, 'C', true);
        }
        $pdf->Ln();
        
        // Données
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetFillColor(255, 255, 255);
        
        $fill = false;
        foreach ($rows as $dataRow) {
            $colIndex = 0;
            foreach ($dataRow as $value) {
                $pdf->Cell($colWidths[$colIndex], $rowHeight, $value, 1, 0, 'L', $fill);
                $colIndex++;
            }
            $pdf->Ln();
            $fill = !$fill; // Alternance de couleurs
        }
        
        // Pied de page
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 10, 'Generiert am: ' . now()->format('d.m.Y H:i'), 0, 0, 'L');
        
        // Générer le PDF
        return $pdf->Output('TELC_Inscriptions.pdf', 'S');
    }
}
