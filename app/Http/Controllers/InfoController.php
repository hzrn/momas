<?php
namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\CategoryInfo;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class InfoController extends Controller
{
    public function index()
    {
        $info = Info::with('category')->MosqueUser()->orderBy('created_at', 'desc')->get();
        return view('info_index', ['info' => $info, 'title' => __('info.title')]);
    }

    public function create()
    {
        $currentMosqueId = auth()->user()->mosque_id;
        $categoryList = CategoryInfo::where('mosque_id', $currentMosqueId)->pluck('name', 'id');

        return view('info_form', [
            'info' => new Info(),
            'route' => 'info.store',
            'method' => 'POST',
            'categoryList' => $categoryList,
            'title' => __('info.form_title')
        ]);
    }

    public function store(Request $request)
    {
        $requestData = $request->validate([
            'title' => 'required|string|max:255',
            'category_info_id' => 'required|exists:category_infos,id',
            'date' => 'required|date_format:Y-m-d\TH:i',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'reminder_date' => 'nullable|date_format:Y-m-d',
        ]);

        \Log::info('Storing Info:', $requestData);

        if ($request->hasFile('photo')) {
            $imagePath = $this->storePhoto($request->file('photo'));
            $requestData['photo'] = $imagePath;
        }

        Info::create($requestData);

        // Add reminder to session using the date field with formatting
        if ($request->has('date') && $request->has('reminder_date')) {
            // Parse the date and reminder_date from the request
            $eventDate = Carbon::parse($requestData['date']);
            $reminderDate = Carbon::parse($requestData['reminder_date']);

            // Check if the current date is equal to the reminder_date
            if ($reminderDate->isToday() && $reminderDate->isSameDay(Carbon::now())) {
                // Format the event date for display
                $formattedEventDate = $eventDate->format('j/n/Y g:i A');

                session()->push('reminders', __("info.event_reminder", [
                    'title' => $requestData['title'],
                    'date' => $formattedEventDate,
                ]));

            }
        }


        flash(__('info.saved'))->success();
        return redirect()->route('info.index');
    }

    public function show(Info $info)
    {
        $info->load('createdBy', 'updatedBy');

        return view('info_show', [
            'info' => $info,
            'title' => __('info.details_title'),
        ]);
    }

    public function edit(Info $info)
    {
        $categoryList = CategoryInfo::pluck('name', 'id');
        return view('info_form', [
            'info' => $info,
            'route' => ['info.update', $info->id],
            'method' => 'PUT',
            'categoryList' => $categoryList,
            'title' => __('info.edit_title')
        ]);
    }

    public function update(Request $request, Info $info)
    {
        $requestData = $request->validate([
            'title' => 'required|string|max:255',
            'category_info_id' => 'required|exists:category_infos,id',
            'date' => 'required|date_format:Y-m-d\TH:i',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'reminder_date' => 'nullable|date_format:Y-m-d',
        ]);

        \Log::info('Updating Info:', $requestData);

        if ($request->hasFile('photo')) {
            $this->deletePhoto($info->photo); // Delete old photo from Cloudinary
            $validatedData['photo'] = $this->storePhoto($request->file('photo'));
        }

        $info->update($requestData + ['updated_by' => auth()->id()]);

        // Add reminder to session using the date field with formatting
        if ($request->has('date') && $request->has('reminder_date')) {
            // Parse the date and reminder_date from the request
            $eventDate = Carbon::parse($requestData['date']);
            $reminderDate = Carbon::parse($requestData['reminder_date']);

            // Check if the current date is equal to the reminder_date
            if ($reminderDate->isToday() && $reminderDate->isSameDay(Carbon::now())) {
                // Format the event date for display
                $formattedEventDate = $eventDate->format('j/n/Y g:i A');

                session()->push('reminders', __("info.event_reminder", [
                    'title' => $requestData['title'],
                    'date' => $formattedEventDate,
                ]));
            }
        }

        flash(__('info.updated'))->success();
        return redirect()->route('info.index');
    }

    public function destroy(info $info)
    {
        $this->deletePhoto($info->photo);
        $info->delete();

        flash(__('info.deleted'))->success();
        return redirect()->route('info.index');
    }

        /**
     * Store uploaded photo on Cloudinary and return the URL.
     */
    protected function storePhoto($image)
    {
        $result = Cloudinary::upload($image->getRealPath(), [
            'folder' => 'infos',
        ]);

        return $result->getSecurePath(); // Secure URL from Cloudinary
    }

    /**
     * Delete photo from Cloudinary if it exists.
     */
    protected function deletePhoto($photo)
    {
        if ($photo) {
            // Extract the public ID from the URL
            $publicId = basename(parse_url($photo, PHP_URL_PATH), '.' . pathinfo($photo, PATHINFO_EXTENSION));
            Cloudinary::destroy('infos/' . $publicId);
        }
    }

    public function exportPDF()
    {
        $info = Info::MosqueUser()->get();
        $mosqueName = optional(auth()->user()->mosque)->name ?? __('info.no_mosque_assigned');

        $pdf = Pdf::loadView('info_pdf', compact('info', 'mosqueName'));
        return $pdf->download('info_list.pdf');
    }


    public function removeAll(Request $request)
    {
        // Clear all reminders from the session
        session()->forget('reminders');

        return response()->json(['success' => true]);
    }

    public function infoAnalysis()
    {
        return view('info_analysis', ['title' => __('info.analysis_title')]);
    }

    public function fetchPieChartData(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', null); // Get the month from the request, default to null (all months)

        // Fetch the data for the pie chart filtered by year and optionally by month
        $infoQuery = Info::with('category')
            ->where('mosque_id', auth()->user()->mosque_id)
            ->whereYear('date', $year);

        if ($month) {
            $infoQuery->whereMonth('date', $month); // Apply month filter if provided
        }

        $infoData = $infoQuery->get();

        $categories = $infoData->groupBy('category_info_id');
        $labels = [];
        $values = [];

        foreach ($categories as $categoryId => $items) {
            $category = CategoryInfo::find($categoryId);
            if ($category) {
                $labels[] = $category->name;
                $values[] = $items->count(); // Count of infos in each category
            }
        }

        $monthNames = getMonthNames();

        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'totalEntries' => $infoData->count(),
            'month' => $month ? ($monthNames[str_pad($month, 2, '0', STR_PAD_LEFT)] ?? __('cashflow.all_months')) : __('cashflow.all_months'),
        ]);
    }

    public function lineChart(Request $request)
    {
        // Get the year from the request, default to the current year
        $year = $request->input('year', now()->year);

        // Validate the year (optional but recommended)
        if (!is_numeric($year) || $year < 2000 || $year > now()->year) {
            return response()->json(['error' => 'Invalid year provided.'], 400);
        }

        // Query to count the number of rows (count of IDs) grouped by month
        $lineData = Info::select(
            \DB::raw('MONTH(created_at) as month'), // Use numeric month for ordering
            \DB::raw('COUNT(id) as total') // Count IDs
        )
            ->where('mosque_id', auth()->user()->mosque_id)
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month'); // Retrieve totals indexed by month number

        // Define all months using the helper function
        $monthNames = collect(array_values(getMonthNames()));

        // Map the totals to month names, defaulting to 0 if no data
        $totals = collect(range(1, 12))->map(function ($month) use ($lineData) {
            return $lineData[$month] ?? 0; // Use numeric month for lookup
        })->toArray(); // Convert the Collection to an array

        // Return the month names and totals as JSON
        return response()->json([
            'months' => $monthNames, // Return translated month names
            'totals' => array_map('intval', $totals), // Ensure totals are integers
        ]);
    }

    public function calendarEvents()
    {
        $events = Info::all(); // Fetch all events from the `info` table

        $formattedEvents = $events->map(function ($event) {
            return [
                'title' => $event->title, // Event title will be used as the tooltip
                'start' => $event->date, // Start date
                'description' => $event->description, // Add description if needed
            ];
        });

        return response()->json($formattedEvents);
    }






}
