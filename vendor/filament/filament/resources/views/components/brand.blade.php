@if (filled($brand = config('filament.brand')))
<div @class([ 'filament-brand text-xl font-bold tracking-tight' , 'dark:text-white'=> config('filament.dark_mode'),
    ])>
    <div class="flex justify-start">
        <img src="{{ asset('/Images/logo-rhu.png') }}" alt="Logo" class="h-10">
        <div class="flex items-center ml-1">{{ $brand }}</div>
    </div>
</div>
@endif