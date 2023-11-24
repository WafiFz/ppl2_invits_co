@extends('client.layouts.app')

@section('content')   
<main class="py-6 bg-white grow">
    <div class="container grid gap-5 md:grid-cols-2 2xl:grid-cols-3">
        <div class="w-full border rounded-md border-brand-purple-300">
            <div class="flex items-center gap-4 p-2.5">
                <div class="grid rounded-md w-28 aspect-square place-items-center bg-brand-purple-500">
                    <i class="text-5xl text-white ph ph-user"></i>
                </div>
                <div class="border-b-md">
                    <h2 class="text-3xl text-brand-purple-500">{{ $data["users"]->count() }}</h2>
                    <span class="uppercase">Total Users</span>
                </div>
            </div>
            <x-button-a class="w-full text-white rounded-t-none bg-brand-purple-400 hover:bg-brand-purple-500">View More<i></i></x-button>
        </div>
        <div class="w-full border rounded-md border-brand-purple-300">
            <div class="flex items-center gap-4 p-2.5">
                <div class="grid rounded-md w-28 aspect-square place-items-center bg-brand-purple-500">
                    <i class="text-5xl text-white ph ph-user"></i>
                </div>
                <div class="border-b-md">
                    <h2 class="text-3xl text-brand-purple-500">{{ $data["packages"]->count() }}</h2>
                    <span class="uppercase">Total Packages</span>
                </div>
            </div>
            <x-button-a class="w-full text-white rounded-t-none bg-brand-purple-400 hover:bg-brand-purple-500">View More<i></i></x-button>
        </div>
        <div class="w-full border rounded-md border-brand-purple-300">
            <div class="flex items-center gap-4 p-2.5">
                <div class="grid rounded-md w-28 aspect-square place-items-center bg-brand-purple-500">
                    <i class="text-5xl text-white ph ph-user"></i>
                </div>
                <div class="border-b-md">
                    <h2 class="text-3xl text-brand-purple-500">{{ $data["themes"]->count() }}</h2>
                    <span class="uppercase">Total Income</span>
                </div>
            </div>
            <x-button-a class="w-full text-white rounded-t-none bg-brand-purple-400 hover:bg-brand-purple-500">View More<i></i></x-button>
        </div>
        <div class="w-full border rounded-md border-brand-purple-300">
            <div class="flex items-center gap-4 p-2.5">
                <div class="grid rounded-md w-28 aspect-square place-items-center bg-brand-purple-500">
                    <i class="text-5xl text-white ph ph-user"></i>
                </div>
                <div class="border-b-md">
                    <h2 class="text-3xl text-brand-purple-500">{{ $data["orders"]->count() }}</h2>
                    <span class="uppercase">Total Orders</span>
                </div>
            </div>
            <x-button-a class="w-full text-white rounded-t-none bg-brand-purple-400 hover:bg-brand-purple-500">View More<i></i></x-button>
        </div>
        <div class="w-full border rounded-md border-brand-purple-300">
            <div class="flex items-center gap-4 p-2.5">
                <div class="grid rounded-md w-28 aspect-square place-items-center bg-brand-purple-500">
                    <i class="text-5xl text-white ph ph-user"></i>
                </div>
                <div class="border-b-md">
                    <h2 class="text-3xl text-brand-purple-500">@rupiah($data["payments"])</h2>
                    <span class="uppercase">Total Income</span>
                </div>
            </div>
            <x-button-a class="w-full text-white rounded-t-none bg-brand-purple-400 hover:bg-brand-purple-500">View More<i></i></x-button>
        </div>
    </div>
</main>
@endsection