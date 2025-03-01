<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\PaymentSetting;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class EnrollmentController extends Controller
{
    private $paypal;

    public function __construct()
    {
        // Initialize PayPal client with API credentials
        $this->paypal = new PayPalClient;
        $this->paypal->setApiCredentials(config('paypal'));
        $this->paypal->setAccessToken($this->paypal->getAccessToken());
    }

    /**
     * Get all enrollments for the authenticated user
     */
    public function myEnrollments(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with('course')
            ->paginate($perPage);

        return response()->json([
            'enrollments' => $enrollments,
        ], 200);
    }

    /**
     * Get details of a specific enrollment
     */
    public function show($id)
    {
        $enrollment = Enrollment::with('course')->find($id);

        if (!$enrollment) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }

        return response()->json($enrollment, 200);
    }

    /**
     * Create a new enrollment
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'payment_method' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'total' => 'required|numeric',
        ]);

        $course = Course::find($validated['course_id']);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        // Check if the user is already enrolled
        if (Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'You are already enrolled in this course'], 400);
        }

        // Create enrollment record
        $enrollment = Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'payment_method' => $validated['payment_method'],
            'total' => $validated['total'],
            'status' => 'pending', // Default status before payment confirmation
        ]);

        return response()->json([
            'message' => 'Enrollment initiated successfully!',
            'enrollment' => $enrollment,
        ], 201);
    }

    /**
     * Create Stripe payment for enrollment
     */
    public function createStripePayment(Request $request)
    {
        try {
            $totalAmount = $request->input('total');
            $amountInCents = $totalAmount * 100;

            $stripeSettings = PaymentSetting::where('provider', 'stripe')->where('enabled', true)->first();
            if (!$stripeSettings || !$stripeSettings->secret_key) {
                return response()->json(['error' => 'Stripe is not configured'], 500);
            }

            Stripe::setApiKey($stripeSettings->secret_key);

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            Log::info('Stripe payment intent created', ['payment_intent_id' => $paymentIntent->id]);

            return response()->json(['clientSecret' => $paymentIntent->client_secret], 200);
        } catch (\Exception $e) {
            Log::error('Error creating Stripe payment intent', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create payment intent'], 500);
        }
    }

    /**
     * Confirm PayPal payment for enrollment
     */
    public function confirmPaypalPayment(Request $request)
    {
        Log::info('Confirm PayPal Payment API called', ['request' => $request->all()]);

        $paypalOrderId = $request->paypalOrderId;

        if (!$paypalOrderId) {
            Log::error('PayPal Order ID is missing in the request.');
            return response()->json(['error' => 'PayPal Order ID is required'], 400);
        }

        try {
            $orderDetails = $this->paypal->showOrderDetails($paypalOrderId);
            Log::info('Order details fetched from PayPal.', ['orderDetails' => $orderDetails]);

            if (isset($orderDetails['status']) && $orderDetails['status'] === 'COMPLETED') {
                Log::info('PayPal order completed successfully.', ['paypalOrderId' => $paypalOrderId]);
                return response()->json(['status' => 'COMPLETED']);
            }

            Log::warning('PayPal order not completed.', ['orderDetails' => $orderDetails]);
            return response()->json(['status' => 'NOT_COMPLETED'], 400);
        } catch (\Exception $e) {
            Log::error('Error while confirming PayPal payment.', [
                'paypalOrderId' => $paypalOrderId,
                'exception' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Failed to confirm PayPal payment'], 500);
        }
    }
}
