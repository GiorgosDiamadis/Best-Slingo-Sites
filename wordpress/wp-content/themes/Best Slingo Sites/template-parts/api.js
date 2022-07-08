const url = `${host}/wp-json/slingo/v1`;

const filterBtn = document.getElementById("filterButton");
if (filterBtn)
    filterBtn.addEventListener("click", (e) => {
        document
            .querySelector("div[data-filter-page]")
            .setAttribute("data-filter-page", "0");
        const bonus = document.getElementById("bonusSelection").value;
        const payment = document.getElementById("paymentSelection").value;
        const gameId = document
            .querySelector(".container[data-game-id]")
            .getAttribute("data-game-id");

        filter(e.target, "casino", ".casino-list", {
            bonuses: bonus,
            payment_methods: payment,
            games: gameId,
        });
    });

const searchInput = document.getElementById("search");

var delayTimer;
var spinner = document.querySelector("#search + .spinner-border");
var searchIcon = document.querySelector(".search > img");

var gameList = document.querySelector(".game-list");
var searchList = document.querySelector(".game-search-list");

var loadBtn = document.getElementById("loadmore");

if (searchInput) {
    searchInput.addEventListener("keyup", (e) => {
        clearTimeout(delayTimer);
        spinner.classList.remove("d-none");
        searchIcon.classList.add("d-none");

        if (e.target.value === "") {
            searchList.classList.add("d-none");

            gameList.classList.remove("d-none");
            spinner.classList.add("d-none");
            searchIcon.classList.remove("d-none");
            searchList.setAttribute("data-filter-page", 1);
            loadBtn.onclick = (e) => {
                loadMore(loadBtn, "game", ".game-list");
            };
            return;
        }
        delayTimer = setTimeout(() => {
            var filters = {like: e.target.value};
            var activeFilters = JSON.stringify(filters);

            searchList.setAttribute("active-filters", activeFilters);
            searchList.setAttribute("data-filter-page", 1);

            $.get(`${url}/game/search?like=${filters["like"]}`).then((data) => {
                spinner.classList.add("d-none");
                searchIcon.classList.remove("d-none");
                if (data.length !== 0) {
                    searchList.classList.remove("d-none");
                    gameList.classList.add("d-none");
                    loadBtn.onclick = (e) => {
                        loadMore(loadBtn, "game", ".game-search-list");
                    };
                    searchList.innerHTML = '';
                    data.forEach((d) => BuildGame(d, searchList, false, false))
                    setUpCasinoFinderGameSelection(".game-search-list");
                } else {
                    searchList.classList.add("d-none");
                    gameList.classList.remove("d-none");

                    loadBtn.onclick = (e) => {
                        loadMore(loadBtn, "game", ".game-list");
                    };
                }
            });
        }, 1000);
    });
}

const findCasinos = (e, type, cl) => {

    qsId("step-1").classList.add("d-none");
    qsId("step-2").classList.add("d-none");
    qsId("step-3").classList.add("d-none");
    qsId("cfResults").classList.remove("d-none");
    qsId("cfSpinner").classList.remove("d-none");

    var filters = JSON.parse(qsId("casinoFinder").getAttribute("active-filters"));

    filters["ui"] = "card";

    qsId('seeall').classList.remove('d-none');
    qsId('seeall').classList.add('block');
    filter(e, type, cl, filters, 3, true);

    const seeAll = qs("#seeall > a");

    var params = "";
    var link = "";

    Object.keys(filters).forEach((key, i) => {
        if (key === 'ui')
            return

        if (i > 0) {
            params += `&${key}=${filters[key]}`;

        } else {
            params += `${key}=${filters[key]}`;

        }
    })
    link = `${host}/casino-finder-results?${params}`

    seeAll.setAttribute('href', link)
};
const loadMore = (e, type, cl, next = true
    ) => {
        var listContainer = e.parentElement.querySelector(cl);
        var page = parseInt(listContainer.getAttribute("data-filter-page"));
        var dataAF = listContainer.getAttribute("active-filters");
        if (!page) {
            page = parseInt(listContainer.getAttribute("data-page"));
        }

        var query = "";

        dataAFObj = JSON.parse(dataAF);
        var queryParams = "";
        if (dataAFObj) {
            Object.keys(dataAFObj).forEach((item, i) => {
                if (i > 0) {
                    queryParams += "&";
                }
                queryParams += `${item}=${dataAFObj[item]}`;
            });


        }

        var dataInitGames = e.getAttribute('data-init-games');
        if (dataInitGames && qs(".game-search-list").classList.contains('d-none')) {
            queryParams += `&games_not=${dataInitGames}`
        }

        var pageParam = queryParams === "" ? `page=${next ? page + 1 : page - 1}` : `&page=${next ? page + 1 : page - 1}`;

        query = `${url}/${type}/search?${queryParams}${pageParam}`;


        $.get(query).then((data) => {

            const loading = e.childNodes[1];
            e.classList.remove('loading')

            if (loading) loading.classList.toggle("d-none");
            if (!next && page === 0)
                return

            if (dataAF && data.length !== 0) {
                listContainer.setAttribute("data-filter-page", (next ? ++page : --page).toString());
            } else if (data.length !== 0) {
                listContainer.setAttribute("data-page", (next ? ++page : --page).toString());
            }

            if (type === "casino") {
                data.forEach((d) => BuildCasinoListItem(d, listContainer))
            } else if (type === "game") {
                if (data.length > 0) {

                    listContainer.innerHTML = '';
                    data.forEach((d) => BuildGame(d, listContainer, false, false))
                    setUpCasinoFinderGameSelection(cl);
                }
            } else {
                data.forEach((d) => BuildArchive(d, listContainer))

            }
        }).catch((reason) => {
            console.log(reason)
        });
    }
;

const filter = (e, type, cl, filters, n = 0, isFinder = false) => {
    var listContainer = document.querySelector(cl);
    var page = parseInt(listContainer.getAttribute("data-filter-page"));

    var query = "";

    var dataAFObj = filters;
    var queryParams = "";
    if (dataAFObj) {
        Object.keys(dataAFObj).forEach((item, i) => {
            if (i > 0) {
                queryParams += "&";
            }
            queryParams += `${item}=${dataAFObj[item]}`;
        });
    }

    var pageParam = queryParams === "" ? `page=${page + 1}` : `&page=${page + 1}`;

    if (n !== 0) {
        queryParams += `&count=${n}`;
    }
    query = `${url}/${type}/search?${queryParams}${pageParam}`;
    $.get(query).then((data) => {
        const loading = e.childNodes[1];

        if (loading) loading.classList.toggle("d-none");
        const spinner = qsId("cfSpinner");
        if (spinner) spinner.classList.add("d-none");

        if (!isFinder)
            listContainer.setAttribute("data-filter-page", (++page).toString());
        listContainer.setAttribute("active-filters", JSON.stringify(filters));
        listContainer.innerHTML = ''

        data.forEach((d) => {
            if (filters['ui'] === 'card')
                BuildCasinoCard(d, listContainer)
            else
                BuildCasinoListItem(d, listContainer)
        })


        if (listContainer.innerHTML === '' && isFinder) {
            const noResults = document.createElement('h3');
            noResults.style.textAlign = 'center'
            noResults.innerHTML = "There are no casinos with these features!";
            listContainer.appendChild(noResults)
            qsId('seeall').classList.add('d-none');
            qsId('seeall').classList.remove('block');
        }
    });
};