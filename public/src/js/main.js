var client = new StompJs.Client({
    brokerURL: rabbitmq.url,
    connectHeaders: {
        login: rabbitmq.login,
        passcode: rabbitmq.password
    },
    reconnectDelay: 5000,
    heartbeatIncoming: 4000,
    heartbeatOutgoing: 4000,
});

var sendMessageTimeout;
var receiveMessageWriteTimeout;
var sendWriteTimeout;

function getChat() {
    return $('.direct-chat-messages');
}

function downChat() {
    let chat = getChat();

    chat.scrollTop(chat[0].scrollHeight);
}

function addToChat(block) {
    let chat = getChat();

    chat.append(block);

    downChat();
}

function getWriteNotice() {
    return $('#write');
}

function hideWriteNotice() {
    getWriteNotice().hide();
}

function makeWriteNotice(senderFullName) {
    let oldWriteNotice = getWriteNotice();
    let newWriteNotice = oldWriteNotice.clone();

    newWriteNotice.find('p span').html(senderFullName);
    newWriteNotice.show();

    oldWriteNotice.remove();

    return newWriteNotice;
}

function receive(message) {
    let body = jsonDecode(message.body);

    if (body.type !== undefined) {
        if (body.sender.id !== userId) {
            clearTimeout(receiveMessageWriteTimeout);

            addToChat(makeWriteNotice(body.sender.fullName));

            receiveMessageWriteTimeout = setTimeout(function () {
                hideWriteNotice();
            }, 4000);
        }
    } else {
        hideWriteNotice();

        addMessage(body);
    }

    clearTimeout(sendMessageTimeout);

    message.ack(message);
}

function addMessage(message) {
    var newMessage;

    if (isMyMessage(message)) {
        newMessage = $('.direct-chat-msg + .right').first().clone();
    } else {
        newMessage = $('.direct-chat-msg').not('.right').first().clone();
    }

    $(newMessage).find('.direct-chat-text').html(message.text);
    $(newMessage).find('.direct-chat-name ').html(message.sender.fullName);
    $(newMessage).find('.direct-chat-timestamp').html(getPublishedAt(message.time.timestamp));
    let avatar = $(newMessage).find('.direct-chat-img').attr('src').split(/img\//)[0] + 'img/' + message.sender.avatar;

    $(newMessage).find('.direct-chat-img').attr('src', avatar);

    addToChat(newMessage);
}

function disconnect() {
    client.deactivate();
}

function connect() {
    client.onConnect = function () {
        if (isChatPage()) {
            let chat = getChatId();

            let connectSubscribe = client.subscribe('/topic/' + chat, function (response) {
                if (response.body === chat) {
                    $('#btn_send').removeAttr('disabled');

                    client.subscribe('/exchange/' + response.body, receive, {ack: 'client-individual'});

                    connectSubscribe.unsubscribe();
                }
            });

            client.publish({destination: '/amq/queue/connect_to_chat', body: chat})
        }
    };

    client.onWebSocketClose = function () {
        $('#btn_send').attr('disabled', 'on');
    };

    client.activate();
}

function sendWrite() {
    if (client.connected) {

        clearTimeout(sendWriteTimeout);

        sendWriteTimeout = setTimeout(function () {
            client.publish({
                destination: '/exchange/' + getChatId(),
                body: jsonEncode({type: 'write', sender: {id: userId, fullName: userFullName}})
            });
        }, 500);
    }
}

function send() {
    let messageInput = $('#message');
    let text = messageInput.val();

    if (text === '') {
        return;
    }

    let body = {
        text: text,
        sender: {
            id: userId,
            fullName: userFullName,
            avatar: userAvatar
        },
        time:
            {
                timestamp: Math.floor((new Date()).getTime() / 1000),
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
            },
        chat: getChatId()
    };

    client.publish({
        destination: '/amq/queue/messages',
        body: jsonEncode(body),
        headers: {
            persistent: true,
        }
    });

    sendMessageTimeout = setTimeout(function () {
        $('.alert').show();

        setTimeout(function () {
            $('.alert').hide();
        }, 5000);
    }, 5000);

    messageInput.val('');
}

$(document).ready(function () {
    $('.direct-chat-timestamp').each(function (e, t) {
        let timestamp = $(t).html();

        $(t).html(getPublishedAt(timestamp));
    })

    downChat();
});