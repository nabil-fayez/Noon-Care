<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\ErrorLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function patientIndex(Request $request)
    {
        try {
            $patient = Auth::guard('patient')->user();

            $invoices = $patient->invoices()
                ->with(['appointment.doctor', 'appointment.facility'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('patient.invoices.index', compact('invoices'));
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ أثناء جلب الفواتير: " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب الفواتير: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل فاتورة للمريض
     */
    public function patientShow(Request $request, Invoice $invoice)
    {
        try {
            // التحقق من أن الفاتورة تخص المريض الحالي
            if ($invoice->patient_id !== Auth::guard('patient')->user()->id) {
                abort(403, 'غير مصرح لك بالوصول إلى هذه الفاتورة');
            }

            $invoice->load(['appointment.doctor', 'appointment.facility', 'appointment.service']);

            return view('patient.invoices.show', compact('invoice'));
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ أثناء عرض الفاتورة: " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء عرض الفاتورة: ' . $e->getMessage());
        }
    }

    /**
     * معالجة دفع الفاتورة
     */
    public function patientPay(Request $request, Invoice $invoice)
    {
        try {
            // التحقق من أن الفاتورة تخص المريض الحالي
            if ($invoice->patient_id !== Auth::guard('patient')->user()->id) {
                abort(403, 'غير مصرح لك بدفع هذه الفاتورة');
            }

            // هنا يمكنك إضافة منطق الدفع (بطاقة ائتمان، محفظة إلكترونية، إلخ)
            // هذا مثال مبسط للتوضيح فقط

            $invoice->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);

            // إرسال إشعار بالدفع الناجح
            $this->notificationService->sendNotification(
                $invoice->patient_id,
                'patient',
                'دفع فاتورة',
                'تم دفع الفاتورة رقم ' . $invoice->id . ' بنجاح'
            );

            return redirect()->route('patient.invoices.show', $invoice)
                ->with('success', 'تم دفع الفاتورة بنجاح');
        } catch (\Exception $e) {
            ErrorLogService::logErrorLevel(
                "ظهر خطأ أثناء دفع الفاتورة: " . $e->getMessage(),
                $e,
                $request
            );
            return redirect()->back()->with('error', 'حدث خطأ أثناء دفع الفاتورة: ' . $e->getMessage());
        }
    }
}
