<?php

namespace App\Http\Controllers;

use App\Services\ChatGPTService;
use Composer\Autoload\ClassLoader;
use Illuminate\Http\Request;
use App\Models\Companies;

use PDF;

class GetRightDataController extends Controller
{
    protected $chatGPTService;

    public function __construct(ChatGPTService $chatGPTService)
    {
        $this->chatGPTService = $chatGPTService;
    }


    public function downloadAll(Request $request)
    {
        $companies = Companies::all();
        foreach ($companies as $company) {
            $this->getData();
            $company->delete();
        }

        return view('welcome');
    }

    public function getData()
    {

        header('Content-Type: text/html; charset=utf-8');


        $companies = Companies::select('address')->take(1)->get();

        $instruction = "Analyzuj nasledujúci úlohu a vyhľadaj hodnoty pre premenne 'nazov', 'mesto', 'adresa', 'psc'. Zapíš tieto hodnoty do tvaru:
        nazov: mazov spolocnosti (dodržuj aj typ spoločnosti)
        adresa: adresa (psc a mesto tam zahrnuté byť nemá, iba ulica a číslo)
        mesto: mesto
        psc: psc (musí mať 5 čísel a jednu medzeru, vačšínou po adrese, prosím odstran medzeru v psc)
        Tu su príklady ako máš vraciať dáta: nazov: Stavprofil, s. r. o. adresa: Dlhovského 1316/144 mesto: Topoľčianky psc: 95193
        Úloha: \"$companies\".";

        $result = $this->chatGPTService->requestChatGPT($instruction);
        $resultArray = json_decode($result, true);
        $summary = $resultArray['choices'][0]['message']['content'] ?? 'No summary returned.';



        preg_match('/nazov:\s*([^ ]*.*?)(?=\s*adresa:)/', $summary, $nazovMatches);
        $nazov = trim($nazovMatches[1] ?? 'N/A');

        preg_match('/adresa:\s*([^ ]*.*?)(?=\s*mesto:)/', $summary, $adresaMatches);
        $adresa = trim($adresaMatches[1] ?? 'N/A');

        preg_match('/mesto:\s*([^ ]*.*?)(?=\s*psc:)/', $summary, $mestoMatches);
        $mesto = trim($mestoMatches[1] ?? 'N/A');

        preg_match('/psc:\s*([^ ]*.*?)/', $summary, $pscMatches);
        $psc = trim($pscMatches[1] ?? 'N/A');



        $data = [
            'adresa' => $adresa,
            'mesto' => $mesto,
            'psc' => $psc,
            'nazov' => $nazov,
        ];

        $pdf = PDF::loadView('pdf.company', $data)
            ->setPaper('A4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        set_time_limit(600);

        $pdfFilePath = public_path('pdf/' . $data['nazov'] . '.pdf');
        $pdf->save($pdfFilePath);
    }



}


