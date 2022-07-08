const host = `${window.location.protocol + "//" + window.location.host}`;
const reOrder = () => {
    const draggables = document.querySelectorAll(".draggable");

    var route = `${host}/wp-json/slingo/v1/casino/reorder`;
    var data = [];
    draggables.forEach((draggable) => {
        const newOrder = draggable.getAttribute("id");
        const casinoId = draggable.firstElementChild.value;

        data.push({'newOrder': newOrder, 'casinoId': casinoId})
    })

    $.post(route,
        {
            data: data
        },
        function (msg) {
            console.log(msg)
        });
}