@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm alert-flash">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm alert-flash">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 mr-3 text-xl"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-lg shadow-sm alert-flash">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-500 mr-3 text-xl"></i>
            <span>{{ session('info') }}</span>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-lg shadow-sm alert-flash">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 text-xl"></i>
            <span>{{ session('warning') }}</span>
        </div>
    </div>
@endif