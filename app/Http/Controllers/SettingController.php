<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $settings = config('app_settings.section.app.keys');
        
        // Pass current env values to the view
        $currentValues = [];
        foreach ($settings as $key => $config) {
            $currentValues[$key] = env($key);
        }

        return view('apps.settings.index', compact('settings', 'currentValues'));
    }

    public function update(Request $request)
    {
        $settings = config('app_settings.section.app.keys');
        $rules = [];
        foreach ($settings as $key => $config) {
            if (isset($config['rules'])) {
                $rules[$key] = $config['rules'];
            }
        }

        $validated = $request->validate($rules);
        $path = base_path('.env');

        if (File::exists($path)) {
            $content = File::get($path);
            
            foreach ($validated as $key => $value) {
                // Handle spaces by wrapping in quotes
                $valueToStore = preg_match('/\s/', $value) ? '"' . $value . '"' : $value;
                
                // Check if key exists
                if (preg_match("/^{$key}=.*/m", $content)) {
                    $content = preg_replace("/^{$key}=.*/m", "{$key}={$valueToStore}", $content);
                } else {
                    $content .= "\n{$key}={$valueToStore}";
                }
            }

            File::put($path, $content);
        }

        // Clear config cache to apply changes immediately
        Artisan::call('config:clear');

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success', 
                'message' => 'Pengaturan berhasil diperbarui. Aplikasi akan dimuat ulang.'
            ]);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
