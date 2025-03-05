<button {{ $attributes->merge(['class' => 'px-4 py-2 bg-blue-600 hover:bg-blue-700 hover:text-white rounded-xl transition duration-200']) }}>
    {{ $slot }}
</button>
