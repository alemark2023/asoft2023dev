<?php

namespace App\CoreFacturalo\Helpers\Functions;

use App\CoreFacturalo\Template;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;



class GeneralPdfHelper
{

    public static function getPreviewTempPdf($temp_folder, $file_content)
    {
        $temp = tempnam(sys_get_temp_dir(), $temp_folder);

        file_put_contents($temp, $file_content);

        return response()->file($temp);
    }


    public static function getBasicPdf($document_type, $document, $format_pdf = 'a4', $filename = null)
    {
        
        ini_set("pcre.backtrack_limit", "5000000");
        $template = new Template();
        $pdf = new Mpdf();

        $company = Company::active();
        $filename = $filename ?? $document->filename;

        $base_template = Establishment::find($document->establishment_id)->template_pdf;

        $html = $template->pdf($base_template, $document_type, $company, $document, $format_pdf);

        $pdf_font_regular = config('tenant.pdf_name_regular');
        $pdf_font_bold = config('tenant.pdf_name_bold');

        if ($pdf_font_regular != false) 
        {

            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            $default = [
                'fontDir' => array_merge($fontDirs, [
                    app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                                DIRECTORY_SEPARATOR.'pdf'.
                                                DIRECTORY_SEPARATOR.$base_template.
                                                DIRECTORY_SEPARATOR.'font')
                ]),
                'fontdata' => $fontData + [
                    'custom_bold' => [
                        'R' => $pdf_font_bold.'.ttf',
                    ],
                    'custom_regular' => [
                        'R' => $pdf_font_regular.'.ttf',
                    ],
                ]
            ];


            $pdf = new Mpdf($default);
        }

        $path_css = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.
                                             DIRECTORY_SEPARATOR.'pdf'.
                                             DIRECTORY_SEPARATOR.$base_template.
                                             DIRECTORY_SEPARATOR.'style.css');

        $stylesheet = file_get_contents($path_css);

        $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);

        $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        return $pdf->output('', 'S');

    }


    public static function getNumberIdFilename($number, $id)
    {
        return join('-', [ $number, $id, date('Ymd') ]);
    }

}
