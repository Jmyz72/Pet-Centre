


<section class="max-w-3xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Clinic Services</h2>

    @php
        $services = $services ?? ($profile->services ?? []);
    @endphp

    @if (!empty($services) && count($services))
        <div class="grid grid-cols-1 gap-6">
            @foreach ($services as $service)
                <div class="bg-white rounded-lg shadow p-6 flex flex-col md:flex-row md:items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $service->name }}</h3>
                        <p class="text-gray-700 mt-1 mb-2">{{ $service->description }}</p>
                        <div class="flex flex-wrap items-center text-gray-600 space-x-4 text-sm">
                            <span class="font-medium">Price:</span>
                            <span class="text-green-600 font-semibold">
                                {{ isset($service->price) ? '₱' . number_format($service->price, 2) : 'Contact for pricing' }}
                            </span>
                            @if (!empty($service->duration))
                                <span class="ml-4">
                                    <span class="font-medium">Duration:</span>
                                    {{ $service->duration }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 md:ml-6">
                        <a
                            href="{{ route('appointments.create', [$profile->slug, 'service' => $service->id]) }}"
                            class="inline-block px-5 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition"
                        >
                            Book Appointment
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-5 rounded shadow text-center">
            <span class="font-medium">No services available at this clinic.</span>
        </div>
    @endif
</section>