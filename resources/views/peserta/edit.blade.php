@extends('layouts.app')

@section('title', 'Edit Peserta')

@section('content')
    <div class="w-full max-w-xs mx-auto">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('peserta.update', $peserta->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="badge_no">
                    Badge No
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    name="badge_no" id="badge_no" type="text" value="{{ old('badge_no', $peserta->badge_no) }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="employee_name">
                    Employee Name
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="employee_name" type="text" name="employee_name" value="{{ old('employee_name', $peserta->employee_name) }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="dept">
                    Dept
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="dept" name="dept" type="text" value="{{ old('dept', $peserta->dept) }}">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="position">
                    Position
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="position" type="text" name="position" value="{{ old('position', $peserta->position) }}">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="position">
                    Join Date
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="join_date" name="join_date" type="date" value="{{ old('join_date', $peserta->join_date) }}">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Status
                </label>
                <select
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="status" name="status">
                    <option value="Active" {{ old('status', $peserta->status) == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Non Active" {{ old('status', $peserta->status) == 'Non Active' ? 'selected' : '' }}>Non Active</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="position">
                    Category Level
                </label>
                <input
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="category_level" name="category_level" type="text" value="{{ old('category_level', $peserta->category_level) }}">
            </div>
            <div class="flex items-center justify-center">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Edit
                </button>
            </div>
        </form>
        <p class="text-center text-gray-500 text-xs">
            &copy;{{ date('Y') }} Acme Corp. All rights reserved.
        </p>
    </div>
@endsection
