<x-dynamic-layout>
    <div>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Chat') }}
            </h2>
        </x-slot>

        <x-chat :chats="$chats" :activeChatId="$chatId" />
    </div>
</x-dynamic-layout>