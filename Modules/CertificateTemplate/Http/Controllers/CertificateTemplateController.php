<?php

namespace Modules\CertificateTemplate\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CertificateTemplate\Entities\CertificateTemplate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\MimeTypes;

class CertificateTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (checkRole('template', 'view') == true) {
            $templates = CertificateTemplate::orderBy('id', 'desc')->get();
            return view('certificatetemplate::index', compact('templates'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // if (checkRole('template', 'add') == true) {
        activityLog('Admin', 'Opened Create certificate template Page');
        return view('certificatetemplate::create');
        // } else {
        //     return redirect()->route('admin.certificate.template.index')->with('failure', 'This user does not have permission to add certificate template');
        // }
        // return view('certificatetemplate::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('certificatetemplate::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $template = CertificateTemplate::findorfail($id);
        if ($template) {
            $filePath = public_path($template->path);
            if (!File::exists($filePath)) {
                abort(404);
            }
            $htmlContent = file_get_contents($filePath);
            $templateID = $template->id;
            return view('certificatetemplate::edit', compact('template', 'templateID', 'htmlContent'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'htmlContent' => 'required|string',
            'name' => 'required|string',
            'type' => 'required|string'
        ]);

        // $template = CertificateTemplate::findorfail($id);

        $htmlContent = $request->input('htmlContent');
        $fileName = $request->input('name');
        $fileName = str_replace(' ', '-', $fileName);
        $pathToSave = "templates/certificate/" . $fileName . ".html";
        $filePath = public_path($pathToSave);
        // Ensure the directory exists
        if (!File::exists(public_path('templates/certificate/'))) {
            File::makeDirectory(public_path('templates/certificate/'), 0755, true, true);
        }

        // Save the HTML file
        if (file_put_contents($filePath, $htmlContent)) {
            $template =  CertificateTemplate::where('id', $id)->update([
                'name' => $request->name,
                'type' => $request->type,
                'path' => $pathToSave
            ]);
            return response()->json(['success' => true, 'message' => 'HTML updated successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to update HTML']);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }


    public function loadHTML($id, $request)
    {

        if (checkRole('template', 'create') == true) {
            $fileName = $request->name;
            $fileName = str_replace(' ', '-', $fileName);
            $pathToFind = "templates/certificate" . $fileName . ".html";
            $filePath = public_path($pathToFind);
            if (!File::exists($filePath)) {
                return view('template::html.upload', compact('template'));
            }
            $htmlContent = file_get_contents($filePath);
            return view('template::html.upload', compact('template', 'htmlContent'));
        } else {
            return abort(404);
        }
    }

    public function convertDoc(Request $request)
    {
        $request->validate([
            'docxFile' => 'required|mimes:docx|max:4048'
        ]);
        
        $file = $request->file('docxFile');

        // Process file to convert DOCX to HTML
        try {
            // Use PhpWord to load and convert the DOCX file to HTML
            $result = convertDocxToHtml($file);

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Conversion failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error converting document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveHtml(Request $request)
    {
        $request->validate([
            'htmlContent' => 'required|string',
            'name' => 'required|string',
            'type' => 'required|string'
        ]);

        $htmlContent = $request->input('htmlContent');
        $fileName = $request->input('name');
        $fileName = str_replace(' ', '-', $fileName);
        $fileNameToSave = $fileName . ".html";
        $pathToSave = "templates/certificate/" . $fileName . ".html";
        $filePath = public_path($pathToSave);
        // Ensure the directory exists
        if (!File::exists(public_path('templates/certificate/'))) {
            File::makeDirectory(public_path('templates/certificate/'), 0755, true, true);
        }

        // Save the HTML file
        if (file_put_contents($filePath, $htmlContent)) {
            $template = CertificateTemplate::create([
                'name' => $request->name,
                'type' => $request->type,
                'path' => $pathToSave
            ]);
            return response()->json(['success' => true, 'message' => 'HTML saved successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to save HTML']);
        }
    }
}
