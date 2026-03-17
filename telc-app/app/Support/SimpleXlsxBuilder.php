<?php

namespace App\Support;

use ZipArchive;

class SimpleXlsxBuilder
{
    // ── En-têtes TELC officiels (14 colonnes) ────────────────────────────────
    public static function telcHeaders(): array
    {
        return [
            'Nachname',
            'Vorname',
            'E-Mail (optional)',
            'Geburtsdatum Format: TT.MM.JJJJ',
            'Geburtsort',
            'Muttersprache Bitte auswählen',
            'Geschlecht Bitte auswählen',
            'Teilnehmernummer / Bogennummer Bitte stets siebenstellig oder achtstellig angeben, ggf. Nullen voranstellen.',
            'Teilprüfung (optionale Eingabe)',
            'Vorkommnisse Bitte auswählen',
            'Geburtsland Bitte auswählen',
            'Wohnort Stadt',
            'Wohnort Land Bitte auswählen',
            'Nur für DTZ / DTB BAMF-Kennnziffer',
        ];
    }

    // ── Mode simple original (inchangé) ──────────────────────────────────────
    public static function build(array $headers, array $rows): string
    {
        $temp = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip  = new ZipArchive();
        $zip->open($temp, ZipArchive::OVERWRITE);

        $sheetRows   = [];
        $sheetRows[] = self::rowXml(1, $headers);
        foreach ($rows as $index => $row) {
            $sheetRows[] = self::rowXml($index + 2, $row);
        }

        $worksheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetData>' . implode('', $sheetRows) . '</sheetData>'
            . '</worksheet>';

        self::addCommonFiles($zip);
        $zip->addFromString('xl/worksheets/sheet1.xml', $worksheet);
        $zip->addFromString('xl/styles.xml', self::buildSimpleStylesXml());
        $zip->close();

        $content = file_get_contents($temp);
        @unlink($temp);
        return $content;
    }

    // ── Mode TELC avec mise en forme officielle ───────────────────────────────
    public static function buildTelc(array $rows): string
    {
        $headers      = self::telcHeaders();
        $colCount     = count($headers);
        $lastCol      = self::columnName($colCount);
        $sharedStr    = [];
        $sheetRows    = [];

        // Ligne 1 — bandeau avertissement rouge
        $warning = 'Teilnehmende:    Bitte achten Sie darauf, alle Felder auszufüllen. '
            . 'Sie finden in der telc Community Hinweise zum Upload und zur Form des Templates.';
        $sharedStr[] = $warning;
        $sheetRows[] = '<row r="1" ht="28" customHeight="1">'
            . '<c r="A1" t="s" s="1"><v>0</v></c>'
            . '</row>';

        // Ligne 2 — en-têtes jaune/orange
        $headerCells = '';
        foreach ($headers as $i => $hdr) {
            $sharedStr[] = $hdr;
            $idx         = count($sharedStr) - 1;
            $col         = self::columnName($i + 1);
            $headerCells .= '<c r="' . $col . '2" t="s" s="2"><v>' . $idx . '</v></c>';
        }
        $sheetRows[] = '<row r="2" ht="90" customHeight="1">' . $headerCells . '</row>';

        // Lignes de données
        foreach ($rows as $ri => $row) {
            $excelRow = $ri + 3;
            $cells    = '';
            // S'assurer qu'on a exactement 14 colonnes
            $padded = array_pad(array_values((array) $row), 14, '');
            foreach ($padded as $ci => $value) {
                $sharedStr[] = (string) ($value ?? '');
                $idx         = count($sharedStr) - 1;
                $col         = self::columnName($ci + 1);
                $cells      .= '<c r="' . $col . $excelRow . '" t="s" s="3"><v>' . $idx . '</v></c>';
            }
            $sheetRows[] = '<row r="' . $excelRow . '" ht="16" customHeight="1">' . $cells . '</row>';
        }

        // Largeurs colonnes
        $widths  = [18, 18, 30, 18, 16, 18, 14, 32, 16, 16, 18, 16, 18, 26];
        $colsXml = '<cols>';
        foreach ($widths as $i => $w) {
            $c       = $i + 1;
            $colsXml .= '<col min="' . $c . '" max="' . $c . '" width="' . $w . '" customWidth="1"/>';
        }
        $colsXml .= '</cols>';

        // Fusion ligne 1
        $mergeXml = '<mergeCells><mergeCell ref="A1:' . $lastCol . '1"/></mergeCells>';

        // Figer lignes 1+2
        $sheetViewXml = '<sheetViews><sheetView tabSelected="1" zoomScale="85" workbookViewId="0">'
            . '<pane ySplit="2" topLeftCell="A3" activePane="bottomLeft" state="frozen"/>'
            . '</sheetView></sheetViews>';

        // Shared strings
        $ssXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            . ' count="' . count($sharedStr) . '" uniqueCount="' . count($sharedStr) . '">';
        foreach ($sharedStr as $s) {
            $e      = htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $ssXml .= '<si><t xml:space="preserve">' . $e . '</t></si>';
        }
        $ssXml .= '</sst>';

        $worksheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            . ' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . $sheetViewXml
            . $colsXml
            . '<sheetData>' . implode('', $sheetRows) . '</sheetData>'
            . $mergeXml
            . '</worksheet>';

        $temp = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip  = new ZipArchive();
        $zip->open($temp, ZipArchive::OVERWRITE);

        self::addCommonFiles($zip, true);
        $zip->addFromString('xl/worksheets/sheet1.xml', $worksheet);
        $zip->addFromString('xl/sharedStrings.xml', $ssXml);
        $zip->addFromString('xl/styles.xml', self::buildTelcStylesXml());
        $zip->close();

        $content = file_get_contents($temp);
        @unlink($temp);
        return $content;
    }

    // ── Fichiers communs ZIP ──────────────────────────────────────────────────
    protected static function addCommonFiles(ZipArchive $zip, bool $withSharedStrings = false): void
    {
        $sharedStringsOverride = $withSharedStrings
            ? '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>'
            : '';

        $zip->addFromString('[Content_Types].xml',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . $sharedStringsOverride
            . '<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'
            . '<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'
            . '</Types>');

        $ssRel = $withSharedStrings
            ? '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>'
            : '';

        $zip->addFromString('xl/_rels/workbook.xml.rels',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . $ssRel
            . '</Relationships>');

        $zip->addFromString('_rels/.rels',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>'
            . '</Relationships>');

        $zip->addFromString('xl/workbook.xml',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            . ' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Inscriptions" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>');

        $zip->addFromString('docProps/app.xml',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties">'
            . '<Application>Tara Institut</Application>'
            . '</Properties>');

        $zip->addFromString('docProps/core.xml',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"'
            . ' xmlns:dc="http://purl.org/dc/elements/1.1/">'
            . '<dc:title>Préinscription TELC - Export</dc:title>'
            . '<dc:creator>Tara Institut</dc:creator>'
            . '</cp:coreProperties>');
    }

    // ── Styles TELC ───────────────────────────────────────────────────────────
    // s="0" défaut  s="1" bandeau rouge  s="2" en-tête jaune  s="3" données
    protected static function buildTelcStylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'

            . '<fonts count="4">'
            // 0 défaut
            . '<font><sz val="9"/><name val="Arial"/></font>'
            // 1 bandeau : gras rouge foncé
            . '<font><sz val="9"/><b/><color rgb="FF9C0006"/><name val="Arial"/></font>'
            // 2 en-tête : gras brun/orange
            . '<font><sz val="9"/><b/><color rgb="FF7F6000"/><name val="Arial"/></font>'
            // 3 données : noir
            . '<font><sz val="9"/><color rgb="FF000000"/><name val="Arial"/></font>'
            . '</fonts>'

            . '<fills count="4">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            // 2 fond rouge clair bandeau
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFFFC7CE"/><bgColor indexed="64"/></patternFill></fill>'
            // 3 fond jaune/orange en-têtes
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFFFE699"/><bgColor indexed="64"/></patternFill></fill>'
            . '</fills>'

            . '<borders count="2">'
            . '<border><left/><right/><top/><bottom/><diagonal/></border>'
            . '<border>'
            . '<left style="thin"><color rgb="FF000000"/></left>'
            . '<right style="thin"><color rgb="FF000000"/></right>'
            . '<top style="thin"><color rgb="FF000000"/></top>'
            . '<bottom style="thin"><color rgb="FF000000"/></bottom>'
            . '<diagonal/>'
            . '</border>'
            . '</borders>'

            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'

            . '<cellXfs count="4">'
            // 0 défaut
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            // 1 bandeau : fond rouge, texte rouge gras, bordure, wrap
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">'
            . '<alignment horizontal="left" vertical="center" wrapText="1"/>'
            . '</xf>'
            // 2 en-tête : fond orange, texte brun gras, centré, wrap, bordure
            . '<xf numFmtId="0" fontId="2" fillId="3" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">'
            . '<alignment horizontal="center" vertical="center" wrapText="1"/>'
            . '</xf>'
            // 3 données : bordure fine, texte noir
            . '<xf numFmtId="0" fontId="3" fillId="0" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1">'
            . '<alignment horizontal="left" vertical="center"/>'
            . '</xf>'
            . '</cellXfs>'
            . '</styleSheet>';
    }

    // ── Styles simples (mode build) ───────────────────────────────────────────
    protected static function buildSimpleStylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="1"><font><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="2"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill></fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/></cellXfs>'
            . '</styleSheet>';
    }

    // ── rowXml sans style (mode simple) ──────────────────────────────────────
    protected static function rowXml($rowNumber, array $values): string
    {
        $cells = '';
        foreach (array_values($values) as $index => $value) {
            $col     = self::columnName($index + 1);
            $escaped = htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $cells  .= '<c r="' . $col . $rowNumber . '" t="inlineStr"><is><t>' . $escaped . '</t></is></c>';
        }
        return '<row r="' . $rowNumber . '">' . $cells . '</row>';
    }

    // ── Nom de colonne Excel ──────────────────────────────────────────────────
    protected static function columnName($index): string
    {
        $name = '';
        while ($index > 0) {
            $index--;
            $name  = chr(65 + ($index % 26)) . $name;
            $index = intdiv($index, 26);
        }
        return $name;
    }
}