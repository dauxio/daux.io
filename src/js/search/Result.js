import preact from "preact";
/** @jsx preact.h */

// TODO :: restore highlight
/*function highlightText(search, text) {
    if (settings.highlightTerms) {
        var pattern = new RegExp(
            `(${search})`,
            settings.highlightEveryTerm ? "gi" : "i"
        );
        text = text.replace(
            pattern,
            '<span class="SearchResults__highlight">$1</span>'
        );
    }

    return text;
}*/

export default function Result({ settings, item }) {
    let description;
    if (item.desc) {
        description = item.desc
            .split(" ")
            .slice(0, settings.descriptiveWords)
            .join(" ");
        if (
            item.desc.length < description.length &&
            description.charAt(description.length - 1) !== "."
        ) {
            description += " ...";
        }
    }

    return (
        <div className="SearchResult">
            <div className="SearchResults__title">
                <a href={settings.base_url + item.url}>{item.title}</a>
            </div>
            {settings.debug && (
                <div className="SearchResults__debug">Score: {item.score}</div>
            )}
            {settings.showURL && (
                <div className="SearchResults__url">
                    <a href={settings.base_url + item.url}>
                        {item.url.toLowerCase().replace(/https?:\/\//g, "")}
                    </a>
                </div>
            )}
            {description.desc && (
                <div className="SearchResults__text">{description}</div>
            )}
        </div>
    );
}
