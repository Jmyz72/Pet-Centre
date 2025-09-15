<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach ($this->getWidgets() as $widget)
            @php
                $widgetInstance = $this->getWidget($widget);
                $columnSpan = $this->getWidgetData()[class_basename($widget)]['columnSpan'] ?? ['default' => 1];
            @endphp

            <div class="
                @if(isset($columnSpan['default']) && $columnSpan['default'] == 1)
                    col-span-1
                @else
                    col-span-full
                @endif
                @if(isset($columnSpan['md']))
                    md:col-span-{{ $columnSpan['md'] }}
                @endif
                @if(isset($columnSpan['xl']))
                    xl:col-span-{{ $columnSpan['xl'] }}
                @endif
            ">
                {{ $widgetInstance }}
            </div>
        @endforeach
    </div>

    {{-- Custom Dark Theme Styles --}}
    <style>
        /* Chart.js dark theme support */
        .dark .apexcharts-text,
        .dark .apexcharts-legend-text {
            fill: rgb(229 231 235) !important;
        }

        .dark .apexcharts-gridline {
            stroke: rgb(55 65 81) !important;
        }

        .dark .apexcharts-tooltip {
            background: rgb(31 41 55) !important;
            border: 1px solid rgb(55 65 81) !important;
            color: rgb(243 244 246) !important;
        }

        /* Custom scrollbar for dark theme */
        .dark ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .dark ::-webkit-scrollbar-track {
            background: rgb(31 41 55);
            border-radius: 3px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: rgb(55 65 81);
            border-radius: 3px;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: rgb(75 85 99);
        }

        /* Animation for theme switching */
        .theme-transition {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>
</x-filament-panels::page>