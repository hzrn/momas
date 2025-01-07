<?php
namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;
use Google_Client;
use Google\Service\Forms as Google_Service_Forms;
use Google_Service_Sheets;

class GoogleFormController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $credentials = json_decode(env('GOOGLE_CREDENTIALS'), true);
        $this->client->setAuthConfig($credentials);
        $this->client->addScope([
            'https://www.googleapis.com/auth/forms.responses.readonly',
            'https://www.googleapis.com/auth/spreadsheets.readonly'
        ]);
    }

    public function handleFormSubmission(Request $request)
    {
        // Verify the webhook secret
        if (!$this->verifyWebhookRequest($request)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $formData = $request->all();

        // Map Google Form fields to your database fields
        $infoData = [
            'title' => $formData['entry.1965117725'] ?? null, // Updated question ID
            'category_info_id' => $this->mapCategoryFromResponse($formData['entry.1979175233'] ?? ''), // Updated question ID
            'date' => $this->formatDate($formData['entry.695259417'] ?? ''), // Updated question ID
            'description' => $formData['entry.919011683'] ?? null, // Updated question ID
            'mosque_id' => $this->getMosqueIdFromResponse($formData),
            'created_by' => auth()->id() ?? 1, // Default system user ID
        ];

        // Create new Info record
        try {
            Info::create($infoData);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error('Google Form submission error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to process submission'], 500);
        }
    }

    protected function verifyWebhookRequest(Request $request)
    {
        // Get the webhook secret from the request headers
        $webhookSecret = $request->header('X-Webhook-Secret');
        $expectedSecret = config('services.google.webhook_secret');

        // Verify the secret
        if ($webhookSecret !== $expectedSecret) {
            \Log::warning('Invalid webhook secret received.');
            return false;
        }

        return true;
    }

    protected function mapCategoryFromResponse($categoryName)
    {
        // Map the category name from the form to your category_info_id
        return \App\Models\CategoryInfo::where('name', $categoryName)->first()->id ?? null;
    }

    protected function formatDate($dateString)
    {
        return \Carbon\Carbon::parse($dateString)->format('Y-m-d\TH:i');
    }

    protected function getMosqueIdFromResponse($formData)
    {
        // Logic to determine mosque_id from form response
        return $formData['entry.1070685759'] ?? null; // Updated question ID
    }

    // Method to fetch responses periodically if webhook isn't possible
    public function fetchFormResponses()
    {
        $formId = config('services.google.form_id');
        $service = new Google_Service_Forms($this->client);

        try {
            $responses = $service->forms_responses->listFormsResponses($formId);

            foreach ($responses->getResponses() as $response) {
                $this->processFormResponse($response);
            }

            return response()->json(['status' => 'success', 'processed' => count($responses)]);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch form responses: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch responses'], 500);
        }
    }

    protected function processFormResponse($response)
    {
        // Process each response and create Info record
        $answers = $response->getAnswers();

        $infoData = [
            'title' => $this->getAnswerByQuestionId($answers, 'entry.1965117725') ?? 'No Title', // Updated question ID
            'category_info_id' => $this->mapCategoryFromResponse(
                $this->getAnswerByQuestionId($answers, 'entry.1979175233') ?? 'No Category' // Updated question ID
            ),
            'date' => $this->formatDate(
                $this->getAnswerByQuestionId($answers, 'entry.695259417') ?? now()->toDateTimeString() // Updated question ID
            ),
            'description' => $this->getAnswerByQuestionId($answers, 'entry.919011683') ?? 'No Description', // Updated question ID
            'mosque_id' => $this->getMosqueIdFromResponse($answers) ?? 1, // Default mosque ID
            'created_by' => auth()->id() ?? 1, // Default system user ID
        ];

        Info::create($infoData);
    }

    protected function getAnswerByQuestionId($answers, $questionId)
    {
        if (!isset($answers[$questionId])) {
            \Log::warning('Question ID not found in answers:', ['questionId' => $questionId]);
            return null;
        }

        $answer = $answers[$questionId];

        if (!isset($answer->textAnswers) || !isset($answer->textAnswers->answers)) {
            \Log::warning('Invalid answer structure for question ID:', ['questionId' => $questionId]);
            return null;
        }

        return $answer->textAnswers->answers[0]->value ?? null;
    }
}
