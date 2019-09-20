function setHeightToAuto(ev) {
    if (ev.target.style.height !== "0px") {
        ev.target.style.height = "auto";
    }

    ev.target.removeEventListener("transitionend", setHeightToAuto);
}

function toggleSubMenu(ev) {
    if (ev.preventDefault !== undefined) {
        ev.preventDefault();
    }

    const parent = ev.target.parentNode.parentNode;
    const subNav = parent.querySelector("ul.Nav");

    if (
        ev.preventDefault !== undefined &&
        parent.classList.contains("Nav__item--open")
    ) {
        // Temporarily set the height so the transition can work.
        subNav.style.height = `${subNav.scrollHeight}px`;
        subNav.style.transitionDuration = `${Math.max(
            subNav.scrollHeight,
            150
        )}ms`;
        subNav.style.height = "0px";
        parent.classList.remove("Nav__item--open");
    } else {
        if (ev.preventDefault === undefined) {
            // When running at page load the transitions don't need to fire and
            // the classList doesn't need to be altered.
            subNav.style.height = "auto";
        } else {
            subNav.style.transitionDuration = `${Math.max(
                subNav.scrollHeight,
                150
            )}ms`;
            // After the transition finishes set the height to auto so child
            // menus can expand properly.
            subNav.addEventListener("transitionend", setHeightToAuto);
            subNav.style.height = `${subNav.scrollHeight}px`;
            parent.classList.add("Nav__item--open");
        }
    }
}

const navItems = document.querySelectorAll(
    ".Nav__item.has-children i.Nav__arrow"
);

// Go in reverse here because on page load the child nav items need to be
// opened first before their parents so the height on the parents can be
// calculated properly.
for (let i = navItems.length - 1, cur; i >= 0; i--) {
    cur = navItems[i];
    cur.addEventListener("click", toggleSubMenu);

    if (cur.parentNode.parentNode.classList.contains("Nav__item--open")) {
        toggleSubMenu({ target: cur });
    }
}
