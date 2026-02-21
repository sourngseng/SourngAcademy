<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LanguageManagementController extends Controller
{
    protected $langPath;
    
    public function __construct()
    {
        $this->langPath = resource_path('lang');
    }
    
    /**
     * Display a listing of languages
     */
    public function index()
    {
        $languages = [];
        $files = File::glob($this->langPath . '/*.json');
        
        foreach ($files as $file) {
            $locale = basename($file, '.json');
            $translations = json_decode(File::get($file), true);
            
            $languages[] = [
                'locale' => $locale,
                'name' => config('app.available_locales')[$locale]['name'] ?? $locale,
                'flag' => config('app.available_locales')[$locale]['flag'] ?? '🌐',
                'keys_count' => count($translations),
                'file_size' => File::size($file),
            ];
        }
        
        return view('admin.languages.index', compact('languages'));
    }
    
    /**
     * Show the form for creating a new language
     */
    public function create()
    {
        // Get English as template
        $templateFile = $this->langPath . '/en.json';
        $template = json_decode(File::get($templateFile), true);
        
        return view('admin.languages.create', compact('template'));
    }
    
    /**
     * Store a newly created language
     */
    public function store(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|max:5',
            'name' => 'required|string|max:50',
            'flag' => 'nullable|string|max:10',
        ]);
        
        $locale = strtolower($request->locale);
        $filePath = $this->langPath . '/' . $locale . '.json';
        
        // Check if file already exists
        if (File::exists($filePath)) {
            return back()->with('error', 'Language file already exists!');
        }
        
        // Copy English template
        $templateFile = $this->langPath . '/en.json';
        $translations = json_decode(File::get($templateFile), true);
        
        // Save new language file
        File::put($filePath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        // Update config
        $this->updateConfig($locale, $request->name, $request->flag ?? '🌐');
        
        return redirect()->route('admin.languages.index')->with('success', 'Language created successfully!');
    }
    
    /**
     * Show the form for editing translations
     */
    public function edit($locale)
    {
        $filePath = $this->langPath . '/' . $locale . '.json';
        
        if (!File::exists($filePath)) {
            return redirect()->route('admin.languages.index')->with('error', 'Language file not found!');
        }
        
        $translations = json_decode(File::get($filePath), true);
        $languageName = config('app.available_locales')[$locale]['name'] ?? $locale;
        
        return view('admin.languages.edit', compact('locale', 'translations', 'languageName'));
    }
    
    /**
     * Update translations
     */
    public function update(Request $request, $locale)
    {
        $filePath = $this->langPath . '/' . $locale . '.json';
        
        if (!File::exists($filePath)) {
            return back()->with('error', 'Language file not found!');
        }
        
        $translations = $request->input('translations', []);
        
        // Save updated translations
        File::put($filePath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        return back()->with('success', 'Translations updated successfully!');
    }
    
    /**
     * Delete a language
     */
    public function destroy($locale)
    {
        // Prevent deleting English
        if ($locale === 'en') {
            return back()->with('error', 'Cannot delete English language!');
        }
        
        $filePath = $this->langPath . '/' . $locale . '.json';
        
        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect()->route('admin.languages.index')->with('success', 'Language deleted successfully!');
        }
        
        return back()->with('error', 'Language file not found!');
    }
    
    /**
     * Add new translation key to all languages
     */
    public function addKey(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);
        
        $files = File::glob($this->langPath . '/*.json');
        
        foreach ($files as $file) {
            $translations = json_decode(File::get($file), true);
            
            // Add new key if it doesn't exist
            if (!isset($translations[$request->key])) {
                $translations[$request->key] = $request->value;
                File::put($file, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }
        
        return back()->with('success', 'Translation key added to all languages!');
    }
    
    /**
     * Set default language
     */
    public function setDefault(Request $request, $locale)
    {
        $filePath = $this->langPath . '/' . $locale . '.json';
        
        if (!File::exists($filePath)) {
            return back()->with('error', 'Language file not found!');
        }
        
        // Update .env file
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            return back()->with('error', '.env file not found!');
        }
        
        $envContent = File::get($envPath);
        
        // Update or add APP_LOCALE
        if (preg_match('/^APP_LOCALE=.*$/m', $envContent)) {
            $envContent = preg_replace('/^APP_LOCALE=.*$/m', 'APP_LOCALE=' . $locale, $envContent);
        } else {
            // Add after APP_FALLBACK_LOCALE if exists, otherwise at the end
            if (preg_match('/^APP_FALLBACK_LOCALE=.*$/m', $envContent)) {
                $envContent = preg_replace(
                    '/(^APP_FALLBACK_LOCALE=.*$)/m',
                    "$1\nAPP_LOCALE=" . $locale,
                    $envContent
                );
            } else {
                $envContent = rtrim($envContent) . "\nAPP_LOCALE=" . $locale . "\n";
            }
        }
        
        File::put($envPath, $envContent);
        
        // Clear session locale so it uses the new default
        $request->session()->forget('locale');
        
        // Clear config cache
        \Artisan::call('config:clear');
        
        // Set for current request
        config(['app.locale' => $locale]);
        app()->setLocale($locale);
        
        return redirect()->route('admin.languages.index')->with('success', 'Default language set to ' . strtoupper($locale) . '! This will apply to all new sessions.');
    }
    
    /**
     * Update config file
     */
    protected function updateConfig($locale, $name, $flag)
    {
        $configPath = config_path('app.php');
        $content = File::get($configPath);
        
        // Find available_locales array and add new language
        $pattern = "/'available_locales'\s*=>\s*\[(.*?)\],/s";
        
        if (preg_match($pattern, $content, $matches)) {
            $localesArray = $matches[1];
            $newEntry = "\n        '{$locale}' => ['name' => '{$name}', 'flag' => '{$flag}'],";
            
            // Check if locale doesn't already exist
            if (strpos($localesArray, "'{$locale}'") === false) {
                $updatedArray = $localesArray . $newEntry;
                $newContent = preg_replace($pattern, "'available_locales' => [{$updatedArray}\n    ],", $content);
                File::put($configPath, $newContent);
            }
        }
    }
}
