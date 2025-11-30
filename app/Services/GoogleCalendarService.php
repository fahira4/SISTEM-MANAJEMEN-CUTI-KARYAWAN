<?php
// app/Services/GoogleCalendarService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GoogleCalendarService
{
    private $calendarUrls = [
        'indonesia' => 'https://calendar.google.com/calendar/ical/id.indonesian%23holiday%40group.v.calendar.google.com/public/basic.ics',
        'indonesian_holidays' => 'https://calendar.google.com/calendar/ical/en.indonesian%23holiday%40group.v.calendar.google.com/public/basic.ics',
    ];

    public function getHolidays($year = null)
    {
        try {
            $allHolidays = [];
            
            foreach ($this->calendarUrls as $source => $url) {
                $holidays = $this->fetchFromCalendar($url, $year);
                $allHolidays = array_merge($allHolidays, $holidays);
            }
            
            $uniqueHolidays = $this->removeDuplicates($allHolidays);
            
            usort($uniqueHolidays, function($a, $b) {
                return strcmp($a['date'], $b['date']);
            });
            
            return $uniqueHolidays;
            
        } catch (\Exception $e) {
            Log::error('Google Calendar iCal Error: ' . $e->getMessage());
            return [];
        }
    }

    private function fetchFromCalendar($url, $filterYear = null)
    {
        $response = Http::timeout(30)->get($url);
        
        if (!$response->successful()) {
            return [];
        }
        
        return $this->parseICal($response->body(), $filterYear);
    }

    private function parseICal($icalData, $filterYear = null)
    {
        $holidays = [];
        $lines = explode("\n", $icalData);
        
        $currentEvent = [];
        $inEvent = false;
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (str_starts_with($line, 'BEGIN:VEVENT')) {
                $inEvent = true;
                $currentEvent = [];
                continue;
            }
            
            if (str_starts_with($line, 'END:VEVENT')) {
                $inEvent = false;
                
                if (!empty($currentEvent['name']) && !empty($currentEvent['date'])) {

                    if ($filterYear === null || str_starts_with($currentEvent['date'], $filterYear)) {
                        $holidayType = $this->determineHolidayType($currentEvent['name']);
                        
                        $holidays[] = [
                            'name' => $currentEvent['name'],
                            'date' => $currentEvent['date'],
                            'type' => $holidayType,
                            'description' => $currentEvent['name'],
                            'is_recurring' => false,
                            'source' => 'google_calendar'
                        ];
                    }
                }
                continue;
            }
            
            if ($inEvent) {
                if (str_starts_with($line, 'SUMMARY:')) {
                    $currentEvent['name'] = trim(str_replace('SUMMARY:', '', $line));
                } elseif (str_starts_with($line, 'DTSTART;VALUE=DATE:')) {
                    $dateStr = trim(str_replace('DTSTART;VALUE=DATE:', '', $line));
                    $currentEvent['date'] = $this->formatDate($dateStr);
                } elseif (str_starts_with($line, 'DTSTART;')) {

                    $parts = explode(':', $line, 2);
                    if (count($parts) === 2) {
                        $dateStr = trim($parts[1]);
                        $currentEvent['date'] = $this->formatDate($dateStr);
                    }
                } elseif (str_starts_with($line, 'DESCRIPTION:')) {
                    $currentEvent['description'] = trim(str_replace('DESCRIPTION:', '', $line));
                }
            }
        }
        
        return $holidays;
    }

    private function formatDate($dateStr)
    {
        if (strlen($dateStr) === 8 && is_numeric($dateStr)) {
            return substr($dateStr, 0, 4) . '-' . substr($dateStr, 4, 2) . '-' . substr($dateStr, 6, 2);
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}/', $dateStr)) {
            return substr($dateStr, 0, 10);
        }
        
        try {
            return Carbon::parse($dateStr)->format('Y-m-d');
        } catch (\Exception $e) {
            return $dateStr;
        }
    }

    private function determineHolidayType($holidayName)
    {
        $name = strtolower($holidayName);
        
        if (str_contains($name, 'cuti bersama') || 
            str_contains($name, 'joint leave') ||
            str_contains($name, 'collective leave')) {
            return 'joint_leave';
        }
        
        if (str_contains($name, 'perusahaan') || 
            str_contains($name, 'company') ||
            str_contains($name, 'corporate')) {
            return 'company';
        }
        
        return 'national';
    }

    private function removeDuplicates($holidays)
    {
        $unique = [];
        
        foreach ($holidays as $holiday) {
            $key = $holiday['date'] . '|' . $holiday['name'];
            if (!isset($unique[$key])) {
                $unique[$key] = $holiday;
            }
        }
        
        return array_values($unique);
    }

    
    public function getAvailableYears()
    {
        $holidays = $this->getHolidays();
        $years = [];
        
        foreach ($holidays as $holiday) {
            $year = substr($holiday['date'], 0, 4);
            if ($year && !in_array($year, $years)) {
                $years[] = $year;
            }
        }
        
        sort($years);
        return $years;
    }
}