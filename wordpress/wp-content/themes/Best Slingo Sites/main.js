const host = `${window.location.protocol + "//" + window.location.host}`;


class CasinoFinder {
    constructor() {
        if (window.screen.width > 1000) {
            this.buttonSteps = qsAll(".steps > button");
        } else {
            this.buttonSteps = qsAll(".steps-mobile > h3");
        }
        this.currentStep = 1;

        this.configureSteps();

    }

    configureSteps() {

        this.nextButtons = qsAll("button[data-finder='next-step']");
        this.backButtons = qsAll("button[data-finder='back-step']");

        if (window.screen.width > 1000) {
            if (qs('.steps')) this.currentButton = qs(".steps").children[this.currentStep - 1];
        } else {
            if (qs(".steps-mobile")) this.currentButton = qs(".steps-mobile").children[this.currentStep - 1];
        }

        this.buttonSteps.forEach((btn) => btn.addEventListener("click", (e) => this.stepClicked(e.target)));
        if (window.screen.width > 1000) {
            if (this.nextButtons) this.nextButtons.forEach((btn) => btn.addEventListener("click", (e) => this.stepChanged()));
            if (this.backButtons) {
                this.backButtons.forEach((btn) => btn.addEventListener("click", (e) => this.stepChanged(false)));
            }
        } else {
            if (this.nextButtons) this.nextButtons.forEach((btn) => btn.addEventListener("click", (e) => this.stepChangedMobile()));
            if (this.backButtons) {
                this.backButtons.forEach((btn) => btn.addEventListener("click", (e) => this.stepChangedMobile(false)));
            }
        }
    }

    stepClicked(a) {
        qsId("cfResults").classList.add("d-none"), this.currentButton.classList.remove("outlined"), a.classList.add("outlined"), this.currentButton = a;
        let b = qsId(`step-${this.currentStep}`);
        b.classList.add("d-none"), this.currentStep = this.getIndex(a) + 1;
        let c = qsId(`step-${this.currentStep}`);
        c.classList.remove("d-none"), this.currentStep > 1 ? qs(".search").classList.add("d-none") : qs(".search").classList.remove("d-none")
    }

    stepChangedMobile(a = !0) {
        qsId("cfResults").classList.add("d-none");
        let b = qsId(`step-${this.currentStep}`);
        b.classList.add("d-none"), this.currentButton.classList.add("d-none"), a ? this.currentStep++ : this.currentStep--, this.currentButton = qs(".steps-mobile").children[this.currentStep - 1], this.currentButton.classList.remove("d-none");
        let c = qsId(`step-${this.currentStep}`);
        c.classList.remove("d-none"), this.currentStep > 1 ? qs(".search").classList.add("d-none") : qs(".search").classList.remove("d-none")
    }

    stepChanged(a = !0) {
        qsId("cfResults").classList.add("d-none");
        let b = qsId(`step-${this.currentStep}`);
        if (b.classList.add("d-none"), this.currentButton.classList.remove("outlined"), a ? this.currentStep++ : this.currentStep--, this.currentButton = qs(".steps").children[this.currentStep - 1], this.currentButton) {
            this.currentButton.classList.add("outlined");
            let c = qsId(`step-${this.currentStep}`);
            c.classList.remove("d-none")
        }
        this.currentStep > 1 ? qs(".search").classList.add("d-none") : qs(".search").classList.remove("d-none")
    }

    getIndex(a) {
        for (var b = 0; null != (a = a.previousSibling);) "#text" !== a.nodeName && b++;
        return b
    }
}

const qs = a => document.querySelector(a), qsId = a => document.getElementById(a),
    qsAll = a => document.querySelectorAll(a), assignJsonStyle = (a, b) => {
        Object.assign(a.style, b)
    }, cf = qsId("casinoFinder");
var nextStep;
var back;

function addSelection(list, type, attribute) {
    list.forEach((item, i) => {
        item.addEventListener("click", (e) => {
            item.style.opacity = 1;

            var activeFilters = JSON.parse(cf.getAttribute("active-filters"));

            if (!activeFilters) {
                activeFilters = {};

                activeFilters[type] = item.getAttribute(attribute);
                cf.setAttribute("active-filters", JSON.stringify(activeFilters));
            } else {
                activeFilters[type] = item.getAttribute(attribute);
                cf.setAttribute("active-filters", JSON.stringify(activeFilters));
            }

            [...list]
                .filter((cfg) => cfg !== item && !cfg.classList.contains('button'))
                .forEach((item, i) => {
                    item.style.opacity = 0.3;
                });


            if (item.getAttribute("data-game-id")) {
                if (nextStep)
                    nextStep.remove()
                nextStep = qs("#step-1 button[data-finder='next-step']").cloneNode(true);
            } else {
                const step = qs("#step-2");
                if (!step.classList.contains("d-none")) {

                    if (nextStep)
                        nextStep.remove()
                    if (back)
                        back.remove()
                    nextStep = step.querySelector("button[data-finder='next-step']").cloneNode(true);
                    back = step.querySelector("button[data-finder='back-step']").cloneNode(true);
                } else {
                    if (nextStep)
                        nextStep.remove()
                    if (back)
                        back.remove()
                    nextStep = qs("#step-3 button[data-finder='find-casino']").cloneNode(true);
                    back = qs("#step-3 button[data-finder='back-step']").cloneNode(true)
                }
            }

            let offset = 0;
            if (window.screen.width >= 1300) {
                offset = -45;
            }
            list[i].parentElement.parentElement.appendChild(nextStep)
            nextStep.style.position = "absolute";
            nextStep.style.top = `${list[i].parentElement.offsetTop + 15}px`;
            nextStep.style.left = `${list[i].parentElement.offsetLeft + list[i].offsetWidth / 2 - offset}px`;

            if (back && !item.getAttribute("data-game-id")) {

                list[i].parentElement.parentElement.appendChild(back)
                back.style.position = "absolute";
                back.style.top = `${list[i].parentElement.offsetTop + 55}px`;
                back.style.left = `${list[i].parentElement.offsetLeft + list[i].offsetWidth / 2 - offset}px`;
            }

            casinoFinder.configureSteps()

        });
    });
}

function setUpCasinoFinderGameSelection(a) {
    let b = qsAll(`${a} > div > div[data-game-id]`);
    addSelection(b, "games", "data-game-id")
}

const bonuses = qsAll("#step-2 > div > div > div[data-article-id]");
addSelection(bonuses, "bonuses", "data-article-id");
const payments = qsAll("#step-3 > div > div > div[data-article-id]");

function toggleDetails(a) {
    let b = a.parentElement.nextElementSibling, c = a.childNodes[1];
    c.classList.toggle("rotate180"), b && b.classList.toggle("flex")
}

if (window.screen.width <= 770) {
    qsAll(".desktop").forEach((d) => {
        d.classList.remove("desktop")
    })
}

addSelection(payments, "payment_methods", "data-article-id"), setUpCasinoFinderGameSelection(".game-list"), document.addEventListener("click", e => {
    const nodeName = e.target.nodeName;
    if (nodeName === "BUTTON") {
        const att = e.target.getAttribute("data-bs-toggle");

        const loading = e.target.childNodes[1];
        if (loading && loading.nodeName !== "A") {
            e.target.classList.add('loading')
            loading.classList.toggle("d-none");
        }
    }
});
const setUpDetails = () => {
    let a = qsAll(".more-details");
    a && a.forEach(a => {
        a.addEventListener("click", () => toggleDetails(a))
    })
};
setUpDetails();
var tooltipTriggerList = [].slice.call(qsAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.map(function (a) {
    return new bootstrap.Tooltip(a)
});
const distanceFromTop = a => {
    var b = 0;
    if (a) do b += a.offsetTop, a = a.offsetParent; while (a)
    return b
};

function CreateContentTable() {
    let a = qsAll(".page .content-link-title"), d = qs(".content-links");
    if (0 === a.length) {
        var b = qs(".content-table");
        b && b.remove();
        var c = qs(".page");
        c && (c.style.gridTemplateColumns = "100%");
        return
    }
    d && a.forEach(f => {
        var a = document.createElement("div");
        a.classList.add("content-link-item");
        var b = document.createElement("h6");
        b.style.fontWeight = "bold", b.innerHTML = f.innerHTML;
        var c = document.createElement("img");
        c.src = "https://bestslingosites.co.uk/wp-content/uploads/2022/06/Vector.png", a.appendChild(b), a.appendChild(c);
        let e = qsId("insertBefore");
        e ? d.insertBefore(a, e) : d.appendChild(a)
    })
}

function ActivateTabOnScroll() {
    var b;
    let c = qsAll(".page .content-link-title"), a = [...qsAll(".content-link-item > h6")];
    let vec = qsAll(".content-link-item > img");

    vec.forEach((b, d) => {
        b.addEventListener("click", b => {
            window.scrollBy(0, -(distanceFromTop(a[d]) - (distanceFromTop(c[d]) + 50)))
        })
    })
    a.forEach((b, d) => {
        b.addEventListener("click", b => {
            window.scrollBy(0, -(distanceFromTop(a[d]) - (distanceFromTop(c[d]) + 50)))
        })
    }), document.addEventListener("scroll", () => {
        c.forEach((e, d) => {
            distanceFromTop(a[0]) < distanceFromTop(c[0]) && void 0 !== b && b.parentElement.classList.remove("active"), distanceFromTop(a[d]) >= distanceFromTop(e) && (void 0 !== b && b.parentElement.classList.remove("active"), a[d].parentElement.classList.add("active"), b = a[d])
        })
    })
}

function getCookie(e) {
    let c = e + "=", d = decodeURIComponent(document.cookie).split(";");
    for (let b = 0; b < d.length; b++) {
        let a = d[b];
        for (; " " === a.charAt(0);) a = a.substring(1);
        if (0 === a.indexOf(c)) return a.substring(c.length, a.length)
    }
    return ""
}

CreateContentTable(), ActivateTabOnScroll();
const cookiePolicy = () => {
    qs(".cookie-notice").style.display = "none";
    let a = new Date(2147483647e3).toUTCString();
    document.cookie = "policy=agreed; expires=" + a
};
"agreed" !== getCookie("policy") && (qs(".cookie-notice").style.display = "block");
const sticky = qs(".reveal-after-cf");
cf && sticky && (sticky.style.display = "none", document.addEventListener("scroll", a => {
    window.scrollY + 200 >= cf.clientHeight ? sticky.style.display = "flex" : sticky.style.display = "none"
}));
var myModal = document.getElementById("exampleModal"), myInput = document.getElementById("myInput");
if (myModal) {

    myModal.addEventListener("shown.bs.modal", function () {
        if (myInput) myInput.focus()
    })
}

function load() {
    const frame = qs("iframe");
    const src = frame.getAttribute('src');

    if (!src) {
        frame.setAttribute('src', localStorage.getItem('game_demo'))
    }
}


function unload() {
    const frame = qs("iframe");
    const src = frame.getAttribute('src');
    frame.setAttribute('src', '')

    localStorage.setItem("game_demo", src)
}

const casinoFinder = new CasinoFinder();
