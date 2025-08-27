


<section class="my-8">
    <h2 class="text-2xl font-semibold mb-6">Grooming Packages</h2>
    @php
        $groomingPackages = isset($packages) ? $packages : ($profile->packages ?? []);
    @endphp
    @if($groomingPackages && count($groomingPackages))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($groomingPackages as $package)
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold mb-2">{{ $package->name }}</h3>
                        <p class="text-green-600 font-semibold mb-1">
                            @if(isset($package->price))
                                ${{ number_format($package->price, 2) }}
                            @else
                                Price on request
                            @endif
                        </p>
                        @if(!empty($package->duration))
                            <p class="text-sm text-gray-500 mb-1">Duration: {{ $package->duration }}</p>
                        @endif
                        <p class="text-gray-700 mb-4">{{ $package->short_description ?? '' }}</p>
                    </div>
                    <div class="mt-auto">
                        <a href="{{ route('grooming.book', [$profile->slug, 'package' => $package->id]) }}"
                           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                            Book Now
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 rounded">
            <p>No grooming packages are currently available.</p>
        </div>
    @endif
</section>