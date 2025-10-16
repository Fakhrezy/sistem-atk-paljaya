{{-- Dropdown bidang yang konsisten --}}
<select name="{{ $name }}" id="{{ $id ?? $name }}"
        class="{{ $class ?? 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500' }}"
        {{ $required ?? false ? 'required' : '' }}>
    <option value="">{{ $placeholder ?? 'Pilih Bidang' }}</option>
    @foreach(\App\Constants\BidangConstants::getBidangList() as $key => $label)
        <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>{{ $label }}</option>
    @endforeach
</select>
