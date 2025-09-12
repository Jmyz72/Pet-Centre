@csrf
<div class="bg-gray-50 p-6 rounded-lg shadow-md">
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div>
        <label class="text-sm font-medium">Name</label>
        <input name="name" value="{{ old('name', $pet->name ?? '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2" required>
        @error('name')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Type</label>
        <select id="pet_type_id" name="pet_type_id" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2" required>
            <option value="">Select…</option>
            @foreach($types as $id => $name)
                <option value="{{ $id }}" @selected(old('pet_type_id', $pet->pet_type_id ?? '')==$id)>{{ $name }}</option>
            @endforeach
        </select>
        @error('pet_type_id')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Breed (optional)</label>
        <select id="pet_breed_id" name="pet_breed_id" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2">
            <option value="">—</option>
            @foreach($types as $typeId => $typeName)
                @php $list = ($breedGroups[$typeId] ?? collect()); @endphp
                @if($list->isNotEmpty())
                    <optgroup label="{{ $typeName }}">
                        @foreach($list as $breed)
                            <option value="{{ $breed->id }}" @selected(old('pet_breed_id', $pet->pet_breed_id ?? '') == $breed->id)>
                                {{ $breed->name }}
                            </option>
                        @endforeach
                    </optgroup>
                @endif
            @endforeach
        </select>
        @error('pet_breed_id')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>


    <div>
        <label class="text-sm font-medium">Sex</label>
        <select name="sex" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2" required>
            @foreach(['male'=>'Male','female'=>'Female','unknown'=>'Unknown'] as $v=>$label)
                <option value="{{ $v }}" @selected(old('sex', $pet->sex ?? 'unknown')==$v)>{{ $label }}</option>
            @endforeach
        </select>
        @error('sex')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Birthdate</label>
        <input type="date" name="birthdate" value="{{ old('birthdate', optional($pet->birthdate ?? null)->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2">
        @error('birthdate')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="text-sm font-medium">Weight (kg)</label>
        <input id="weight_kg" name="weight_kg" value="{{ old('weight_kg', $pet->weight_kg ?? '') }}" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2">
        <p class="mt-1 text-xs text-gray-500">Size is automatically determined from weight.</p>
        @error('weight_kg')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>

    <div class="sm:col-span-2">
        <label class="text-sm font-medium">Photo</label>
        <input type="file" name="photo" accept="image/*" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2">
        @error('photo')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>

    <div class="sm:col-span-2">
        <label class="text-sm font-medium">Description</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2">{{ old('description', $pet->description ?? '') }}</textarea>
        @error('description')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('customer.pets.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-100">Cancel</a>
    <button class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 shadow">Save</button>
</div>
</div>
