


class Helper {

    PrivateRoomConnect (roomId) {
        window.Echo.private('chat.' + roomId)
            .subscribed(() => {
                console.log('-- Private Chat -- ' + roomId + ' -- Subscribed..!');
            })
            .listen('.private.chat', (e) => {
                console.log('Private - Message - ', e);
            });
    }

}

export default new Helper();
