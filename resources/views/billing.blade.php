Payment History

@extends('layouts.app')

@section('title', 'Payment History')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Payment History</h1>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Services</th>
                    <th class="px-4 py-3 text-left">Payment Method</th>
                    <th class="px-4 py-3 text-right">Total (RM)</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <!-- Example Row 1 -->
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">2025-09-01</td>
                    <td class="px-4 py-3">
                        Groomer (Premium) <br>
                        Shelter (Buddy) <br>
                        Clinic (Checkup)
                    </td>
                    <td class="px-4 py-3">Online Banking</td>
                    <td class="px-4 py-3 text-right tabular-nums">477.00</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            Paid
                        </span>
                    </td>
                </tr>

                <!-- Example Row 2 -->
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">2025-08-20</td>
                    <td class="px-4 py-3">
                        Shelter (Milo) <br>
                        Clinic (Vaccination)
                    </td>
                    <td class="px-4 py-3">Touch ’n Go eWallet</td>
                    <td class="px-4 py-3 text-right tabular-nums">330.00</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            Paid
                        </span>
                    </td>
                </tr>

                <!-- Example Row 3 -->
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">2025-08-10</td>
                    <td class="px-4 py-3">
                        Groomer (Basic) <br>
                        Clinic (Surgery)
                    </td>
                    <td class="px-4 py-3">FPX</td>
                    <td class="px-4 py-3 text-right tabular-nums">620.00</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            Paid
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
