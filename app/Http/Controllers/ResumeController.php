<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use ZipArchive;

class ResumeController extends Controller
{
    public function match(Request $request)
    {
        $request->validate([
            'resume' => 'required|max:10000|mimes:pdf,docx,txt',
        ]);

        try {
            $file = $request->file('resume');
            $extension = $file->getClientOriginalExtension();
            $path = $file->getPathname();
            $text = "";

            if (strtolower($extension) === 'pdf') {
                $parser = new Parser();
                $pdf = $parser->parseFile($path);
                $text = $pdf->getText();
            } 
            elseif (strtolower($extension) === 'txt') {
                $text = file_get_contents($path);
            } 
            elseif (strtolower($extension) === 'docx') {
                $text = $this->readDocx($path);
            }

            $cleanText = preg_replace('/\s+/', ' ', trim($text));

            $prompt = "
                You are an Agentic SAP Recruiter. 
                Analyze the following resume text: \"$cleanText\"
                
                STRICT TASK:
                1. Summarize the candidate.
                2. Assign any recommended roles.
                3. Identify 3 skills that need improvement.
                4. Suggest 3 learning tips.
                5. LOG YOUR REASONING: Explain your logical steps, tools used (e.g., 'Text Analysis', 'Keyword Match'), and how inputs led to the role choice.

                OUTPUT FORMAT:
                Return ONLY valid JSON. Structure:
                {
                    \"summary\": \"...\",
                    \"suggested_role\": \"...\",
                    \"skill_gaps\": [\"...\", \"...\", \"...\"],
                    \"learning_tips\": [\"...\", \"...\", \"...\"],
                    \"reasoning_log\": [
                        { \"step\": \"1. Input Processing\", \"details\": \"Extracted keywords...\" },
                        { \"step\": \"2. Role Matching\", \"details\": \"Matched 'inventory' to MM...\" }
                    ]
                }
            ";

            $response = Http::timeout(120)->post('http://host.docker.internal:11434/api/generate', [
                'model' => 'llama3.2',
                'prompt' => $prompt,
                'stream' => false,
                'format' => 'json'
            ]);

            $aiData = $response->json();
            return response()->json(json_decode($aiData['response'], true));

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function readDocx($filePath)
    {
        $zip = new ZipArchive;
        $content = '';
        if ($zip->open($filePath) === TRUE) {
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $data = $zip->getFromIndex($index);
                $xml = new \DOMDocument();
                $xml->loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $content = strip_tags($xml->saveXML());
            }
            $zip->close();
        }
        return $content;
    }
}