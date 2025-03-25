<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chat') }}
        </h2>
    </x-slot>

    <div class="py-12 flex flex-grow max-h-[80dvh]">
        <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full flex flex-col">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex-grow overflow-auto">
                    <div class="chat">
                        <p>🤖: Hi {{ Auth::user()->name }}!</p>
                    </div>
                </div>

                <form class="p-6" id="message" action="{{ route('dashboard.postMessage') }}" method="POST">
                    @csrf
                    <div class="flex items-center space-x-2">
                        <input type="text" id="message" name="message" autocomplete="off" class="w-full border rounded p-2 text-blue-950" placeholder="Type a message...">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Send</button>
                        <a href="?truncate_chat" type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const chat = document.querySelector('.chat');
    const form = document.querySelector('form#message');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const message = formData.get('message');
        if (!message) {
            return;
        }
        chat.innerHTML += `<p>👤: ${message}</p>`;
        chat.innerHTML += `<p id="thinking">🤖: ...</p>`;

        form.reset();
        document.querySelector('#message').setAttribute('disabled', 'disabled');
        document.querySelector('#message').classList.add('opacity-60');

        const response = await fetch(form.action, {
            method: form.method,
            body: formData
        });

        const data = await response.json();

        if (data.isPlainText) {
            printResponse(data.message);
        } else {
            chat.innerHTML += `<p>🤖: ${data.message}</p>`;
            enableChat();
        }
    });

    var printResponse = function (message) {
        chat.innerHTML += `<p>🤖: <span id="response"></span></p>`;
        let index = 0;
        var interval = setInterval(() => {
            document.getElementById('response').innerHTML += message[index++];
            if (index === message.length) {
                clearInterval(interval);
                document.querySelector('#response').removeAttribute('id');
                enableChat();
            }
        }, 10);
    }

    var enableChat = function () {
        document.querySelector('#thinking').remove();
        document.querySelector('#message').removeAttribute('disabled');
        document.querySelector('#message').classList.remove('opacity-60');
    }
</script>

<style>
    .chat {
        ol, ul, menu {
            list-style: revert;
            margin: revert;
            padding: revert;
        }
    }
</style>
