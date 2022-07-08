const BuildCasinoListItem = (data, parent) => {
    const {
        welcome_bonus,
        fr_spins,
        expiry_period,
        wagering_t,
        about_cb,
        permalink,
        ratedImage,
        thumbnail,
        pros,
        games,
        code,
        affiliate_link, terms, codeIcon, arrowIcon
    } = data.meta;

    var bottom = $("<div class='details justify-center align-center'></div>");

    var bottomLeft = $("<div class='left'></div>")
        .append(`<p><span>Bonus</span> <span>${welcome_bonus}</span></p>`)
        .append(`<p><span>Free Spins</span> <span>${fr_spins}</span></p>`)
        .append(`<p><span>Wagering</span> <span>${expiry_period}</span></p>`)
        .append(`<p><span>Expiry Period</span> <span>${wagering_t}</span></p>`)

    var bottomRight = $("<div class='right'></div>")
        .append("<h5>About Casino Bonus</h5>")
        .append(`${about_cb}`)
        .append(`<a href="${permalink}" style='text-align: left'>Read full review</a>`)

    $(bottom).append(bottomLeft).append(bottomRight)


    var top = $("<div class='flex align-center d-column general'></div>")

    var topInfo = $("<div class='info flex align-center justify-center'></div>");

    var topRated = ratedImage !== "" ? $(`<img src='${ratedImage}' class='rated' width='70'
                         height='70' alt='top-rated-1'/>`) : "";

    $(topInfo).append(topRated).append(`<a href='${permalink}' style='margin-top: 0'> <img style='border-radius:8px' src='${thumbnail}' width='200' height='111' alt='${data.post_title + " thumbnail"}'></a>`);

    var whyPlay = $("<div class='text-list'></div>")
        .append("<h5>Why Play</h5>").append(ProsCons(pros[0], "pros"))


    $(topInfo).append(whyPlay).append(`<div class='slingo-games'>Slingo Games ${games.length}</div>`)

    var codeHtml = code ? `<div class='code'>${code} <img src='${codeIcon}' width='20' height='18' alt=''></div>` : "<div class='code' style='width: unset!important;display: block'><strong>No code required</strong></div>";
    $(topInfo).append(codeHtml);

    var cta = $("<div class='cta'></div>")
        .append(`<button class='button button-green'> <a href='${affiliate_link}'>Claim Bonus</a></button>`)
        .append(`<div style='text-align: center;margin-top: 1rem;'><a href='${permalink}'>Read review</a></div>`)

    $(topInfo).append(cta);


    var moreDetails = $(`<p href='#' class='more-details'>More Details</p>`)
        .append(`<img src='${arrowIcon}' width='14' height='9' alt=''>`)
    moreDetails.on('click', () => toggleDetails(moreDetails[0]))

    $(top).append(topInfo).append(`<div class='terms'>${terms}</div>`).append(moreDetails)


    var item = $("<div class='col-lg-12 col-md-6 casino-list-item flex align-center justify-center d-column'></div>").append(top).append(bottom)
    $(parent).append(item)
}

const BuildCasinoCard = (data, parent) => {
    var {
        permalink,
        ratedImage,
        card_thumb,
        top_rated,
        star_full,
        star_empty,
        affiliate_link, sh_title, no_deposit_bonus, ratingNum
    } = data.meta;

	star_full = "https://bestslingosites.co.uk/wp-content/uploads/2022/06/starfilled.png";
	star_empty = "https://bestslingosites.co.uk/wp-content/uploads/2022/06/starempty.png"

    const alt = data.post_title + " thumbnail";
    var topRated = ratedImage !== "" ? $(`<img src='${ratedImage}' class='rated' width='70'
                         height='70' alt='top-rated-1'/>`) : "";

    let header;
    if (!sh_title || sh_title === "") {
        if (top_rated[0] <= 3) {
            // header = $('<div class="header"></div>')
            // header.append(topRated)

        } else {
            header = $('<div ></div>')

        }
    } else {
        let b = false;
        if (top_rated[0] <= 3) {

            // header = $('<div class="header"></div>')
            // b = true;
            // header.append(topRated)
        }
        if (sh_title) {
            if (!b) {

                header = $('<div class="header"></div>')
            }

            header.append(`<h5>${sh_title}</h5>`);
        }

    }


    var container = $("<div class='col-xl-4  col-md-6'></div>");

    var card = $("<div class='casino-card flex d-column justify-center align-center pb-4 pt-4 mt-3'></div>")
        .append(header)
        .append(`<a href="${permalink}"><img src='${card_thumb[0]}' width='150' height='150' alt='${alt}'></a>`)
        .append(`<h5 style='color:white;'>${data.post_title}</h5>`)
        .append(`<p class='bonus'> ${no_deposit_bonus !== undefined ? no_deposit_bonus : 'No code required'}</p>`);


    var rating = $("<div class='rating'></div>");
    var ratingInfo = $("<div class='rating-info'></div>")
        .append(`<h5 class='mb-0' style='text-align: center;font-weight: bold;color: white'>${ratingNum}</h5>`)
        .append(Stars(ratingNum, star_full, star_empty))
        .append(`<a href='${permalink}'>
                        <p style='color: white;text-decoration: underline;text-align: center'>Read review</p>
                        </a>`)

    rating.append(ratingInfo)
        .append(`<a href='${affiliate_link}'>    
                    <button class='button button-orange' style='padding: 6px 27px;vertical-align: text-bottom'>Visit Casino
                    </button>
                    </a>`)

    card.append(rating)
    $(container).append(card)
    $(parent).append(container)
}
const BuildGame = (data, parent, linkImage = true, hasButton = true) => {
    const {permalink, thumbnail, post_title, ID} = data;
    var container = $("<div class='col-xl-4 col-lg-6 col-12 '></div>");
    var alt = post_title + " thumbnail";
    var a = linkImage
        ? `<a href='${permalink}'><img  style='max-width:100%' src='${thumbnail}' width='373' height='210'  alt='${alt}'></a>`
        : `<a href='#' onclick='return false'><img  style='max-width:100%' src='${thumbnail}' width='373' height='210'  alt='${alt}'></a>`;
    var aContainer = $(`<div  style='max-width:100%' data-game-id='${ID}' class='card-simple flex d-column justify-center align-center'></div>`);
    var button = hasButton ? `<a style='text-decoration:none' href='${permalink}'><button style='padding: 1px 20px' class='button button-gray block m-auto mb-3'>Play Free
        </button></a>` : "";

    $(aContainer).append(a)
        .append(`<h4 class='slot-title m-auto'>${post_title}</h4>`)
        .append(button)

    container.append(aContainer)
    $(parent).append(container);
}
const BuildArchive = (data, parent) => {
    const {permalink, thumbnail, post_title, post_excerpt} = data;
    var alt = post_title + " thumbnail";
    var container = $("<article></article>")
        .append(`<a href='${permalink}'><img src='${thumbnail}' width='336' height='198' alt='${alt}'></a>`)
    var articleInfo = $("<div class='article-info hide-1000'></div>")
        .append(`<h3>${post_title}</h3>`)
        .append(`<p class='excerpt'>${post_excerpt}</p>`)
        .append(`<button class='button button-orange' style='padding: 15px 20px'><a
                          href='${permalink}'>Read More</a>
              </button>`)

    container.append(articleInfo)
        .append(`<h4 style='display: none' class='show-1000'>${post_title}</h4>`)
        .append(`<button class='button button-orange show-1000' style='display: none;padding: 15px 20px'><a
                      href='${permalink}'>Read More</a>
          </button>`)

    $(parent).append(container);
}
const Stars = (overall, starFull, starHalf) => {
    var res = $("<div class='stars'></div>");
    let i;
    for (i = 0; i < overall; i++) {
        $(res).append(`<span><img class='class-rating' src='${starFull}' width='20' height='20' alt=''></span>`);
    }

    for (; i < 10; i++) {
        $(res).append(`<span><img class='class-rating' src='${starHalf}' width='20' height='20' alt=''></span>`);
    }
    return res;
}
const ProsCons = (sentence, cl) => {
    var output = $("<div></div>");
    const sentences = sentence.split(';');

    sentences.forEach((sentence, i) => {

        if (i < 3)
            $(output).append(`<p class='text-list-item ${cl}'>${sentence}</p>`)

    })
    return output;
}