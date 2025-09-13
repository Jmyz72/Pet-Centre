<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Wallet Summary
        </x-slot>

        @php
            $wallet = $this->getWalletData();
        @endphp

        @if($wallet)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">💰</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Available Balance</p>
                            <p class="text-2xl font-bold text-green-600">RM {{ number_format($wallet['balance'], 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">⏳</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-600">Pending Balance</p>
                            <p class="text-2xl font-bold text-yellow-600">RM {{ number_format($wallet['pending_balance'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($wallet['transactions']) && count($wallet['transactions']) > 0)
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Recent Transactions</h4>
                    <div class="space-y-3">
                        @foreach($wallet['transactions'] as $transaction)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ $transaction['description'] ?? "Transaction #{$transaction['transaction_id']}" }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('M d, Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium {{ $transaction['type'] === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction['type'] === 'credit' ? '+' : '-' }}RM {{ number_format($transaction['amount'], 2) }}
                                    </p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $transaction['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
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
                <p class="text-gray-500">Wallet data not available</p>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>