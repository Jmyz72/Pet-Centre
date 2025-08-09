<x-filament::page>
    <x-filament::card>
        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Name</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $record->name }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Role</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $record->role }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $record->phone }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $record->status }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Registration No.</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $record->registration_number }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Address</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $record->address }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">License Number</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $record->license_number }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Document Path</dt>
                <dd class="mt-1 text-sm">
                    <a href="{{ Storage::url($record->document_path) }}" target="_blank" class="text-blue-600 underline">
                        View Uploaded Document
                    </a>
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Rejection Reason</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $record->rejection_reason ?? 'N/A' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Can Reapply</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $record->can_reapply ? 'Yes' : 'No' }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Submitted At</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $record->created_at->format('Y-m-d H:i') }}</dd>
            </div>
        </dl>
    </x-filament::card>
</x-filament::page>
