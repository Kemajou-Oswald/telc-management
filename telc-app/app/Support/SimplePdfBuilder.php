<?php

namespace App\Support;

/**
 * SimplePdfBuilder — génère un PDF A4 paysage avec la mise en forme officielle TELC :
 *  - Ligne 1 : bandeau rouge clair, texte rouge foncé gras
 *  - Ligne 2 : en-têtes jaune/orange, texte brun gras, centré, wrap
 *  - Données  : texte noir, bordures fines, lignes alternées blanches
 *
 * Usage :
 *   $pdf = SimplePdfBuilder::buildTelc($rows);
 *   // $rows = tableau de tableaux indexés, 14 colonnes dans l'ordre TELC
 */
class SimplePdfBuilder
{
    // ── Couleurs (R G B en 0-1) ───────────────────────────────────────────────
    private const RED_DARK   = [0.612, 0.000, 0.024]; // #9C0006
    private const RED_LIGHT  = [1.000, 0.780, 0.808]; // #FFC7CE
    private const ORANGE_BG  = [1.000, 0.902, 0.600]; // #FFE699
    private const ORANGE_TXT = [0.498, 0.376, 0.000]; // #7F6000
    private const BLACK      = [0.000, 0.000, 0.000];
    private const WHITE      = [1.000, 1.000, 1.000];
    private const BORDER     = [0.000, 0.000, 0.000];

    // ── Mise en page A4 paysage (points) ─────────────────────────────────────
    private const PAGE_W  = 841.89;
    private const PAGE_H  = 595.28;
    private const MARGIN  = 20.0;

    // ── En-têtes TELC officiels ───────────────────────────────────────────────
    private static function telcHeaders(): array
    {
        return [
            "Nachname",
            "Vorname",
            "E-Mail\n(optional)",
            "Geburtsdatum\nFormat:\nTT.MM.JJJJ",
            "Geburtsort",
            "Muttersprache\nBitte\nauswählen",
            "Geschlecht\nBitte\nauswählen",
            "Teilnehmernummer /\nBogennummer\nBitte stets 7- oder\n8-stellig angeben.",
            "Teilprüfung\n(optionale\nEingabe)",
            "Vorkommnisse\nBitte\nauswählen",
            "Geburtsland\nBitte\nauswählen",
            "Wohnort\nStadt",
            "Wohnort Land\nBitte\nauswählen",
            "Nur für DTZ / DTB\nBAMF-\nKennnziffer",
        ];
    }

    // ── Largeurs relatives des colonnes (proportionnelles) ────────────────────
    private static function colWidths(): array
    {
        // En mm — seront mis à l'échelle pour remplir la page
        return [22, 22, 36, 22, 20, 22, 18, 36, 20, 20, 22, 20, 22, 28];
    }

    // ── Point d'entrée principal ──────────────────────────────────────────────
    public static function buildTelc(array $rows): string
    {
        return self::render($rows);
    }

    // ── Compatibilité avec l'ancien build(array $lines) ───────────────────────
    public static function build(array $lines): string
    {
        // Détection : si c'est un tableau de tableaux → mode TELC
        if (! empty($lines) && is_array($lines[0])) {
            return self::buildTelc($lines);
        }
        // Sinon fallback texte brut
        return self::buildLegacy($lines);
    }

    // ── Rendu PDF TELC complet ────────────────────────────────────────────────
    private static function render(array $rows): string
    {
        $pageW  = self::PAGE_W;
        $pageH  = self::PAGE_H;
        $margin = self::MARGIN;

        // Largeurs colonnes mises à l'échelle
        $rawWidths = self::colWidths();
        $rawTotal  = array_sum($rawWidths);
        $available = $pageW - 2 * $margin;
        $colWidths = array_map(function($w) use ($available, $rawTotal) { return $w * $available / $rawTotal; }, $rawWidths);

        $headers = self::telcHeaders();
        $stream  = '';

        // ── Police et couleurs de base ────────────────────────────────────────
        $stream .= "BT ET\n"; // reset

        // ── Ligne 1 : bandeau avertissement ──────────────────────────────────
        $bannerH = 20;
        $bannerY = $pageH - $margin - $bannerH;
        $bannerX = $margin;
        $bannerW = $available;

        // Fond rouge clair
        $stream .= self::setFillColor(self::RED_LIGHT);
        $stream .= self::rect($bannerX, $bannerY, $bannerW, $bannerH, true, false);
        // Bordure
        $stream .= self::setStrokeColor(self::BORDER);
        $stream .= self::setLineWidth(0.5);
        $stream .= self::rect($bannerX, $bannerY, $bannerW, $bannerH, false, true);

        // Texte avertissement
        $warnText = 'Teilnehmende:    Bitte achten Sie darauf, alle Felder auszufüllen. '
            . 'Sie finden in der telc Community Hinweise zum Upload und zur Form des Templates.';
        $stream .= self::setFillColor(self::RED_DARK);
        $stream .= self::drawText($warnText, $bannerX + 4, $bannerY + 6, 'Helvetica-Bold', 8);

        // ── Ligne 2 : en-têtes ────────────────────────────────────────────────
        $headerH = 52; // hauteur pour 4 lignes de texte wrap
        $headerY = $bannerY - $headerH - 1;

        $xCursor = $margin;
        foreach ($colWidths as $ci => $cw) {
            // Fond orange
            $stream .= self::setFillColor(self::ORANGE_BG);
            $stream .= self::rect($xCursor, $headerY, $cw, $headerH, true, false);
            // Bordure
            $stream .= self::setStrokeColor(self::BORDER);
            $stream .= self::rect($xCursor, $headerY, $cw, $headerH, false, true);

            // Texte en-tête (multi-ligne, centré)
            $lines = explode("\n", $headers[$ci]);
            $lineH = 8.5;
            $totalTextH = count($lines) * $lineH;
            $textStartY = $headerY + ($headerH + $totalTextH) / 2 - $lineH;

            $stream .= self::setFillColor(self::ORANGE_TXT);
            foreach ($lines as $li => $line) {
                $lineY = $textStartY - $li * $lineH;
                $stream .= self::drawTextCentered(
                    self::encodeText($line),
                    $xCursor,
                    $cw,
                    $lineY,
                    'Helvetica-Bold',
                    6.5
                );
            }

            $xCursor += $cw;
        }

        // ── Lignes de données ─────────────────────────────────────────────────
        $rowH  = 14;
        $rowY  = $headerY - $rowH;
        $maxRows = max(count($rows), 15); // au moins 15 lignes vides

        for ($ri = 0; $ri < $maxRows; $ri++) {
            $row      = $rows[$ri] ?? [];
            $xCursor  = $margin;

            // Fond blanc + bordures
            foreach ($colWidths as $ci => $cw) {
                $stream .= self::setFillColor(self::WHITE);
                $stream .= self::rect($xCursor, $rowY, $cw, $rowH, true, false);
                $stream .= self::setStrokeColor(self::BORDER);
                $stream .= self::rect($xCursor, $rowY, $cw, $rowH, false, true);

                $cellValue = isset($row[$ci]) ? self::encodeText((string) $row[$ci]) : '';
                if ($cellValue !== '') {
                    $stream .= self::setFillColor(self::BLACK);
                    $stream .= self::drawText($cellValue, $xCursor + 2, $rowY + 4, 'Helvetica', 6.5);
                }

                $xCursor += $cw;
            }

            $rowY -= $rowH;

            // Nouvelle page si nécessaire
            if ($rowY < $margin && $ri < $maxRows - 1) {
                $stream .= "showpage\n";
                $rowY = $pageH - $margin - $rowH;
            }
        }

        // ── Construction PDF brut ─────────────────────────────────────────────
        return self::assemblePdf($stream, $pageW, $pageH);
    }

    // ── Helpers graphiques ────────────────────────────────────────────────────

    private static function setFillColor(array $rgb): string
    {
        return sprintf("%.3f %.3f %.3f rg\n", $rgb[0], $rgb[1], $rgb[2]);
    }

    private static function setStrokeColor(array $rgb): string
    {
        return sprintf("%.3f %.3f %.3f RG\n", $rgb[0], $rgb[1], $rgb[2]);
    }

    private static function setLineWidth(float $w): string
    {
        return sprintf("%.2f w\n", $w);
    }

    private static function rect(float $x, float $y, float $w, float $h, bool $fill, bool $stroke): string
    {
        if ($fill && $stroke) {
            $op = 'B';
        } elseif ($fill) {
            $op = 'f';
        } elseif ($stroke) {
            $op = 'S';
        } else {
            $op = 'n';
        }
        return sprintf("%.2f %.2f %.2f %.2f re %s\n", $x, $y, $w, $h, $op);
    }

    private static function drawText(string $text, float $x, float $y, string $font, float $size): string
    {
        $fontKey = ($font === 'Helvetica-Bold') ? 'F2' : 'F1';
        $escaped = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
        return sprintf("BT /%s %.1f Tf %.2f %.2f Td (%s) Tj ET\n", $fontKey, $size, $x, $y, $escaped);
    }

    private static function drawTextCentered(string $text, float $cellX, float $cellW, float $y, string $font, float $size): string
    {
        // Approximation largeur texte : chaque caractère ≈ size * 0.52
        $approxW = strlen($text) * $size * 0.52;
        $x       = $cellX + max(2, ($cellW - $approxW) / 2);
        return self::drawText($text, $x, $y, $font, $size);
    }

    private static function encodeText(string $text): string
    {
        $encoded = @iconv('UTF-8', 'CP1252//TRANSLIT', $text);
        if ($encoded === false) {
            $encoded = preg_replace('/[^\x20-\x7E]/', '?', $text);
        }
        return $encoded !== false ? $encoded : '';
    }

    // ── Assemblage PDF 1.4 ────────────────────────────────────────────────────
    private static function assemblePdf(string $stream, float $pageW, float $pageH): string
    {
        $length  = strlen($stream);
        $mediaBox = sprintf('[0 0 %.2f %.2f]', $pageW, $pageH);

        $objects = [
            '1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj',
            '2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj',
            '3 0 obj << /Type /Page /Parent 2 0 R /MediaBox ' . $mediaBox
                . ' /Contents 4 0 R /Resources << /Font << /F1 5 0 R /F2 6 0 R >> >> >> endobj',
            "4 0 obj << /Length {$length} >>\nstream\n{$stream}\nendstream endobj",
            '5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >> endobj',
            '6 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >> endobj',
        ];

        $pdf     = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $obj) {
            $offsets[] = strlen($pdf);
            $pdf      .= $obj . "\n";
        }

        $xrefOffset = strlen($pdf);
        $count      = count($objects) + 1;
        $pdf       .= "xref\n0 {$count}\n";
        $pdf       .= "0000000000 65535 f \n";
        foreach ($offsets as $offset) {
            $pdf .= sprintf('%010d 00000 n ', $offset) . "\n";
        }

        $pdf .= "trailer << /Size {$count} /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }

    // ── Fallback : ancien mode texte brut ─────────────────────────────────────
    private static function buildLegacy(array $lines): string
    {
        $pageW   = 612;
        $pageH   = 792;
        $margin  = 50;
        $cursorY = $pageH - 52;
        $stream  = '';

        foreach ($lines as $line) {
            $line = trim((string) $line);
            if ($line === '') { $cursorY -= 10; continue; }
            if ($line === '---') {
                $stream .= "0.85 0.85 0.85 RG\n{$margin} {$cursorY} m " . ($pageW - $margin) . " {$cursorY} l S\n0 0 0 RG\n";
                $cursorY -= 12;
                continue;
            }
            $font = 'F1'; $size = 12; $x = $margin;
            if (strpos($line, '# ') === 0)        { $font = 'F2'; $size = 18; $line = substr($line, 2); }
            elseif (strpos($line, '## ') === 0)  { $font = 'F2'; $size = 12; $line = substr($line, 3); }
            elseif (strpos($line, '### ') === 0) { $font = 'F2'; $size = 14; $line = substr($line, 4); }
            elseif (strpos($line, '- ') === 0)   { $x = $margin + 14; $line = '- ' . substr($line, 2); }

            $enc     = self::encodeText($line);
            $escaped = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $enc);
            $stream .= "BT\n/{$font} {$size} Tf\n1 0 0 1 {$x} {$cursorY} Tm\n({$escaped}) Tj\nET\n";
            $cursorY -= ($size + 6);
        }

        return self::assemblePdf($stream, $pageW, $pageH);
    }
}