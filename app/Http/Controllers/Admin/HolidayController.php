<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\GoogleCalendarService;


class HolidayController extends Controller
{
public function index(Request $request)
{
    $year = $request->get('year', 'all');
    
    if ($year === 'all') {
        $holidays = Holiday::orderBy('date')->get();
    } else {
        $holidays = Holiday::forYear($year)
                        ->orderBy('date')
                        ->get()
                        ->map(function($holiday) use ($year) {
                            if ($holiday->is_recurring) {
                                $holiday->date = Carbon::create($year, $holiday->date->month, $holiday->date->day);
                            }
                            return $holiday;
                        })
                        ->sortBy('date');
    }

    $availableYears = Holiday::selectRaw('YEAR(date) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

    $availableYears->prepend('all');
    
    if (!$availableYears->contains(date('Y'))) {
        $availableYears->push(date('Y'));
    }

    return view('admin.holidays.index', compact('holidays', 'year', 'availableYears'));
}

    public function create()
    {
        return view('admin.holidays.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:national,company,joint_leave',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean'
        ]);

        Holiday::create([
            'name' => $request->name,
            'date' => $request->date,
            'type' => $request->type,
            'description' => $request->description,
            'is_recurring' => $request->boolean('is_recurring')
        ]);

        return redirect()->route('admin.holidays.index')
                        ->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function edit(Holiday $holiday)
    {
        return view('admin.holidays.edit', compact('holiday'));
    }

    public function show(Holiday $holiday)
    {
        return view('admin.holidays.show', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:national,company,joint_leave',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean'
        ]);

        $holiday->update([
            'name' => $request->name,
            'date' => $request->date,
            'type' => $request->type,
            'description' => $request->description,
            'is_recurring' => $request->boolean('is_recurring')
        ]);

        return redirect()->route('admin.holidays.index')
                        ->with('success', 'Hari libur berhasil diperbarui.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('admin.holidays.index')
                        ->with('success', 'Hari libur berhasil dihapus.');
    }
    
public function showImportForm()
{
    try {
        $apiService = new \App\Services\GoogleCalendarService();
        $availableYears = $apiService->getAvailableYears();
        
        \Log::info("Available years from Google Calendar: " . implode(', ', $availableYears));
        
        $currentYear = date('Y');
        if (!in_array($currentYear, $availableYears)) {
            $availableYears[] = $currentYear;
        }
        
        sort($availableYears);
        
        return view('admin.holidays.import', compact('availableYears'));
        
    } catch (\Exception $e) {
        \Log::error('Error in showImportForm: ' . $e->getMessage());
        $availableYears = [2023, 2024, 2025];
        return view('admin.holidays.import', compact('availableYears'))
                ->with('warning', 'Google Calendar service sedang tidak tersedia: ' . $e->getMessage());
    }
}

public function importFromGoogleCalendar(Request $request)
{
    $request->validate([
        'year' => 'nullable|integer|min:2000|max:2030',
        'import_type' => 'required|in:specific_year,all_years'
    ]);

    try {
        $apiService = new \App\Services\GoogleCalendarService();
        
        \Log::info('Starting Google Calendar import...');
        
        if ($request->import_type === 'specific_year' && $request->year) {
            $holidays = $apiService->getHolidays($request->year);
            $successMessage = "Data hari libur tahun {$request->year}";
            \Log::info("Importing specific year: {$request->year}");
        } else {
            $holidays = $apiService->getHolidays(); 
            $successMessage = "Semua data hari libur yang tersedia";
            \Log::info("Importing all years");
        }
        
        if (empty($holidays)) {
            \Log::warning('No holidays found from Google Calendar');
            return redirect()->route('admin.holidays.index')
                            ->with('error', 'Tidak dapat mengambil data dari Google Calendar. Silakan coba lagi.');
        }

        $importedCount = 0;
        $skippedCount = 0;
        foreach ($holidays as $index => $holidayData) {
            
            $existing = Holiday::where('date', $holidayData['date'])->first();

            if ($existing) {
                $skippedCount++;
                \Log::info("Skipped (Duplicate Date): " . $holidayData['date'] . " - " . $holidayData['name']);
            } else {
                Holiday::create([
                    'name' => $holidayData['name'],
                    'date' => $holidayData['date'],
                    'type' => $holidayData['type'],
                    'description' => $holidayData['description'],
                    'is_recurring' => $holidayData['is_recurring']
                ]);
                $importedCount++;
                \Log::info("Imported: " . $holidayData['name']);
            }
        }

        $message = "✅ {$successMessage} selesai diproses! ";
        $message .= "({$importedCount} baru ditambahkan, {$skippedCount} duplikat diabaikan)";
        
        \Log::info("Import completed: {$message}");

        return redirect()->route('admin.holidays.index')
                        ->with('success', $message);

    } catch (\Exception $e) {
        \Log::error('Google Calendar import error: ' . $e->getMessage());
        return redirect()->route('admin.holidays.index')
                        ->with('error', 'Error importing from Google Calendar: ' . $e->getMessage());
    }
}
}

?>