import stopWords from "./stopwords";

export function getURLP(name) {
    const elements = new RegExp(`[?|&]${name}=([^&;]+?)(&|#|;|$)`).exec(
        window.location.search
    );

    return (
        decodeURIComponent(
            ((elements && elements[1]) || "").replace(/\+/g, "%20")
        ) || null
    );
}

function getScore(searchFor, page) {
    let score = 0;
    const pat = new RegExp(searchFor, "gi");

    if (page.title.search(pat) !== -1) {
        score += 20 * page.title.match(pat).length;
    }

    if (page.text.search(pat) !== -1) {
        score += 20 * page.text.match(pat).length;
    }

    if (page.tags.search(pat) !== -1) {
        score += 10 * page.tags.match(pat).length;
    }

    if (page.url.search(pat) !== -1) {
        score += 20;
    }

    return score;
}

function getStandardScore(searchWords, page) {
    let score = 0;
    for (let f = 0; f < searchWords.length; f++) {
        if (searchWords[f].match("^-")) {
            const pat = new RegExp(searchWords[f].substring(1), "i");
            if (
                page.title.search(pat) !== -1 ||
                page.text.search(pat) !== -1 ||
                page.tags.search(pat) !== -1
            ) {
                score = 0;
            }
        } else {
            score += getScore(searchWords[f], page);
        }
    }

    return score;
}

export function getSearchString(search) {
    let isStandard = true;
    let hasStopWords = false;
    if (
        (search.match('^"') && search.match('"$')) ||
        (search.match("^'") && search.match("'$"))
    ) {
        isStandard = false;
    }

    let searchFor;
    if (isStandard) {
        const searchWords = search.split(" ");
        searchFor = searchWords
            .filter(word => stopWords.indexOf(word) === -1)
            .join(" ");
        hasStopWords = search !== searchFor;
    } else {
        searchFor = searchFor.substring(1, searchFor.length - 1);
    }

    return {
        hasStopWords,
        isStandard,
        searchFor
    };
}

function makeResult(score, { title, url }, desc) {
    return {
        score,
        title,
        desc,
        url
    };
}

export function getResults(index, searchFor, standard) {
    const found = [];

    let pages = index.pages;

    // If a searchLanguage is set, filter out all other pages
    if (window.searchLanguage) {
        pages = pages.filter(
            item => item.url.indexOf(`${window.searchLanguage}/`) === 0
        );
    }

    const searchWords = searchFor.split(" ");
    for (let i = 0; i < pages.length; i++) {
        const score = standard
            ? getStandardScore(searchWords, pages[i])
            : getScore(searchFor, pages[i]);
        if (score !== 0) {
            found.push(makeResult(score, pages[i], pages[i].text));
        }
    }

    found.sort((a, b) => b.score - a.score);

    return found;
}
