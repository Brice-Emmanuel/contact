<footer class="bg-white border-t border-gray-200 py-4 px-6 mt-auto">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center text-sm text-gray-500">
        <p>
            &copy; {{ date('Y') }} Gestion de Contacts. Tous droits réservés.
        </p>
        <div class="flex items-center space-x-4 mt-2 sm:mt-0">
            <span class="flex items-center">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                <span>Version 1.0</span>
            </span>
            <span class="hidden sm:inline">•</span>
            <span class="hidden sm:inline">Laravel {{ Illuminate\Foundation\Application::VERSION }}</span>
        </div>
    </div>
</footer>