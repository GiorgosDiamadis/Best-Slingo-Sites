const casinoReordering = () => {

    const lis = document.querySelectorAll("li[data-index]")

    const casinos = [];
    lis.forEach((li) => {
        casinos.push({
            name: li.lastElementChild.lastElementChild.innerHTML,
            id: li.lastElementChild.firstElementChild.value
        });
    })

    const draggables = document.querySelectorAll(".draggable");
    const dragListItems = document.querySelectorAll(".draggable-list li");
    var dragStartIndex = -1;


    function dragOver(e) {
        e.preventDefault()
    }


    function dragDrop() {
        const dragEndIndex = +this.getAttribute('data-index')
        swapItems(dragStartIndex, dragEndIndex)

        this.classList.remove('over')
    }

    function swapItems(dragStartIndex, dragEndIndex) {
        var name1;
        var id1;
        var id2 = '';
        var name2 = '';

        var index1, index2 = 0;

        if (dragStartIndex > dragEndIndex) {
            index1 = dragEndIndex - 1;
            index2 = dragStartIndex


            name1 = document.getElementById(`${dragStartIndex}`).lastElementChild.innerHTML;
            id1 = document.getElementById(`${dragStartIndex}`).firstElementChild.value;
        } else {
            index1 = dragStartIndex - 1;
            index2 = dragEndIndex;

            name1 = document.getElementById(`${dragEndIndex}`).lastElementChild.innerHTML;
            id1 = document.getElementById(`${dragEndIndex}`).firstElementChild.value;

        }

        for (let i = index1; i < index2; i++) {
            name2 = casinos[i].name;
            id2 = casinos[i].id;

            casinos[i].name = name1;
            casinos[i].id = id1;
            name1 = name2;
            id1 = id2;
        }


        const list = document.querySelectorAll(".draggable")

        list.forEach((item, i) => {
            item.lastElementChild.innerHTML = casinos[i].name;
            item.firstElementChild.value = casinos[i].id;
        })
    }


    function dragStart() {
        dragStartIndex = +this.closest('li').getAttribute('data-index')


    }

    if (draggables) {
        draggables.forEach((draggable) => {
            draggable.addEventListener('dragstart', dragStart);
        })

        dragListItems.forEach((item) => {
            item.addEventListener('dragover', dragOver);
            item.addEventListener('drop', dragDrop);
        })

    }
}

document.addEventListener('DOMContentLoaded', (e) => {
    casinoReordering()
})
// casinoReordering()
