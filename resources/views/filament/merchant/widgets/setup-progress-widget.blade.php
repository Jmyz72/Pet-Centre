<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                @if($this->getCompletionPercentage() === 100)
                    <x-heroicon-o-check-circle class="h-5 w-5 text-gray-600" />
                @else
                    <x-heroicon-o-clipboard-document-check class="h-5 w-5 text-primary-600" />
                @endif
                <span>
                    Business Setup Progress
                    @if($this->getCompletionPercentage() === 100)
                        <span class="ml-2 text-xs font-normal text-gray-600">✓ Complete</span>
                    @endif
                </span>
            </div>
        </x-slot>

        <div class="space-y-6">
            {{-- Progress Bar --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium {{ $this->getCompletionPercentage() === 100 ? 'text-green-600' : 'text-gray-700' }}">
                        Setup Completion
                    </span>
                    <span class="text-sm font-medium {{ $this->getCompletionPercentage() === 100 ? 'text-green-600' : 'text-gray-700' }}">
                        {{ $this->getCompletionPercentage() }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
                    <div class="h-3 rounded-full transition-all duration-500 ease-in-out" 
                         style="width: {{ $this->getCompletionPercentage() }}%; background-color: #22c55e;">
                    </div>
                </div>
            </div>

            {{-- Completion Message --}}
            @if($this->getCompletionPercentage() === 100)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center">
                        <x-heroicon-o-check-circle class="h-5 w-5 text-gray-600 dark:text-gray-400 mr-2" />
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                            Congratulations! Your business setup is complete and ready to accept bookings.
                        </p>
                    </div>
                </div>
            @else
                <div class="bg-warning-50 border border-warning-200 rounded-lg p-4 dark:bg-warning-900/20 dark:border-warning-700">
                    <div class="flex items-center">
                        <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-warning-600 dark:text-warning-400 mr-2" />
                        <p class="text-sm font-medium text-warning-800 dark:text-warning-200">
                            Complete the remaining steps to start accepting bookings from customers.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Setup Steps --}}
            <div class="space-y-3">
                @foreach($this->getSetupSteps() as $index => $step)
                    <div class="flex items-center p-4 border rounded-lg transition-colors {{ $step['completed'] ? 'bg-gray-50 border-gray-300 dark:bg-gray-800 dark:border-gray-600' : 'bg-gray-50 border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700' }}">
                        {{-- Step Number/Check --}}
                        <div class="flex-shrink-0 mr-4">
                            @if($step['completed'])
                                <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                    <x-heroicon-o-check class="h-4 w-4 text-white" />
                                </div>
                            @else
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm font-medium text-gray-600 dark:bg-gray-600 dark:text-gray-300">
                                    {{ $index + 1 }}
                                </div>
                            @endif
                        </div>

                        {{-- Step Icon --}}
                        <div class="flex-shrink-0 mr-4">
                            @svg($step['icon'], 'h-6 w-6 text-gray-500 dark:text-gray-400')
                        </div>

                        {{-- Step Content --}}
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium {{ $step['completed'] ? 'text-gray-800 dark:text-gray-200' : 'text-gray-900 dark:text-gray-100' }}">
                                {{ $step['title'] }}
                            </h4>
                            <p class="text-sm {{ $step['completed'] ? 'text-gray-600 dark:text-gray-400' : 'text-gray-500 dark:text-gray-400' }}">
                                {{ $step['description'] }}
                            </p>
                        </div>

                        {{-- Action Button --}}
                        <div class="flex-shrink-0 ml-4">
                            <a href="{{ $step['url'] }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md {{ $step['completed'] ? 'text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600' : 'text-primary-700 bg-primary-100 hover:bg-primary-200 dark:text-primary-200 dark:bg-primary-900/20 dark:hover:bg-primary-900/30' }} transition-colors">
                                @if($step['completed'])
                                    View
                                @else
                                    Setup
                                @endif
                                <x-heroicon-o-arrow-right class="ml-1 h-3 w-3" />
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Next Steps Suggestion --}}
            @php
                $nextStep = $this->getSetupSteps()->where('completed', false)->first();
            @endphp
            
            @if($nextStep && $this->getCompletionPercentage() < 100)
                <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 dark:bg-primary-900/20 dark:border-primary-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-primary-800 dark:text-primary-200">
                                Next: {{ $nextStep['title'] }}
                            </h4>
                            <p class="text-sm text-primary-600 dark:text-primary-300">
                                {{ $nextStep['description'] }}
                            </p>
                        </div>
                        <a href="{{ $nextStep['url'] }}" 
                           class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 transition-colors">
                            Start Now
                            <x-heroicon-o-arrow-right class="ml-1 h-3 w-3" />
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
