function getChatId() {
    return location.pathname.substring(1);
}

function isChatPage() {
    return /^((\S+?-){4})\S+$/.test(getChatId());
}

function isMyMessage(message) {
    return userId === message.sender.id;
}

function getPublishedAt(timestamp) {
    let date = new Date(timestamp * 1000);

    return date.getFullYear() + '.' +
        (date.getMonth() + 1) + '.' +
        date.getDay() + ' ' +
        date.getHours() + ':' +
        date.getMinutes() + ':' +
        date.getSeconds()
        ;
}

function jsonDecode(json) {
    return JSON.parse(json);
}

function jsonEncode(data) {
    return JSON.stringify(data);
}