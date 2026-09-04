<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseApplication;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function index() { return view('admin.applications.index', ['applications' => CourseApplication::with(['course', 'payments'])->latest()->paginate(15)]); }
    public function show(CourseApplication $application) { $application->load(['course', 'payments']); return view('admin.applications.show', compact('application')); }
    public function update(Request $request, CourseApplication $application) { $application->update($request->validate(['status' => ['required', Rule::in(['submitted', 'reviewing', 'accepted', 'rejected', 'enrolled'])]])); return back()->with('success', 'Application updated.'); }
    public function updatePayment(Request $request, Payment $payment) { $data = $request->validate(['status' => ['required', Rule::in(['pending', 'paid', 'failed', 'refunded'])]]); $data['paid_at'] = $data['status'] === 'paid' ? now() : null; $payment->update($data); return back()->with('success', 'Payment updated.'); }
}
