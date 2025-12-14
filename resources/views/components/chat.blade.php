<div>

    <style>
        /* Chat Layout */
        .chat-wrapper {
            height: calc(100vh - 80px);
            background: #f8f9fb;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #dcdcdc;
        }

        /* Sidebar */
        .chat-users {
            width: 28%;
            background: #ffffff;
            border-right: 1px solid #eee;
            overflow-y: auto;
        }

        .chat-user {
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-user.active {
            background: #0077b61a;
        }

        .chat-user:hover {
            background: #f6f6f6;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #e2f5ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            color: #0077b6;
        }

        /* Right Panel */
        .chat-area {
            width: 72%;
            display: flex;
            flex-direction: column;
            background: #eef2f7;
        }

        .chat-header {
            padding: 15px;
            background: #fff;
            border-bottom: 1px solid #ddd;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-body {

            padding: 20px;
            overflow-y: auto;
            background: #eef2f7;
        }

        /* Message bubbles */
        .msg {
            max-width: 65%;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 10px;
            display: inline-block;
            font-size: 15px;
            line-height: 1.4;
            word-break: break-word;
        }

        .msg-me {
            background: #0077b6;
            color: white;
            margin-left: auto;
            float: inline-end;
        }

        .msg-other {
            background: #ffffff;
            color: #333;
            border: 1px solid #ddd;
            float: inline-start;
        }

        .msg-time {
            font-size: 10px;
            color: #999;
            margin-top: 5px;
            text-align: right;
        }

        .msg-me .msg-time {
            color: #fff;
        }

        /* Input Bar */
        .chat-input {
            padding: 12px;
            background: #fff;
            border-top: 1px solid #ddd;
        }

        .input-group-custom {
            display: flex;
            align-items: center;
            background: #f1f3f5;
            padding: 10px;
            border-radius: 30px;
        }

        .input-group-custom input {
            border: none;
            background: transparent !important;
            flex: 1;
            padding-left: 10px;
            outline: none !important;
        }

        .btn-send {
            background: #0077b6;
            color: #fff;
            border-radius: 30px;
            padding: 10px 18px;
            border: none;
        }

        .btn-send:hover {
            background: #005f8c;
        }

        .file-btn {
            margin-right: 10px;
            cursor: pointer;
            font-size: 20px;
            color: #0077b6;
        }
    </style>

    <div class="container-fluid">
        <div class="chat-wrapper d-flex shadow">

            <!-- LEFT – USERS LIST -->
            <div class="chat-users">

                @forelse ($chats as $chat)
                    @php
                        $otherUser = $chat->sender_id == auth()->id() ? $chat->user2 : $chat->user1;
                    @endphp

                    <div class="chat-user" id="chat-{{ $chat->id }}"
                        onclick="loadChat({{ $chat->id }}, '{{ $otherUser->fullName }}')">
                        <div class="user-avatar">
                            {{ strtoupper($otherUser->nameInitials) }}
                        </div>
                        <div>
                            <div class="fw-bold">{{ $otherUser->fullName }}</div>
                            <small class="text-muted">Tap to open chat</small>
                        </div>
                    </div>
                @empty
                    <div class="d-flex align-items-center justify-content-center" style="height: 100%;">
                        <div class="">
                            No Chats
                        </div>
                    </div>
                @endforelse

            </div>

            <!-- RIGHT – CHAT AREA -->
            <div class="chat-area">
                <div class="chat-header" id="chatHeader">
                    Select a chat
                </div>

                <div class="chat-body" id="chatBody">
                    <p class="text-muted text-center mt-5">Select a user to start chat.</p>
                </div>

                <div class="chat-input">
                    <div class="input-group-custom">
                        <label class="file-btn">
                            <i class="bi bi-paperclip"></i>
                            <input type="file" id="fileInput" hidden>
                        </label>

                        <input type="text" id="messageInput" placeholder="Type your message...">

                        <button class="btn-send" onclick="sendMessage()">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

    <script>
        let activeChatId = null;
        @if (isset($activeChatId))

            activeChatId = "{{ $activeChatId }}";
            console.log('====================================');
            console.log(activeChatId);
            console.log('====================================');
            document.getElementById("chat-{{ $activeChatId }}").click();
        @endif

        // Load chat messages
        function loadChat(chatId, userName) {
            activeChatId = chatId;
            document.getElementById("chatHeader").innerHTML = userName;

            document.getElementById('chat-' + activeChatId).classList.add('active');
            //remove from other chats
            document.querySelectorAll('.chat-user').forEach(el => {
                if (el.id !== 'chat-' + activeChatId) {
                    el.classList.remove('active');
                }
            })

            fetch(`{{ url('/chat/messages') }}/${chatId}`)
                .then(res => res.json())
                .then(messages => {
                    let html = "";
                    messages.forEach(msg => {
                        html += renderMessage(msg);
                    });

                    document.getElementById("chatBody").innerHTML = html;
                    scrollToBottom();
                });
        }

        // Send message
        function sendMessage() {
            if (!activeChatId) return;

            let text = document.getElementById("messageInput").value.trim();
            let fileInput = document.getElementById("fileInput");

            let form = new FormData();
            form.append("chat_id", activeChatId);
            form.append("message", text);

            if (fileInput.files.length > 0) {
                form.append("file", fileInput.files[0]);
                form.append("type", "file");
            }

            fetch(`{{ url('/chat/send') }}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: form
                })
                .then(res => res.json())
                .then(msg => {
                    document.getElementById("chatBody").innerHTML += renderMessage(msg);
                    document.getElementById("messageInput").value = "";
                    document.getElementById("fileInput").value = "";
                    scrollToBottom();
                });
        }

        // Render message bubble
        function renderMessage(msg) {
            let isMe = msg.sender_id == {{ auth()->id() }};

            if (msg.type === "file") {
                return `
                <div class="d-block w-100">
            <div class="msg ${isMe ? 'msg-me' : 'msg-other'}">
                <a href="/storage/${msg.file}" target="_blank" class="text-white text-decoration-underline">
                    <i class="bi bi-paperclip"></i> Download File
                </a>
            </div></div>`;
            }

            return `
             <div class="d-inline-flex  w-100">
        <div class="msg ${isMe ? 'msg-me' : 'msg-other'}">
            ${msg.message}
            <div class="msg-time">${msg.human_time}</div>
        </div></div>`;
        }

        // Auto-scroll
        function scrollToBottom() {
            let chatBody = document.getElementById("chatBody");
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        // Pusher Listener
        window.Echo.private('chat.*')
            .listen('NewChatMessage', (e) => {
                if (e.message.chat_id == activeChatId) {
                    document.getElementById("chatBody")
                        .innerHTML += renderMessage(e.message);
                    scrollToBottom();
                }
            });
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });

        var channel = pusher.subscribe('');
        channel.bind('my-event', function(data) {
            alert(JSON.stringify(data));
        });
    </script>

</div>
