@extends('layouts.admin-layouts')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    
    <!-- Profile Section -->
    <div class="p-6 bg-gray-50 rounded-lg shadow">
      <h2 class="text-xl font-semibold mb-4">Profile</h2>
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <img src="https://via.placeholder.com/60" alt="Profile" class="w-16 h-16 rounded-full">
          <div>
            <h3 class="text-lg font-semibold">Musharof Chowdhury</h3>
            <p class="text-gray-500">Team Manager | Arizona, United States.</p>
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <button class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-300 text-gray-500 hover:bg-gray-200">
            <i class="fab fa-facebook-f"></i>
          </button>
          <button class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-300 text-gray-500 hover:bg-gray-200">
            <i class="fab fa-x-twitter"></i>
          </button>
          <button class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-300 text-gray-500 hover:bg-gray-200">
            <i class="fab fa-linkedin-in"></i>
          </button>
          <button class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-300 text-gray-500 hover:bg-gray-200">
            <i class="fab fa-instagram"></i>
          </button>
          <button class="px-4 py-2 flex items-center space-x-2 border rounded-lg text-gray-600 hover:bg-gray-200">
            <i class="fas fa-edit"></i>
            <span>Edit</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Personal Information -->
    <div class="mt-6 p-6 bg-white rounded-lg shadow">
      <div class="flex justify-between">
        <h2 class="text-xl font-semibold mb-4">Personal Information</h2>
        <button class="px-4 py-2 flex items-center space-x-2 border rounded-lg text-gray-600 hover:bg-gray-200">
          <i class="fas fa-edit"></i>
          <span>Edit</span>
        </button>
      </div>
      <div class="grid grid-cols-2 gap-6 text-gray-600">
        <div>
          <p class="text-gray-400 text-sm">First Name</p>
          <p class="font-semibold">Chowdhury</p>
        </div>
        <div>
          <p class="text-gray-400 text-sm">Last Name</p>
          <p class="font-semibold">Musharof</p>
        </div>
        <div>
          <p class="text-gray-400 text-sm">Email address</p>
          <p class="font-semibold">randomuser@pimjo.com</p>
        </div>
        <div>
          <p class="text-gray-400 text-sm">Phone</p>
          <p class="font-semibold">+09 363 398 46</p>
        </div>
        <div class="col-span-2">
          <p class="text-gray-400 text-sm">Bio</p>
          <p class="font-semibold">Team Manager</p>
        </div>
      </div>
    </div>

    <!-- Address Section -->
    <div class="mt-6 p-6 bg-white rounded-lg shadow">
      <div class="flex justify-between">
        <h2 class="text-xl font-semibold mb-4">Address</h2>
        <button class="px-4 py-2 flex items-center space-x-2 border rounded-lg text-gray-600 hover:bg-gray-200">
          <i class="fas fa-edit"></i>
          <span>Edit</span>
        </button>
      </div>
      <div class="grid grid-cols-2 gap-6 text-gray-600">
        <div>
          <p class="text-gray-400 text-sm">Country</p>
          <p class="font-semibold">United States</p>
        </div>
        <div>
          <p class="text-gray-400 text-sm">City/State</p>
          <p class="font-semibold">Arizona, United States.</p>
        </div>
        <div>
          <p class="text-gray-400 text-sm">Postal Code</p>
          <p class="font-semibold">ERT 2489</p>
        </div>
        <div>
          <p class="text-gray-400 text-sm">TAX ID</p>
          <p class="font-semibold">AS4568384</p>
        </div>
      </div>
    </div>

  </div>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection