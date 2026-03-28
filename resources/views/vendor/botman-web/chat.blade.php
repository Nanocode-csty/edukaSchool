<!doctype html>
<html>
<head>
    <title>Asistente Eduka - Chat</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/botman-web-widget/build/assets/css/chat.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* ========== HEADER MEJORADO ========== */
        .botmanWidgetHeader {
            background: linear-gradient(135deg, #28AECE 0%, #1a8fb3 100%) !important;
            padding: 16px 20px !important;
            border-radius: 0 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .botmanWidgetHeader .botmanWidgetHeaderTitle {
            font-family: 'Quicksand', sans-serif !important;
            font-weight: 700 !important;
            font-size: 18px !important;
            color: white !important;
            letter-spacing: -0.2px !important;
        }

        .botmanWidgetHeader .botmanWidgetHeaderClose {
            color: white !important;
            opacity: 0.9 !important;
            transition: all 0.2s ease !important;
        }

        .botmanWidgetHeader .botmanWidgetHeaderClose:hover {
            opacity: 1 !important;
            transform: rotate(90deg) !important;
        }

        /* ========== ÁREA DE MENSAJES CON DEGRADADO ========== */
        .botmanChatWindow {
            background: linear-gradient(to bottom, #28AECE 0%, #4fc3dc 40%, #ffffff 100%) !important;
            border-radius: 0 !important;
        }

        .botmanChatWindow__messages {
            padding: 20px 16px !important;
            background: transparent !important;
            scroll-behavior: smooth !important;
        }

        /* Scrollbar personalizada */
        .botmanChatWindow__messages::-webkit-scrollbar {
            width: 6px !important;
        }

        .botmanChatWindow__messages::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.3) !important;
        }

        .botmanChatWindow__messages::-webkit-scrollbar-thumb {
            background: rgba(40, 174, 206, 0.5) !important;
            border-radius: 10px !important;
        }

        .botmanChatWindow__messages::-webkit-scrollbar-thumb:hover {
            background: rgba(40, 174, 206, 0.7) !important;
        }

        /* ========== MENSAJES CON ANIMACIÓN ========== */
        .botmanChatMessageContainer {
            animation: slideInMessage 0.3s ease-out !important;
            margin-bottom: 14px !important;
        }

        @keyframes slideInMessage {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mensajes del BOT */
        .botmanChatMessageContainer.botmanIncomingMessage .botmanChatMessage {
            background: white !important;
            color: #2d3748 !important;
            font-family: 'Quicksand', sans-serif !important;
            font-size: 14px !important;
            line-height: 1.5 !important;
            border-radius: 16px 16px 16px 4px !important;
            padding: 10px 14px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1) !important;
            max-width: 85% !important;
            word-wrap: break-word !important;
        }

        /* Mensajes del USUARIO */
        .botmanChatMessageContainer.botmanOutgoingMessage .botmanChatMessage {
            background: rgba(15, 62, 97, 0.9) !important;
            color: white !important;
            font-family: 'Quicksand', sans-serif !important;
            font-size: 14px !important;
            line-height: 1.5 !important;
            border-radius: 16px 16px 4px 16px !important;
            padding: 10px 14px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15) !important;
            max-width: 85% !important;
            word-wrap: break-word !important;
        }

        /* ========== ÁREA DE INPUT MEJORADA ========== */
        .botmanChatWindow__messageInputContainer {
            padding: 14px 16px !important;
            background: white !important;
            border-top: 1px solid #e2e8f0 !important;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.05) !important;
        }

        .botmanChatWindow__input {
            font-family: 'Quicksand', sans-serif !important;
            font-size: 14px !important;
            border: 2px solid #e2e8f0 !important;
            border-radius: 20px !important;
            padding: 10px 15px !important;
            background: #f8f9fc !important;
            color: #2d3748 !important;
            transition: all 0.2s ease !important;
        }

        .botmanChatWindow__input:focus {
            outline: none !important;
            border-color: #28AECE !important;
            background: white !important;
            box-shadow: 0 0 0 3px rgba(40, 174, 206, 0.1) !important;
        }

        .botmanChatWindow__input::placeholder {
            color: #a0aec0 !important;
        }

        /* Botón de enviar */
        .botmanChatWindow__submit {
            background: linear-gradient(135deg, #28AECE 0%, #1a8fb3 100%) !important;
            border: none !important;
            border-radius: 50% !important;
            width: 38px !important;
            height: 38px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 6px rgba(40, 174, 206, 0.3) !important;
        }

        .botmanChatWindow__submit:hover {
            transform: scale(1.1) !important;
            box-shadow: 0 4px 10px rgba(40, 174, 206, 0.4) !important;
        }

        .botmanChatWindow__submit:active {
            transform: scale(0.95) !important;
        }

        /* ========== INDICADOR DE ESCRITURA ========== */
        .botmanTypingIndicator {
            padding: 10px 14px !important;
            background: white !important;
            border-radius: 16px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1) !important;
        }

        .botmanTypingIndicator span {
            background: #cbd5e0 !important;
            animation: botmanTyping 1.4s infinite ease-in-out !important;
        }

        @keyframes botmanTyping {
            0%, 60%, 100% { 
                transform: translateY(0); 
                opacity: 0.7; 
            }
            30% { 
                transform: translateY(-6px); 
                opacity: 1; 
            }
        }

        /* ========== TIMESTAMP ========== */
        .botmanChatMessageTime {
            font-size: 11px !important;
            color: rgba(0, 0, 0, 0.4) !important;
            margin-top: 4px !important;
            font-family: 'Quicksand', sans-serif !important;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .botmanChatWindow__messages {
                padding: 16px 12px !important;
            }
        }

        /* ========== PERSONALIZACIÓN ADICIONAL ========== */
        .desktop-closed-message-avatar {
            background-color: #28AECE !important;
        }

        /* Avatar del bot */
        .botmanChatWindow__messageAvatar {
            width: 30px !important;
            height: 30px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, #28AECE 0%, #1a8fb3 100%) !important;
            box-shadow: 0 2px 4px rgba(40, 174, 206, 0.3) !important;
        }
    </style>
</head>
<body>
<div id="botmanWidgetRoot"></div>

<script id="botmanWidget" src='https://cdn.jsdelivr.net/npm/botman-web-widget/build/js/chat.js'></script>
<script>
    // Configuración inicial del chat
    var botmanWidget = {
        frameEndpoint: '/botman',
        chatServer: '/botman',
        title: 'Tío Edú',
        introMessage: '👋 ¡Hola! Soy tu asistente Tío Edú. ¿En qué puedo ayudarte hoy?',
        placeholderText: 'Escribe tu mensaje aquí...',
        mainColor: '#28AECE',
        bubbleBackground: 'transparent',
        headerTextColor: '#ffffff',
        bubbleAvatarUrl: '{{ asset('imagenes/imgTioEduka.png') }}',
        displayMessageTime: true,
        desktopHeight: 600,
        desktopWidth: 420,
        mobileHeight: '100%',
        mobileWidth: '100%',
        aboutText: 'Powered by Eduka Perú',
        aboutLink: '{{ url('/') }}'
    };

    // Auto-enviar mensaje de inicio después de cargar
    setTimeout(function() {
        try {
            var input = document.querySelector('.botmanChatWindow input[type="text"]') ||
                       document.querySelector('input.botmanChatWindow__input') ||
                       document.querySelector('input');

            var button = document.querySelector('.botmanChatWindow button[type="submit"]') ||
                        document.querySelector('button.botmanChatWindow__submit') ||
                        document.querySelector('button');

            if (input && button) {
                input.value = 'start';
                button.click();
                console.log('✅ Chat iniciado');
            } else {
                setTimeout(function() {
                    var retryInput = document.querySelector('input');
                    var retryButton = document.querySelector('button');
                    if (retryInput && retryButton) {
                        retryInput.value = 'start';
                        retryButton.click();
                    }
                }, 1000);
            }
        } catch (error) {
            console.error('Error al iniciar chat:', error);
        }
    }, 2000);

    // Mejorar scroll suave
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🎨 Asistente Eduka cargado');
        
        setTimeout(() => {
            const messages = document.querySelector('.botmanChatWindow__messages');
            if (messages) {
                messages.style.scrollBehavior = 'smooth';
                
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.addedNodes.length) {
                            setTimeout(() => {
                                messages.scrollTo({
                                    top: messages.scrollHeight,
                                    behavior: 'smooth'
                                });
                            }, 100);
                        }
                    });
                });
                
                observer.observe(messages, {
                    childList: true,
                    subtree: true
                });
            }
        }, 2500);
    });
</script>
</body>
</html>
