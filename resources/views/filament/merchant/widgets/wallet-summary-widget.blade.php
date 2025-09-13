<x-filament-widgets::widget class="theme-transition">
    <x-filament::section>
        <x-slot name="heading">
            Wallet Summary
        </x-slot>

        @php
            $wallet = $this->getWalletData();
        @endphp

        @if($wallet)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-100 dark:border-green-800/30 theme-transition">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 dark:bg-green-600 rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-white font-bold">💰</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Available Balance</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">RM {{ number_format($wallet['balance'], 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800/30 theme-transition">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 dark:bg-yellow-600 rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-white font-bold">⏳</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Pending Balance</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">RM {{ number_format($wallet['pending_balance'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($wallet['transactions']) && count($wallet['transactions']) > 0)
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Recent Transactions</h4>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach($wallet['transactions'] as $transaction)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-100 dark:border-gray-700/50 theme-transition hover:bg-gray-100 dark:hover:bg-gray-800">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                        {{ $transaction['description'] ?? "Transaction #{$transaction['transaction_id']}" }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y H:i') }}</p>
                                </div>
                                <div class="text-right ml-4 flex-shrink-0">
                                    <p class="font-medium {{ $transaction['type'] === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $transaction['type'] === 'credit' ? '+' : '-' }}RM {{ number_format($transaction['amount'], 2) }}
                                    </p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        {{ $transaction['status'] === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                        {{ ucfirst($transaction['status']) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-6">
                <div class="w-12 h-12 mx-auto mb-3 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <span class="text-gray-400 dark:text-gray-500 text-2xl">💳</span>
                </div>
                <p class="text-gray-500 dark:text-gray-400">Wallet data not available</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Please check your connection</p>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>